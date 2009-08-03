<?php

class Zend_View_Helper_CycleCss extends Zend_View_Helper_Abstract
{
	protected $_count = 0;
	
	public function cycleCss()
	{
		$this->_count++;
		if (($this->_count % 2) == 0)
			return 'even';
		else
			return 'odd';
	}

}