<?php

class Models_DbTable_AclResource extends Zend_Db_Table_Abstract {
	
	protected $_name = "acl_resources";
	protected $_primary = "id";

	# Primary does Auto Inc
	protected $_sequence = true;
	
}
