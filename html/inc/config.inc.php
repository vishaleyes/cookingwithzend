<?php

/**
 * Config file - figures out what stage the application is on.
 */

$environment = '';
$config_path = 'config.xml';

if ( array_key_exists( 'HTTP_HOST', $_SERVER ) ) {
    if ((substr($_SERVER['HTTP_HOST'], 0, strlen('local')) == 'local')||(stristr($_SERVER['HTTP_HOST'], 'local')))
    {
        $environment = 'development';
        $config = new Zend_Config_Xml($config_path, 'local');
        
    } elseif (substr($_SERVER['HTTP_HOST'], 0, strlen('qa')) == 'qa') {
        $environment = 'qa';
        $config = new Zend_Config_Xml($config_path, 'qa');
    } elseif (substr($_SERVER['HTTP_HOST'], 0, strlen('dev')) == 'dev') {
        $environment = 'development';
        $config = new Zend_Config_Xml($config_path, 'development');
    } elseif (substr($_SERVER['HTTP_HOST'], 0, strlen('staging')) == 'staging') {
        $environment = 'staging';
        $config = new Zend_Config_Xml($config_path, 'staging');
    } else {
        $environment = 'production';
        $config = new Zend_Config_Xml($config_path, 'production');

        $hosts = explode(' ',$config->hosts);

        if (!in_array($_SERVER['HTTP_HOST'],$hosts,true))
        {
            $environment = 'development';
            $config = new Zend_Config_Xml($config_path, 'development');
        }
    }
} else {
    $file = dirname(dirname(__FILE__)) . '/' . $config_path;
    if ( ! ENVIRONMENT ) {
        $config = new Zend_Config_Xml($file, 'production');
    } else {
        $config = new Zend_Config_Xml($file, ENVIRONMENT);
    }
}

// Add the routes
$router = $controller->getRouter();
$router->addConfig( $config, 'routes' );

Zend_Registry::set('environment', $environment);
Zend_Registry::set('config', $config);
