<?php

class UserController extends Recipe_Model_Controller  
{
	public function init()
	{
		parent::init();
		$this->_model = $this->getModel();
	}

	public function viewAction()
	{
		if ( ! $this->checkRequiredParams(array('id')) )
			$this->_redirect( '/recipe/index' );
			
		$userID = $this->_getParam('id');
		$user = $this->_model->getSingleByField('name', $userID);
		$this->view->title = 'Viewing user '.$user['name'];
		$this->view->user = $user;
		
		$c = new Recipe_Model_Comment();
		$this->view->comments = $c->getComments('u.name', $userID);
	}
	
	public function newAction()
	{
		$this->view->title = 'Create an account';
		$this->_form = $this->getForm('UserNew');

		if ($this->getRequest()->isPost()) {
			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($this->_form->isValid($this->_request->getPost())) {

				$values = $this->_form->getValues();
				$params = array(
					'name'       => $values['name'],
					'email'      => $values['email'],
					'password'   => new Zend_Db_Expr('MD5("'.$values['password'].'")')
				);
		
				try {
					$this->_model->table->insert( $params );
					$this->_flashMessenger->addMessage( 'Please check your email for a confirmation link' );
					$this->_log->debug( 'Inserted user ' . $values['email'] );
					$this->_model->sendConfirmationEmail($values['email'], $values['name']);
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

	public function editAction()
	{
		$this->view->title = 'Your account';
		
		$row = array();
		$row = $this->_model->getSingleByField('id', $this->_identity->id);
				
		if ( $row )
		{
			$this->view->user = $row;
		}
		
		$this->_form = $this->getForm('UserAccount');
		$this->_form->populate($this->view->user->toArray());
		$this->view->form = $this->_form;
	}	

}
