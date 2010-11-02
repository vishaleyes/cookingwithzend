<?php

class Recipe_Model_DbTable_Measurement extends Zend_Db_Table_Abstract
{
	protected $_name = "measurements";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;
}
