<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 9:45 AM
 */

namespace App\Services\Logs;


use App\Services\LogsCommons\CommonLogHandler;
use Monolog\Logger;

class LogMonolog
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger('byqueue');
        $logger->pushHandler(new CommonLogHandler());
        $logger->pushProcessor(new LogProcessor());
        return $logger;
    }

}
