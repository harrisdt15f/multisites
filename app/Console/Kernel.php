<?php

namespace App\Console;

use App\Models\Game\Lottery\CronJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        Commands\LotterySchedule::class,
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
        $scheduleArr = CronJob::getOpenCronJob();
        foreach ($scheduleArr as $scheduleItem) {
            $criterias = json_decode($scheduleItem['param'], true);
            if (empty($criterias)) {
                $schedule->command($scheduleItem['command'])->cron($scheduleItem['schedule']);
            } else {
                $schedule->command($scheduleItem['command'], [$criterias])->cron($scheduleItem['schedule']);
            }
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
