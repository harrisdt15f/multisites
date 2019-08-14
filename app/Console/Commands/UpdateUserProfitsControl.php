<?php
namespace App\Console\Commands;

use App\Jobs\UpdateUserProfits;
use Illuminate\Console\Command;

class UpdateUserProfitsControl extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateUserProfits  {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推入消息队列更新用户盈亏脚本';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        dispatch(new UpdateUserProfits($this->argument('userId')));
    }
}
