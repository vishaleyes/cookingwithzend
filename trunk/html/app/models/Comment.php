<?php

class Comment extends Zend_Db_Table_Abstract {
	
	protected $_name = "comments";
	protected $_primary = "id";
	protected $_rowClass = 'CommentRow';

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'User',
			'refColumns'	=> 'id'
		),
		'Recipe' => array(
			'columns'		=> 'recipe_id',
			'refTableClass' => 'Recipe',
			'refColumns'	=> 'id'
		)
			
	);

	// May be able to delete this
	function __construct( $prefetch = true )
	{
		if ( ! $prefetch === false ) unset( $_rowClass );
		$this->db = Zend_Registry::get("db");
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
		$this->log = Zend_Registry::get('log');
		
		$this->_setup();
	}
	
	public function insert($params)
	{
		$params['user_id'] = Zend_Registry::get( 'session')->user['id'];
		$params['created'] = new Zend_Db_Expr('NOW()');
		
		return parent::insert($params);
	}
	
	public function getComments($recipe_id, $per_page = null, $offset = null)
	{
		$select = $this->db->select()
				->from( array( 'c' => 'comments') )
				->join( 'users', 'c.user_id = users.id', array('name','email') )
				->join( 'recipes', 'recipes.id = c.recipe_id', 'creator_id' )
				->joinLeft( 
					array( 'r' => 'ratings' ), 
					'c.recipe_id = r.recipe_id AND c.user_id = r.user_id',
					'value'
				)
				->where('c.recipe_id = ?',$recipe_id)
				->order('c.created','ASC');

		if ( isset( $per_page ) ) {
			$offset = ( isset( $offset ) ? $offset : 0 );
			$select->limit( $per_page, $offset );
		}

		return ($this->db->fetchAll($select));
	}
	
	public function getCommentForm()
	{
		/* Submit comment form */

		$submitCommentForm = new Zend_Form();
		$submitCommentForm->setAction('/comment/add/recipe_id/' . $this->recipe->id)
										->setMethod('post')
										->setAttrib('name','submit_comment')
										->setAttrib('id','submit-comment');
										
		$commentTextarea = new Zend_Form_Element_Textarea('comment_value');
		$stripTags = new Zend_Filter_StripTags();
		$stripTags->setTagsAllowed( array( 'p', 'a', 'img', 'strong', 'b', 'i', 'em', 's', 'del' ) );
		$stripTags->setAttributesAllowed( array( 'href', 'target', 'rel', 'name', 'src', 'width', 'height', 'alt', 'title' ) );

		$commentTextarea->setLabel('Your comment: ')
						->setAttrib('cols','60')
						->setAttrib('rows','5')
						->setAttrib('class','fck')
						->setRequired( true )
						->addFilter($stripTags)
						->addValidator( new Zend_Validate_NotEmpty(), true );
		
		$submitCommentForm->addElement($commentTextarea);
		
		$submitButton = new Zend_Form_Element_Submit('submit_comment');
		$submitButton->setLabel('Submit comment');
		
		$submitCommentForm->addElement($submitButton);
		
		return $submitCommentForm;
	}

}
