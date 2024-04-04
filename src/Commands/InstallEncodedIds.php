<?php
namespace Davide7h\EncodedIds\Commands;

use Illuminate\Console\Command;

class InstallEncodedIds extends Command
{
    protected $signature = 'encoded-ids:install';
    protected $description = 'Install the Encoded Ids package';

    public function handle()
    {
        //Check if the config file has already been generated
        $filePath = config_path('encoded-ids.php');
        if (file_exists($filePath)) {
            $this->warn("The Encoded Ids config file has already been published. Do you wish to regenerate it?\nBEWARE: This will cause previously encoded ids to be PERMANENTLY LOST.");

            $choice = $this->choice('Regenerate encoding alphabet and force config publish?', ['Yes', 'No'], 1);
            if ($choice == 'No'){
                $this->info('The existing files will not be overwritten. Everything will continue working as usual.');
                return;
            } 
        }
        require_once(__DIR__ . '/../../generate-alphabet.php');
        $this->call('vendor:publish', ['--tag' => 'config', '--provider' => 'Davide7h\EncodedIds\Providers\EncodedIdsProvider', '--force' => true]);
    }
}