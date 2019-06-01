<?php

namespace App\Console;

use App\Models\Admin\PartnerSysConfigures;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DeleteCachePicControl::class,
        Commands\AllocationRechargeFundControl::class,
        Commands\GenerateIssueControl::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('DeleteCachePic')->daily()->at('03:00');
        $schedule->command('AllocationRechargeFund')->daily()->at('00:00');
        //定时生成奖期
        if (Cache::has('generateIssueTime')) {
            $generateIssueTime = Cache::get('generateIssueTime');
        } else {
            $PartnerSysConfigures = new PartnerSysConfigures();
            $generateIssueTime = $PartnerSysConfigures->getConfigValue('generate_issue_time');
            Cache::forever('generateIssueTime', $generateIssueTime);
        }
        $schedule->command('GenerateIssue')->daily()->at($generateIssueTime);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
