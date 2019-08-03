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
        Commands\UserProfitsControl::class,
        Commands\UserDaysalaryControl::class,
        Commands\SendDaysalaryControl::class,
        Commands\UserBonusControl::class,
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

        $schedule->command('UserProfits')->everyFiveMinutes();

        //每日2点 统计用户日工资
        $schedule->command('UserDaysalary')->daily()->at('02:00');
        //每日3点 发放用户日工资
        $schedule->command('SendDaysalary')->daily()->at('03:00');

        //每月1号15号 统计计算代理分红
        $schedule->command('UserBonus')->monthlyOn(1, '4:00');
        $schedule->command('UserBonus')->monthlyOn(15, '4:00');
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
