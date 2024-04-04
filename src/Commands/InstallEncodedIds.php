<?php
namespace Davide7h\EncodedIds\Commands;

use Illuminate\Console\Command;

class InstallEncodedIds extends Command
{
    protected $signature = 'encoded-ids:install';
    protected $description = 'Install the Encoded Ids package';

    public function handle()
    {
        exec('generate-alphabet.php');
        $this->call('vendor:publish', ['--tag' => 'config', '--provider' => 'Davide7h\EncodedIds\Providers\EncodedIdsProvider']);
    }
}