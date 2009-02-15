<?php

/**
* 
*/
class Models_DbTable_Recipe extends Zend_Db_Table_Abstract
{
	protected $_name = "recipes";
	protected $_primary = "id";
	//	protected $_rowClass = "Models_RecipeRow";

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array(
		'Models_DbTable_RecipeIngredient', 
		'Models_DbTable_MethodItem', 
		'Models_DbTable_Rating'
	);

	protected $_referenceMap = array(
		'User' => array(
			'columns'       => 'creator_id',
			'refTableClass' => 'Models_DbTable_User',
			'refColumns'	=> 'id'
		)
	);
	
	public function insert( array $params )
	{
		$params['creator_id'] = Zend_Registry::get( 'session')->user['id'];
		$params['created'] = new Zend_Db_Expr('NOW()');
		$params['updated'] = new Zend_Db_Expr('NOW()');
		
		return parent::insert( $params );
	}

	public function update( array $params, $where )
	{
		$params['updated'] = new Zend_Db_Expr('NOW()');
		return parent::update( $params, $where );
	}
	
}
