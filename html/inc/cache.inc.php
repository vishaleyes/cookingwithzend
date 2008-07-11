<?php

$frontendOptions = array(
   'lifetime' => $config->cache_lifetime,
   'automatic_serialization' => true
);

if ( $_SERVER['DOCUMENT_ROOT'] ) {
    $backendOptions = array(
        'cache_dir' => $_SERVER['DOCUMENT_ROOT'].'/cache/' // Directory where to put the cache files
    );
} else {
    $backendOptions = array(
        'cache_dir' => dirname(dirname(__FILE__)).'/cache/' // Directory where to put the cache files
    );
}

$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

Zend_Registry::set('cache', $cache);
