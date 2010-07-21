<?php

/**
 * Initialises the application, break this, break the whole app
 *
 * @author Chris Lock
 * @copyright Copyright (c) CognoLink.com  
 * @internal Complexity Level : LOW
 * @version $Id$
 */

require_once 'Zend/Controller/Plugin/Abstract.php';
require_once( APPLICATION_PATH . '/../inc/functions.inc.php' );

/**
 * 
 * Initializes configuration depndeing on the type of environment 
 * (test, development, production, etc.)
 *  
 * This can be used to configure environment variables, databases, 
 * layouts, routers, helpers and more
 *   
 */
class Initializer extends Zend_Controller_Plugin_Abstract
{
	
    /**
     * @var Zend_Config
     */
    protected $_config;

    /**
     * @var string Current environment
     */
    protected $_env;

    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    /**
     * @var string Path to application root
     */
    protected $_root;
    
    /** 
     * @var obj Zend_Registry
     */
    protected $_registry;

    /**
     * Constructor
     *
     * Initialize environment, root path, and configuration.
     * 
     * @param  string $env 
     * @param  string|null $root 
     * @return void
     */
    public function __construct($env, $root = null)
    {
        $this->_setEnv($env);
        if (null === $root) {
            $root = realpath(dirname(__FILE__) . '/../');
        }
        $this->_root = $root;

        // Set up autoload.
        require_once "Zend/Loader/Autoloader.php";
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Recipe_');
		$autoloader->setFallbackAutoloader(true);
		
		$this->_registry = Zend_Registry::getInstance();
        
        $this->initPhpConfig();
        
        $this->_front = Zend_Controller_Front::getInstance();
        
        // set the test environment parameters
        if ($env == 'development') {
			// Enable all errors so we'll know when something goes wrong. 
			error_reporting(E_ALL | E_STRICT);  
			ini_set('display_startup_errors', 1);  
			ini_set('display_errors', 1); 

			$this->_front->throwExceptions(true);  
        } 
    }

    /**
     * Initialize environment
     * 
     * @param  string $env 
     * @return void
     */
    protected function _setEnv($env) 
    {
		$this->_env = $env;    	
    }
    

    /**
     * Initialize configuration
     * 
     * @return void
     */
    public function initPhpConfig()
    {	
    	$this->_config = new Zend_Config_Xml(APPLICATION_PATH . '/../config.xml', APPLICATION_ENVIRONMENT);
    	$this->_registry->set('config', $this->_config );
    }
    
    /**
     * Route startup
     * 
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
    	$this->initLog();
       	$this->initDb();
        $this->initHelpers();
        $this->initView();
        $this->initPlugins();
        $this->initRoutes();
        $this->initControllers();
        // $this->initCache();
        $this->initMail();
        $this->initSession();
    }
    
    /**
     * Initialise the log
     * 
     * @return void
     */
    
    public function initLog()
    {
    	$log = new Zend_Log( new Zend_Log_Writer_Stream( $this->_config->log->file, 'a' ) );
		$log->addWriter( new Zend_Log_Writer_Firebug() );
		$this->_registry->set('log', $log );
    }
    
    /**
     * Initialize data bases
     * 
     * @return void
     */
    public function initDb()
    {
    	$db = Zend_Db::factory($this->_config->database);
		// defaule all queries to be utf8 compliant
		$db->query('SET NAMES utf8');
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		$this->_registry->set('db', $db );
    }

    /**
     * Initialize action helpers
     * 
     * @return void
     */
    public function initHelpers()
    {
    	// register the default action helpers
    	Zend_Controller_Action_HelperBroker::addPath('../application/default/helpers', 'Zend_Controller_Action_Helper');
    }
    
    /**
     * Initialize view 
     * 
     * @return void
     */
    public function initView()
    {
		// Bootstrap layouts
		Zend_Layout::startMvc(array(
		    'layoutPath' => $this->_root .  '/app/layouts',
		    'layout' => 'layout',
			'doctype' => 'XHTML1_STRICT'
		));
		$view = Zend_Layout::getMvcInstance()->getView();
		$view->addHelperPath( 'Recipe/View/Helper/', 'Recipe_View_Helper' );
		$view->setScriptPath( APPLICATION_PATH . '/views/' );    	
    }
    
    /**
     * Initialize plugins 
     * 
     * @return void
     */
    public function initPlugins()
    {
		//$this->_front->registerPlugin(new Recipe_Plugin_Acl());
    }
    
    /**
     * Initialize routes
     * 
     * @return void
     */
    public function initRoutes()
    {
    
    }

    /**
     * Initialize Controller paths 
     * 
     * @return void
     */
    public function initControllers()
    {
    	$this->_front->addControllerDirectory($this->_root . '/app/controllers', 'default');
    }
    
    /**
     * Initialise the session
     * 
     * @return void
     */
    
    public function initSession()
    {
		Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($this->_config->sessions));
		Zend_Session::start();
		$session = new Zend_Session_Namespace();
		$this->_registry->set( 'session', $session );
    }
    
    /**
     * Initialize Mail
     * 
     * @return void
     */
    
    public function initMail()
    {
    	// MAIL Setup
		$tr = new Zend_Mail_Transport_Smtp($this->_config->mail->smtp_server, $this->_config->mail->toArray());
		Zend_Mail::setDefaultTransport($tr);
    }
    
    public function initCache()
    {
    	$cache = Zend_Cache::factory(
			'Core',
    		'File',
    		array(
    			'lifetime' => 7200, // cache lifetime of 2 hours
    			'automatic_serialization' => true
			),
    		array(
    			'cache_dir' => APPLICATION_PATH . '/../cache/' // Directory where to put the cache files
			)
		);
		$this->_registry->set( 'cache', $cache );
    }
    
}

// @todo CACHE
