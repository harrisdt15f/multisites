<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 10:41 AM
 */

namespace App\Listeners;

use App\models\Logs;
use App\Services\Logs\LogMonologEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogMonologEventListener implements ShouldQueue
{
    public $queue = 'logs';
    public $connection = 'beanstalkd';
    protected $log;
    public function __construct(Logs $log) {
        $this->log = $log;
    }
    /**
     * @param $event
     */
    public function onLog($event)
    {
        $log = new $this->log;
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
                LogMonologEvent::class,
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
