<?php

class Models_DbTable_AclRole extends Zend_Db_Table_Abstract {
	
	protected $_name = "acl_roles";
	protected $_primary = "id";

	# Primary does Auto Inc
	protected $_sequence = true;
	
}
