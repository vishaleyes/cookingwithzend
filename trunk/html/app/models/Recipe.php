<?php

class Models_Recipe extends Models_GenericModel implements Zend_Acl_Resource_Interface
{
	var $ownerUserId = null;
	
	protected $_data = array();
	
	/**
	 * getResourceId returns the resourceID of the model 
	 * @see Zend_Acl_Resource_Interface 
	 */
	
	public function getResourceId()
	{
		return 'recipe';
	}
	
	/**
	 * @param int $id
	 */
	
	public function __construct($id = null)
	{
		parent::__construct();
		if ($id !== null)
			$this->getRecipe($id);
	}
	
	/**
	 * Fetches a single Recipe from the database
	 * 
	 * @param unknown_type $recipe_id
	 * @return unknown
	 */
	
	public function getRecipe($recipe_id)
	{
		$select = $this->db->select()
			->from(array('r' => 'recipes'))
			->joinLeft(array('u' => 'users'), 'r.user_id = u.id', array(
				'username' => 'u.name'
			))
			->where('r.id = ?', $recipe_id);
		$row = $this->db->fetchRow($select);
		$this->_dataMerge( $row );
	}
	
	/**
	 * Return all the recipies with a username thrown in
	 *
	 * @param string $userID
	 * @param string $order
	 * @return array
	 */
	public function getRecipes($userID = null, $order = null, $direction = 'ASC')
	{
		$select = $this->db->select()
			->from(array('r' => 'recipes'))
			->joinLeft(array('u' => 'users'), 'r.user_id = u.id', array(
				'username' => 'u.name'
			));
		
		if (null !== $userID)
			$select->where('u.name = ?', $userID);
		
		if (null !== $order)
		{
			$select->order("r.$order $direction");
			$select->order("r.name $direction");
		}
		else
			$select->order("r.name");
			
		$stmt = $this->db->query($select);
		$rowSet = $stmt->fetchAll();
		return $rowSet;
	}
	
	/**
	 * Rebuilds the ingredient from the joining tables
	 *
	 * @see Models_Ingredient
	 * @param int $id
	 * @return array
	 */
	
	public function getIngredients( $recipe_id )
	{
		$i = new Models_Ingredient();
		return $i->getIngredients($recipe_id);
	}
	
	/**
	 * Retrieve the methods for a recipe
	 *
	 * @see Models_Method
	 */
	
	public function getMethods( $recipe_id )
	{
		$m = new Models_Method();
		return $m->getMethods($recipe_id);
	}
	
}
