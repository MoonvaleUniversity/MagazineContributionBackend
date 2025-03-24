<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Contribution\Services\Implementations\ContributionApiService;

class SendContributionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contribution-reminder:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send emails ';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Checking for contributions without comments...");
        $contributionApiService = app(ContributionApiServiceInterface::class);
        $contributionApiService->automatic();
        $this->info("Emails sent successfully.");
    }
}
