<?php

namespace App\Console;

use App\Models\Admin\SystemConfiguration;
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
        Commands\ZxyfcInputCodeControl::class,
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
            $systemConfiguration = new SystemConfiguration();
            $generateIssueTime = $systemConfiguration->getConfigValue('generate_issue_time');
            Cache::forever('generateIssueTime', $generateIssueTime);
        }
        $schedule->command('GenerateIssue')->daily()->at($generateIssueTime);
        //中兴一分彩自动开奖
        $schedule->command('ZxyfcInputCode')->everyMinute();
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
