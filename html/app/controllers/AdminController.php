<?php

class AdminController extends DefaultController
{
	public function init()
	{
		parent::init();
		$this->model = new Models_AclResource();
	}
	
	public function resourcesAction()
	{
		$this->view->resources = $this->model->getResources();
		$form = $this->model->getForm('Resource'); 
	} 
	
	public function addResourceAction()
	{
		$form = $this->model->getForm('Resource');
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				

				$this->_log->info( 'Added Recipe ' . sq_brackets( $data['name'] ) ); 
				$this->_flashMessenger->addMessage( 'Added recipe ' . $data['name'] );
				$this->_redirect( '/recipe/new' );
			}
		}
	}
}
