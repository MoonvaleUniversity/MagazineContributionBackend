<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Is your .env file configured correctly?', true)) {
            $this->call('migrate');
            $this->call('app:generate-roles-and-permissions');
            $this->call('db:seed');
            $this->call('storage:link');
            $this->info('Project setup completed successfully. You can now run `php artisan serve` to run the project');
        } else {
            $this->warn('Setup aborted. Please configure your .env file first.');
        }
    }
}
