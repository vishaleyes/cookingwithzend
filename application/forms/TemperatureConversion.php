<?php

class Recipe_Form_TemperatureConversion extends Zend_Form
{

	public function init()
	{
		$this->addElement('text', 'temperature', array(
			'label'    => 'Temperature:',
			'onChange' => 'convertTemp()',
		))->addElement('select', 'convert', array(
			'label'    => 'Convert To:',
			'onChange' => 'convertTemp()',
			'multiOptions' => array('C' => 'C', 'F' => 'F')
		));
	}
}