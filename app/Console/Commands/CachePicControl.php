<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        Log::info('开始定时删除没上传活动的图片' . date('Y-m-d H:i:s', time()));
        if (Cache::has('CachePic')) {
            $CachePic = Cache::get('CachePic');
            foreach ($CachePic as $k => $v) {
                if ($v['expire_time'] < time()) {
                    $path = 'public/' . $v['path'];
                    if (file_exists($path)) {
                        if (!is_writable(dirname($path))) {
                            Log::info($path . '权限不足');
                        } else {
                            try {
                                unlink($path);
                                unset($CachePic[$k]);
                                Log::info('清除图片缓存成功');
                            } catch (Exception $e) {
                                $errorObj = $e->getPrevious()->getPrevious();
                                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                                Log::info('清除图片缓存错误' . $msg);
                            }
                        }
                    } else {
                        Log::info($path . '图片文件不存在');
                    }
                }
            }
            $minutes = 2 * 24 * 60;
            Cache::put('CachePic', $CachePic, $minutes);
        }
    }
}
