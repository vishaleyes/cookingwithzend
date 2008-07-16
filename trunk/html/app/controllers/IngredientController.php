<?php

class IngredientController extends DefaultController  
{

	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn();
	}

	private function ingredientForm()
	{
		$i = new Ingredient();
		$ri = new RecipeIngredient();
		$form = new Zend_Form();
		$form->addElements( $i->_form_fields_config );
		$form->addElements( $ri->_form_fields_config );
		return $form;
	}
	
	public function newAction()
	{
		$this->view->title = 'Add an ingredient';
		$this->view->pageContent = $this->pagesFolder.'/ingredient/new.phtml';
		
		$form = $this->ingredientForm();
		$form->setAction( '/ingredient/create/recipe_id/' . $this->recipe->id );
		$form->addElement( 'submit', 'Add' );
		$this->view->form = $form;
		
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
	}
	
	public function createAction()
	{
		$this->view->title = 'Add an ingredient';
		$this->view->pageContent = $this->pagesFolder.'/ingredient/new.phtml';
		
		$form = $this->ingredientForm();
		if (! $form->isValid($_POST)) {
			$this->_redirect( '/ingredient/new' );
		}
		
		$values = $form->getValues();
		$i = new Ingredient();
		$ri = new RecipeIngredient();
		$this->db->beginTransaction();
		
		try {
			// Insert into Ingredient
			$row = $i->insert( array( 'name' => $values['ingredient_name'] ) );
			
			// Insert into RecipeIngredient
			$ri->insert(
				array(
					'quantity' => $values['quantity'],
					'amount' => $values['amount'],
					'recipe_id' => $this->recipe->id,
					'ingredient_id' => $row->id
				)
			);

			$this->recipe->ingredients_count++;
			$this->recipe->save();

			$this->db->commit();
			$this->log->info( 'Added Ingredient ' . sq_brackets( $values['ingredient_name'] ) . ' to RecipeID ' . sq_brackets( $this->recipe->id ) ); 
			// All is well send us to the ingredient list for this recipe
			$this->_redirect( '/recipe/view/recipe_id/' . $this->recipe->id );
		} catch (Exception $e) {
			$this->log->info( $e->getMessage() );
			$this->db->rollBack();
			// Something went boom
			$this->_redirect( '/ingredient/create/recipe_id/' . $this->recipe->id );
		}

	}

	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}
	
}
