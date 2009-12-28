<?php

class Forms_Rating extends Zend_Form
{

	public function init()
	{		
		$stripTags = new Zend_Filter_StripTags();
		$stripTags->setTagsAllowed( array( 'p', 'a', 'img', 'strong', 'b', 'i', 'em', 's', 'del' ) );
		$stripTags->setAttributesAllowed( array( 'href', 'target', 'rel', 'name', 'src', 'width', 'height', 'alt', 'title' ) );
		
		$this->setAction('/rating/new');
		$this->addElement('select', 'rating', array(
			'label' => 'Rating:',
			'required'   => true,
			'multiOptions' => array(1,2,3,4,5,6,7,8,9,10)
		))->addElement('textarea', 'comment', array(
			'class'      => 'richedit',
			'label'      => 'Comment:',
			'filters'    => array('StringTrim', $stripTags),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
			)
		))
		->addElement('hidden', 'recipe_id')
		->addElement('submit', 'submit');
	}
}