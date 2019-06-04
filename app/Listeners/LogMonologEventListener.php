<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 10:41 AM
 */

namespace App\Listeners;

use App\Models\Admin\Logs;
use App\Services\Logs\LogMonologEvent;
use App\Services\LogsCommons\CommonLogMonologEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class LogMonologEventListener implements ShouldQueue
{
    public $queue = 'logs';
//    public $connection = 'beanstalkd';
    protected $log;
    protected $recordedDays;

    public function __construct(Logs $log)
    {
        $this->log = $log;
    }

    /**
     * @param $event
     */
    public function onLog($event)
    {
        $log = new $this->log;
        $this->recordedDays = config('logsetting.day');
        //7天以上的数据都删掉
        $date = Carbon::now()->subDays($this->recordedDays)->format('Y-m-d H:i:s');
        $logEloq = $log->where('created_at', '<', $date)->get();
        if (!$logEloq->isEmpty()) {
            foreach ($logEloq as $items) {
                $items->delete();
            }
        }
        //记录日志
        $log->fill($event->records['formatted']);
        $log->save();
    }

    /**
     * Register the listeners for the subscriber.
     * @param $events
     */
    public function subscribe($events)
    {
        try {
            $events->listen(
                CommonLogMonologEvent::class,
                'App\Listeners\LogMonologEventListener@onLog'
            );
        } catch (\Exception $e) {
            Log::channel('daily')->error(
                $e->getMessage(),
                array_merge($this->context(), ['exception' => $e])
            );
        }
    }

}
