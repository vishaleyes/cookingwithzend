<?php

class LoginController extends DefaultController
{
	public function predispatch()
	{
		// held in defaultcontroller
		// $this->loggedin( array( 'login', 'logout' ) );
	}

	public function loginAction() {

		$this->view->pageContent = $this->pagesFolder.'/user/login.phtml';
		$this->view->title = 'Login';
		
		// Dont need the username
		$this->form->removeElement( 'name' );
		$this->form->addElement( 'submit', 'Login' );

		if (! $this->form->isValid($_POST)) {
			$this->view->form = $this->form;
			echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
			exit;
		}
		
		$values = $this->form->getValues();

		$this->auth->setIdentity( $values['email'] )
		           ->setCredential( $values['password'] );

		$result = $this->auth->authenticate();
		
		if( $result->isValid() )
		{
			$u = new User();

			if ( $user = $u->getByEmail( $values['email'] ) ) {
				$user->last_login = new Zend_Db_Expr('NOW()');
				$user->save();
				$this->session->user = $user->toArray();
			}
			$this->log->info( 'User '.sq_brackets( $this->session->user['name'] ).' logged in' );
		} else {
			$this->log->info( 'User '.sq_brackets( $values['email'] ).' failed login'. var_export( $result, true ) );
		}
		
		// Redirect to what we were asking for in the fist place or /
		if ( ! empty( $this->session->referrer ) ) {
			$redirect = $this->session->referrer;
			$this->log->info( $redirect );
			unset( $this->session->referrer );
			$this->_redirect( $redirect );
		} else {
			$this->_redirect( '/' );
		}
	}

	public function openidAction()
	{
		//$values = $this->form->getValues();
		$consumer = new Zend_OpenId_Consumer();
		if (!$consumer->login('http://www.flickr.com/photos/catharsisjelly', '/login/openidcapture')) {
		    die("OpenID login failed.");
		}
	}

	public function openidcaptureAction()
	{
		$consumer = new Zend_OpenId_Consumer();
		if ($consumer->verify($_GET, $id)) {
	    	echo "VALID " . htmlspecialchars($id);
		} else {
		    echo "INVALID " . htmlspecialchars($id);
		}
	}

	/**
	 * Logout, nuke the user session
	 */
	
	public function logoutAction()
	{
		unset( $this->session->user );
		$this->_redirect( '/' );
	}
	
	public function postDispatch() {
		exit;
	}


}

