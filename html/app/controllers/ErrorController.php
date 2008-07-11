<?php
class ErrorController extends DefaultController 
{
    protected $_error = null; 
     
	/**
	 * Folder where all pages should be stored
	 *
	 * @var string
	 */
	protected $pagesFolder;
	/**
	 * Folder where all temlpates should be stored
	 *
	 * @var string
	 */
	protected $templatesFolder;
	/**
	 * Folder where all other application's specific include files should be stored
	 *
	 * @var string
	 */
	protected $includesFolder;
	/**
	* Logger Object (usage : $this->log->log("Message to log", 0);)
	*
	* @var Zend_Log
	*/
	protected $log;
		    
    public function errorAction() 
    { 
        $this->_error = $this->_getParam('error_handler');
        
    	switch ($this->_error->type) { 
            case 'EXCEPTION_NO_CONTROLLER': 
            case 'EXCEPTION_NO_ACTION': 
                $this->notFoundAction();
                return;
            case 'EXCEPTION_OTHER':
                $this->serverErrorAction();
                return; 
        } 
    }

    public function notFoundAction() 
    { 
		// First check if an html page exists - if so, use that with the default controller
		$exists = false;
		foreach($this->view->getScriptPaths() as $script_path)
		{
			if (file_exists($script_path.$this->pagesFolder.$this->_request->getRequestUri().'.php'))
			{
				$exists = true;
				break;
			}
		}
		
		if ($exists)
		{
			$this->view->pageContent = $this->pagesFolder.$this->_request->getRequestUri().'.php';
		} else {
			$this->log->info("Page not found" . " : " . $this->_request->getRequestUri());
			print_r($this->_error);
			#$this->_redirect( '/' );
		}
        echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));	
    }

    public function serverErrorAction() 
    { 
    	$exception = $this->_error->exception;
        $this->log->debug($exception->getMessage() . "\n" .  $exception->getTraceAsString());
        $this->view->title = 'Error';
        $this->view->errorType = 'Server';
		print "<p>".$exception->getMessage() .  $exception->getTraceAsString()."</p>";
    }
	
	public function postDispatch() {
		exit;
	}

    public function checkAuthorisation()
    {
    }
}
