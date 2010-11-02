<?php

class Recipe_Model_Recipe extends Recipe_Model_GenericModel implements Zend_Acl_Resource_Interface
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
		if ($id !== null){
			$this->getRecipe($id);
		}
	}
	
	/**
	 * Fetches a single Recipe from the database
	 * 
	 * @param unknown_type $recipe_id
	 * @return unknown
	 */
	
	public function getRecipe($recipe_id)
	{
		$select = $this->getRecipesSelect();
		$select->where('r.id = ?', $recipe_id);
		$row = $this->db->fetchRow($select);
		$this->_dataMerge( $row );
	}
	
	/**
	 * Return all the recipies
	 *
	 * @param string $userID
	 * @param string $order Name of the column you want to order by
	 * @param string $direction ASC|DESC
	 * @return array
	 */
	public function getRecipes($userID = null, $order = null, $direction = 'ASC')
	{
		$select = $this->getRecipesSelect($userID, $order, $direction);
			
		$stmt = $this->db->query($select);
		$rowSet = $stmt->fetchAll();
		return $rowSet;
	}
	
	/**
	 * Return a select query for all the recipies with a username thrown in
	 *
	 * @param string $userID
	 * @param string $order Name of the column you want to order by
	 * @param string $direction ASC|DESC
	 * @return Zend_Db_Select
	 */
	public function getRecipesSelect($userID = null, $order = null, $direction = 'ASC')
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
			
		return $select;
	}
	
	/**
	 * Rebuilds the ingredient from the joining tables
	 *
	 * @see Recipe_Model_Ingredient
	 * @param int $id
	 * @return array
	 */
	
	public function getIngredients( $recipe_id )
	{
		$i = new Recipe_Model_Ingredient();
		return $i->getIngredients($recipe_id);
	}
	
	/**
	 * Retrieve the methods for a recipe
	 *
	 * @see Recipe_Model_Method
	 */
	
	public function getMethods( $recipe_id )
	{
		$m = new Recipe_Model_Method();
		return $m->getMethods($recipe_id);
	}
	
}
