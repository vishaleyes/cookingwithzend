<?php

/**
 * Cleanup file - 
 * Frees memory by unsetting objects/variables or object's references that have been set up but are no longer needed to be visible in the scope of the application 
 * since they were made singletons by using Zend_Registry::set()
 * This will make a significant impact on the performance as the traffic grows
 * @author Marcin Dominas : marcin.dominas@bd-ntwk.com
 * @package Generic Framework
 * @see : http://digitalwiki.bd-ntwk.com/index.php?title=BD_Network_PHP_Application_Framework
 */

unset($url);
unset($match);
unset($release_no);
unset($request);
unset($frontendOptions);
unset($backendOptions);
unset($cache);
unset($environment);
unset($config);
unset($hosts);
unset($use_db);
unset($db);
unset($test);
unset($columnMapping);
unset($writer);
unset($formatter);
unset($log);
unset($r);
unset($session);
unset($signature);