<?php

class Forms_Comment extends Zend_Form
{

	public function init()
	{
		$stripTags = new Zend_Filter_StripTags();
		$stripTags->setTagsAllowed( array( 'p', 'a', 'img', 'strong', 'b', 'i', 'em', 's', 'del' ) );
		$stripTags->setAttributesAllowed( array( 'href', 'target', 'rel', 'name', 'src', 'width', 'height', 'alt', 'title' ) );	
		
		$this->setAction('/comment/new');
		$this->addElement('textarea', 'comment', array(
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