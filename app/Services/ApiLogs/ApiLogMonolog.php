<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 9:45 AM
 */

namespace App\Services\ApiLogs;

use App\Services\LogsCommons\CommonLogHandler;
use Monolog\Logger;

class ApiLogMonolog
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger('apibyqueue');
        $logger->pushHandler(new CommonLogHandler());
        $logger->pushProcessor(new ApiLogProcessor());
        return $logger;
    }

}
