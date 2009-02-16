<?php

/**
	* 
	*/
class Forms_RecipeNew extends Zend_Form
{

	public function init()
	{
		// set the method for the display form to POST
		$this->setMethod('post');

		// add an email element
		$this->addElement('text', 'name', array(
			'label'      => 'Name:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength( array( 3, 255 ) )
			)
		));
		
		$this->addElement( 'text', 'cooking_time', array(
			'label'      => 'Cooking Time (in minutes):',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Int()
			)
		));

		$e = new Zend_Form_Element_Text( 'preparation_time' );
		$e->setLabel( 'Preparation Time (in minutes)' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'serves' );
		$e->setLabel( 'Serves' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() )
		  ->addValidator( new Zend_Validate_GreaterThan(0) );
		$elements[] = $e;
		
		$this->addElement('text', 'difficulty', array(
			'label'      => 'Difficulty',
			'required'   => true,
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Int(),
				new Zend_Validate_Between( 1, 10 )
			)
		))

		$this->addElement('checkbox', 'freezable' array(
			'label' => 'Freezable'
		));

		// add the comment element
		$this->addElement('textarea', 'comment', array(
			'label'      => 'Please Comment:',
			'required'   => true,
		'validators' => array(
			array('validator' => 'StringLength', 'options' => array(0, 20))
			)
			));

		$this->addElement('captcha', 'captcha', array(
			'label'      => 'Please enter the 5 letters displayed below:',
			'required'   => true,
			'captcha'    => array('captcha' => 'Figlet', 'wordLen' => 5, 'timeout' => 300)
			));

		// add the submit button
		$this->addElement('submit', 'submit', array(
			'label'    => 'Sign Guestbook',
			));
		}
	}
