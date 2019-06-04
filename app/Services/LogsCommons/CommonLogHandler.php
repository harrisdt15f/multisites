<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 9:48 AM
 */

namespace App\Services\LogsCommons;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class CommonLogHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG)
    {
        parent::__construct($level);
    }

    protected function write(array $record)
    {
        // Queue implementation
        event(new CommonLogMonologEvent($record));
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new CommonLogFormatter();
    }

}
