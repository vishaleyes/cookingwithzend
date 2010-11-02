<?php

class AdminController extends Recipe_Model_Controller
{
	public function init()
	{
		parent::init();
		$this->model = new Recipe_Model_AclResource();
	}
	
	public function indexAction()
	{
	}
	
	public function resourcesAction()
	{
		$this->view->resources = $this->model->getResources(); 
	} 
	
	public function addResourceAction()
	{
		$this->view->title = 'Create a resource';
		$form = $this->model->getForm('Resource');
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {

			// now check to see if the form submitted exists, and
			// if the values passed in are valid for this form
			if ($form->isValid($this->_request->getPost())) {
				// Get the values from the DB
				$data = $form->getValues();

				// Unset the buttons
				unset( $data['submit'] );
				$this->model->table->insert($data);
			}
		}
	}
}
