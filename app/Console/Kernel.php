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
        $schedule->command('GenerateIssue')->everyMinute();
        //自开彩种自动开奖 option --> lottery_sign
        $lotteryScheduleEloqs = CronJob::getOpenCronJob();
        foreach ($lotteryScheduleEloqs as $item) {
            $criterias = json_decode($item->param, true);
            $schedule->command($item->command, [$criterias])->cron($item->schedule);
        }
        $schedule->command('UserProfits')->everyFiveMinutes();
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
