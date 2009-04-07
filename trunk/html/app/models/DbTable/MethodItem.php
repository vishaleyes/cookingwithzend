<?php

/**
* 
*/
class Models_DbTable_MethodItem extends Zend_Db_Table_Abstract
{
	protected $_name = "method_items";
	protected $_primary = "id";

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'Recipe' => array(
			'columns'		=> 'recipe_id',
			'refTableClass' => 'Models_DbTable_Recipe',
			'refColumns'	=> 'id'
		),
	);
	
}
