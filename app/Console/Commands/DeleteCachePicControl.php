<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DeleteCachePicControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeleteCachePic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时删除没上传活动的图片';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Cache::has('cache_pic')) {
            $cachePic = Cache::get('cache_pic');
            foreach ($cachePic as $key => $pic) {
                if (!isset($pic['expire_time']) || $pic['expire_time'] < time()) {
                    $path = 'public/' . $pic['path'];
                    if (file_exists($path)) {
                        if (!is_writable(dirname($path))) {
                        } else {
                            try {
                                unlink($path);
                                unset($cachePic[$key]);
                            } catch (Exception $e) {
                                $errorObj = $e->getPrevious()->getPrevious();
                                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                            }
                        }
                    } else {
                        unset($cachePic[$key]);
                    }
                }
            }
            $hourToStore = 24 * 2;
            $expiresAt = Carbon::now()->addHours($hourToStore);
            Cache::put('cache_pic', $cachePic, $expiresAt);
        }
    }
}
