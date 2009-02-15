<?php

class Models_RecipeRow extends Zend_Db_Table_Row_Abstract {

	public function __construct(array $config = array())
	{
		parent::__construct($config);
		$this->getUser();
		$this->getRating();
		$this->getTags();
	}

	public function getIngredients()
	{
		$output = array();
		$ingredients = $this->findRecipeIngredient();
		foreach( $ingredients as $ingredient ) {
			$tmp = $ingredient->toArray();
			$tmp['name'] = $ingredient->findParentIngredient()->name;
			$output[] = $tmp;
		}

		return $output;
	}

	public function getUser()
	{
		$this->_data['user'] = $this->findParentUser();
	}

	public function getRating()
	{
		$r = new Rating();
		$this->_data['rating'] = $r->getRating( $this->id );
	}

	public function getTags()
	{
		$tag = new Tag();
		$this->_data['tags'] = $tag->getTags( $this );
	}

}
