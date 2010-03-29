<?php

/**
* 
*/
class Models_DbTable_UserPreferences extends Zend_Db_Table_Abstract
{
	protected $_name = "user_preferences";
	protected $_primary = "id";
	
	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_referenceMap = array(
		'User' => array(
			'columns'       => 'user_id',
			'refTableClass' => 'Models_DbTable_User',
			'refColumns'	=> 'id'
		)
	);
		
}
