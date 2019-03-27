<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 9:45 AM
 */

namespace App\Services\ApiLogs;


use Monolog\Logger;

class ApiLogMonolog
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger('apibyqueue');
        $logger->pushHandler(new ApiLogHandler());
        $logger->pushProcessor(new ApiLogProcessor());
        return $logger;
    }

}
