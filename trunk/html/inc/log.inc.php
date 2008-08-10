<?php

/**
 * Log file - depending on configuration settings it figures out where to write logs
 */

$log = new Zend_Log();

// log to file
$textWriter = new Zend_Log_Writer_Stream( $config->logs->application );
$formatter = new Zend_Log_Formatter_Simple('%timestamp% %priorityName% %message%' . PHP_EOL);
$textWriter->setFormatter($formatter);
$log->addWriter($textWriter);

Zend_Registry::set('log', $log);

