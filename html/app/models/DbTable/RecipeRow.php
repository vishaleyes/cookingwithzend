<?php

class Models_DbTable_RecipeRow extends Zend_Db_Table_Row_Abstract {

	public function __construct(array $config = array())
	{
		parent::__construct($config);
		$this->getUser();
	}

	public function getUser()
	{
		$user = $this->findParentModels_DbTable_User()->name;
		$this->_data['user'] = $user;
	}

}
