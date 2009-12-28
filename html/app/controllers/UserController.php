<?php

class UserController extends DefaultController  
{
	public function init()
	{
		parent::init();
		$this->model = $this->getModel();
	}

	public function viewAction()
	{
		if ( ! $this->checkRequiredParams(array('id')) )
			$this->_redirect( '/recipe/index' );
			
		$userID = $this->_getParam('id');
		$user = $this->model->getSingleByField('name', $userID);
		$this->view->title = 'Viewing user '.$user['name'];
		$this->view->user = $user;
		
		$c = new Models_Comment();
		$this->view->comments = $c->getComments('u.name', $userID);
	}
	
	public function newAction()
	{
		$this->view->title = 'Create an account';
		$form = $this->model->getForm('UserNew');
		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {
			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {

				$values = $form->getValues();
				$params = array(
					'name'       => $values['name'],
					'email'      => $values['email'],
					'password'   => new Zend_Db_Expr('MD5("'.$values['password'].'")')
				);
		
				try {
					$this->model->table->insert( $params );
					$this->_flashMessenger->addMessage( 'Please check your email for a confirmation link' );
					$this->_log->debug( 'Inserted user ' . $values['email'] );
					$this->model->sendConfirmationEmail($values['email'], $values['name']);
					$this->_redirect( '/' );
				} catch(Exception $e) {
					$this->_flashMessenger->setNamespace( 'error' );
					$this->_flashMessenger->addMessage( $e->getMessage() );
					$this->_flashMessenger->resetNamespace();
					$this->_log->debug( 'Failed to insert user ' .$values['email'] . ' : ' . var_export( $e, true ) );
					//$this->db->rollback();
					$this->_redirect( '/user/new' );
				}
			}
		}
	}

	/**
	 * Display the users acccount to them
	 */

	public function accountAction()
	{
		$this->view->title = 'Your account';
		
		$row = $this->model->getSingleByField('id', $this->_identity['id']);
				
		if ( $row )
		{
			$this->view->user = $row;
		}
		
		$form = $this->model->getForm('UserAccount');
		$form->populate($row);
		$this->view->form = $form;
	}
	
	/*
	 * Executes sendConfirmationEmail function. Used for testing, can be deleted or modified.
	 */
	public function sendConfirmationAction()
	{
		$this->_helper->layout->disableLayout();
		
		$rowset = $this->model->getByField( 'id', $this->_getParam('id') );
		$emailResult = $this->model->sendConfirmationEmail($rowset['email'], $rowset['name']);
		
		if ($emailResult)
		{
			$this->message->addMessage( 'Confirmation e-mail sent.' );
			$this->_redirect('/');
		}
	}
		
	/**
	 * Allows user to confirm e-mail address.
	 */
	
	public function confirmAction()
	{
		$code = $this->_getParam("code");
		$userId = substr( $code, 32, strlen( $code ) - 32 );
		var_dump($this->_getAllParams());
		print($userId);
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		/* $user = $this->model->getByField( 'id', $this->session->user['id'] );
			
		if ( $code === $user->getVerificationCode() )
		{
			// Code matches, update DB
			$this->db->update( "users", array( "status" => "active" ), "id = $userId" );
			$this->message->addMessage( 'You have now confirmed your account.' );
			$this->_redirect('/');
		} else {
			// Code doesn't match, bitch at user.
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'Incorrect confirmation code supplied.' );
			$this->message->resetNamespace();
			$this->_redirect('/');
		} */
		
	}		

}
