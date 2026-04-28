<?php

namespace App\Console\Commands;

use App\Network\Facades\Network;
use Illuminate\Console\Command;

class PurgeNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all the rooms from the network. Useful on app boot to remove dangling rooms.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->info('Purging the network...');
            network()->purge();
            $this->info('The network has been purged.');
            return 0;
        } catch (\Throwable $throwable) {
            $this->error('The network purge failed.');
            throw $throwable;
        }
    }
}
