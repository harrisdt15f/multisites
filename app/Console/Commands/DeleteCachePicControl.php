<?php

namespace App\Console\Commands;

use App\Lib\Common\CacheRelated;
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
        $tags = 'images';
        $redisKey = 'cleaned_images';
        $cleanedImages = CacheRelated::getTagsCache($tags, $redisKey);
        if ($cleanedImages !== false) {
            foreach ($cleanedImages as $key => $pic) {
                if (!isset($pic['expire_time']) || $pic['expire_time'] < time()) {
                    $path = 'public/' . $pic['path'];
                    if (file_exists($path)) {
                        if (!is_writable(dirname($path))) {
                        } else {
                            unlink($path);
                            unset($cleanedImages[$key]);
                        }
                    } else {
                        unset($cleanedImages[$key]);
                    }
                }
            }
            CacheRelated::setTagsCache($tags, $redisKey, $cleanedImages);
            $minuteToStore = 60 * 24 * 2;
            Cache::tags($tags)->put($redisKey, $cleanedImages, $expiresAt, $minuteToStore);
        }
    }
}
