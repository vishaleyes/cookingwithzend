<?php

class IngredientController extends DefaultController  
{

	/**
	 * This happens before the page is dispatch
	 */

	public function preDispatch()
	{
		// Held in DefaultController
		$this->loggedIn();
	}

	private function ingredientForm()
	{
		$i = new Ingredient();
		$ri = new RecipeIngredient();
		$m = new Measurement();
		$form = new Zend_Form();
		$form->addElements( $i->getFormElements() );
		$form->addElements( $ri->getFormElements() );
		$form->addElements( $m->getFormElements() );
		$form->removeElement( 'measurement_abbr' );
		return $form;
	}
	
	/**
	 * Display the build a new ingredient page
	 */
	
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
	
	/**
	 * Put a new ingredient in the database
	 * @todo Pass form params back to /recipe/new if fail
	 */
	
	public function createAction()
	{
		$form = $this->ingredientForm();
		if (! $form->isValid($_POST)) {
			$this->log->info( 'Ingredient form is invalid '.var_export( $form->getMessages(), true ) );
			$this->_redirect( '/ingredient/new/recipe_id/'.$this->recipe->id );
		}
		
		$values = $form->getValues();
		$i = new Ingredient();
		$ri = new RecipeIngredient();
		$m = new Measurement();
		$this->db->beginTransaction();
		
		try {
			// Insert into Ingredient
			$row = $i->insert( array( 'name' => $values['ingredient_name'] ) );
			
			$measurement = null;
			if ( ! empty( $values['measurement_name'] ) )
				$measurement = $m->getByName( $values['measurement_name'] );
				
			if ( $measurement instanceof Zend_Db_Table_Row )
				$measurement = $measurement->id;
			
			// Insert into RecipeIngredient
			$ri->insert(
				array(
					'quantity'       => ($values['quantity'] > 0 ? $values['quantity'] : null),
					'amount'         => ($values['amount'] > 0 ? $values['amount'] : null),
					'recipe_id'      => $this->recipe->id,
					'measurement_id' => $measurement,
					'ingredient_id'  => $row->id
				)
			);

			$params = array(
				'ingredients_count' => ($this->recipe->ingredients_count + 1)
			);

			$r = $this->recipe->getTable();
			$where = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );
			$r->update( $params, $where );

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
	 * Displays the form with the current values in the database in it
	 */
	
	public function editAction()
	{
		$ingredientId = $this->_getParam( 'ingredient_id' );
		
		$this->view->title = 'Edit an ingredient';
		$this->view->pageContent = $this->pagesFolder.'/ingredient/new.phtml';
		$form = $this->ingredientForm();
		$form->setAction( '/ingredient/update/recipe_id/' . $this->recipe->id . '/ingredient_id/' . $ingredientId );
		$form->addElement( 'submit', 'Update' );

		$ri = new RecipeIngredient();
		$rowset = $ri->find( $this->recipe->id, $ingredientId );
	
		// Gather the current values
		if ( $rowset )
		{
			$row = $rowset->current();
			$form->getElement('quantity')->setValue( $row->quantity );
			$form->getElement('amount')->setValue( $row->amount );
			$form->getElement('measurement_name')->setValue( $row->findParentMeasurement()->name );
			$form->getElement('ingredient_name')->setValue( $row->findParentIngredient()->name );
		}
		$this->view->form = $form;

		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));

	}

	/**
	 * Updates the recipe_ingredient table
	 */

	public function updateAction()
	{
		$ingredientId = $this->_getParam( 'ingredient_id' );

		$form = $this->ingredientForm();
		if (! $form->isValid($_POST)) {
			$this->log->info( 'Ingredient form is invalid '.var_export( $form->getMessages(), true ) );
			$this->_redirect( '/ingredient/new/recipe_id/'.$this->recipe->id );
		}
		
		$values = $form->getValues();
		$i = new Ingredient();
		$ri = new RecipeIngredient();
		$m = new Measurement();

		$this->db->beginTransaction();
		try {
			$row = $i->insert( array( 'name' => $values['ingredient_name'] ) );
	
			if ( ! empty( $values['measurement_name'] ) )
				$measurement = $m->getByName( $values['measurement_name'] );
				
			if ( $measurement instanceof Zend_Db_Table_Row )
				$measurement = $measurement->id;

			$where[] = $this->db->quoteInto( 'recipe_id = ?', $this->recipe->id );
			$where[] = $this->db->quoteInto( 'ingredient_id = ?', $ingredientId );

			$params = array(
				'quantity'       => ($values['quantity'] > 0 ? $values['quantity'] : null),
				'amount'         => ($values['amount'] > 0 ? $values['amount'] : null),
				'recipe_id'      => $this->recipe->id,
				'measurement_id' => $measurement,
				'ingredient_id'  => $row->id
			);

			$this->db->update( 'recipe_ingredients', $params, $where );

			$r = $this->recipe->getTable();
			$where = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );
			$r->update( array(), $where );

			$this->db->commit();
		} catch (Exception $e) {
			$this->log->info( $e->getTraceAsString() );
			$this->db->rollBack();
		}

		$this->_redirect( '/recipe/view/recipe_id/' . $this->recipe->id );
	}

	/**
	 * Deletes the association between Ingredient and Recipe
	 */

	public function deleteAction()
	{
		$ingredientId = $this->_getParam( 'ingredient_id' );
	
		$where[] = $this->db->quoteInto( 'recipe_id = ?', $this->recipe->id );
		$where[] = $this->db->quoteInto( 'ingredient_id = ?', $ingredientId );

		$params = array(
			'ingredients_count' => ($this->recipe->ingredients_count - 1)
		);
		$r = $this->recipe->getTable();
		$rwhere = $r->getAdapter()->quoteInto( 'id = ?', $this->recipe->id );

		$this->db->beginTransaction();
		try {
			$this->db->delete( 'recipe_ingredients', $where );
			$this->log->info( var_export( $params, true ) );
			$r->update( $params, $rwhere );
			$this->db->commit();
			$this->message->addMessage( 'Deleted ingredient from '.$this->recipe->name );
		} catch (Exception $e) {
			$this->db->rollBack();
			$this->log->info( $e->getMessage() );
		}

		$this->_redirect( '/recipe/view/recipe_id/'.$this->recipe->id );
	}

	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}
	
}