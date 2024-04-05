![Logo](https://i.ibb.co/njZqNb5/encoded-ids.webp)
# ID Masking for your Eloquent Models

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![Stability](https://img.shields.io/badge/Framework-Laravel-red.svg
)](https://getcomposer.org/doc/04-schema.md#minimum-stability)
[![Latest Version](https://img.shields.io/badge/Latest_Version-1.0.6-ffed00.svg
)](https://getcomposer.org/doc/04-schema.md#minimum-stability)
[![Stability](https://img.shields.io/badge/Min_Stability-alpha-blue.svg
)](https://getcomposer.org/doc/04-schema.md#minimum-stability)

## Documentation, Installation, and Usage Instructions

### What It Does
This package allows you to use a convenient Trait to instantly enable all your Eloquent Models with encoded ids for you to use in routes and api endpoints without exposing to your users the real IDs for your database records. 

### Requirements

This package is under active development, therefore its requirements may be subject to change. At the moment it's been successfully tested on:
- Laravel ^8.x | ^9.x | ^10.x
- PHP >= 8.1

### Important Security Notes
This package requires [Sqids](https://sqids.org/) package for hashing the numeric IDs. It will be automatically installed. 

It's important to reiterate what the authors of the Sqids package clearly state on their website: **this is not an encryption library**, and the generated IDs can **still** be deciphered if an attacker were to find out what alphabet was used to generate it. It only serves a cosmetic purpose, rendering IDs less obvious and more hard to guess, but not cryptographically encrypted.

**Do not use it to hide sensitive information!**

### Installation
Just run 
 ```composer require davide7h/encoded-ids```
 
This will download and extract the package and its dependencies ([Sqids](https://sqids.org/)). The auto-discovery feature will automatically register the package ServiceProvider. 

#### Generating Alphabet and publishing configurations

```php artisan encoded-ids:install```.

This command will generate a randomly shuffled alphabet that will be used to encode and decode your IDs. 

It will also publish the config file ```config/encoded-ids.php```. Here you will find:
- the newly generated **alphabet** (in case you need to alter it or replace it with an entirely new one)
- the **padding** key, used to specify a *minimum length* for the encoded id (default: 8char) 

That's it, you're good to go!

## Usage

### Encoding Model IDs
Providing your Models with the ability to display a masked version of their actual IDs is as simple as using the ```HasEncodedId``` Trait:

```php
<?php

namespace App\Models;

...
use Davide7h\EncodedIds\Traits\HasEncodedId;
...

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasEncodedId;
    
    ...
}
```
Now your model will feature the ```encoded_id``` attribute, which you can use to access a masked version of your model's ID:

```php
$user = User::first();
echo $user->id;         //example output: 1
echo $user->encoded_id; //example output: "ULwu08NB"
```
### Retrieving Models
The package provides a convenient way to query the Database for records using the ```encoded_id``` attribute, without any need for decoding it. Just call the ```find``` or ```findOrFail``` methods on your model's class, like you would normally do using the acutal (numeric) IDs for your records as parameters, but with the option to also use their masked version (as a string):
```php
User::find(1)          //returns the User model with ID = 1;
User::find("ULwu08NB") //also returns the User with ID = 1;
```
### Decoding IDs
As discussed before, there is no need for explicitly decoding your IDs before queueing the Database. Should the need for manual decoding ever arise, though, the package provides an ```EncodingService``` class that can be used anywhere in your code to encode or decode integer numbers for any reason you might think of.
```php
...
use Davide7h\EncodedIds\Services\EncodingService;
...

EncodingService::encode(1);          //example output: "ULwu08NB"
EncodingService::decode("ULwu08NB"); //example output: 1;
```
Please note that the ```encode``` method of the ```EncodingService``` class expects an ```Int``` as a parameter and returns a ```String```, while the ```decode``` method expects a ```String``` and returns the decoded ```Int``` if the given string is a valid encoding, ```null``` otherwise.

### Parametric routes
Using ```Dependency Injection``` to fetch the actual model from a parametric route is pretty handy, but unfortunately it only works when the parameter used to populate the route exists as a column in your model's database table. When using computed attributes (like ```encoded_id``` )the workaround used to be giving up on the dependency injection and just query the database for the desired model after decoding it inside the controller:
```php
//Route file:
Route::get('/users/{encoded_id}', [UserController::class, 'getUser'])

//UserController class:
public function getUser(String $encoded_id)
{
    $user = User::findOrFail($encoded_id);
    ...
}
```

But fear not! This package extends Laravel's native route binding functionalities enabling you to use encoded ids in your parametric routes and still being able to inject your models in your Controller methods without having to manually querying the database inside of it, leaving room for the actual controller logic without cluttering it with unnecessary operations:
```php
//Route file
Route::get('/users/{user:encoded_id}', [UserController::class, 'getUser'])

//UserController
public function getUser(User $user)
{
    ....
}

```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.