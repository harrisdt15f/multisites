<?php
namespace App\Console\Commands;

use App\Models\User\UserCommissions;
use Illuminate\Console\Command;

class SendCommissionsControl extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendCommissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发放佣金';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        UserCommissions::sendCommissions(152342); // 测试用
    }
}
