<?php

class Models_Method extends Models_GenericModel implements Zend_Acl_Resource_Interface 
{
	protected $_ownerUserId = null;
	
	public function getResourceId()
	{
		return 'method';
	}
}
