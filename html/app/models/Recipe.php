<?php

class Models_Recipe extends Models_GenericModel implements Zend_Acl_Resource_Interface
{
	protected $_ownerUserId = null;
	
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
		$stmt = $this->db->query($select);
		$row = $stmt->fetchRow();
		$this->_ownerUserId = $row['user_id'];
		return $row;
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
	 * @param int $id
	 * @return array
	 */
	
	public function getIngredients( $recipe_id )
	{
		$select = $this->db->select()
			->from(array('ri' => 'recipe_ingredients'))
			->joinLeft(
				array('i' => 'ingredients'), 
				'ri.ingredient_id = i.id', 
				array( 'name' => 'i.name')
			)
			->joinLeft(
				array('m' => 'measurements'),
				'ri.measurement_id = m.id',
				array( 'measurement' => 'm.abbreviation')
			)
			->where('ri.recipe_id = ?', $recipe_id);
		return $this->db->fetchAll($select);
	}
	
	/**
	 * Retrieve the methods for a recipe
	 *
	 * @param int $id
	 * @return array
	 */
	
	public function getMethods( $recipe_id )
	{
		$select = $this->db->select()
			->from(array('m' => 'method_items'))
			->where('m.recipe_id = ?', $recipe_id)
			->order('position')
			->order('id');
			
		return $this->db->fetchAll($select);
	}
	
}
