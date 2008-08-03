<?php

class Comment extends Zend_Db_Table_Abstract {
	
	protected $_name = "comments";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'User',
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
	
	public function getComments($recipe_id)
	{
		$select = $this->db->select()
				->from('comments')
				->join('users','comments.user_id = users.id',array('name','email'))
				->where('recipe_id = ?',$recipe_id)
				->order('comments.created','ASC');
				
		return ($this->db->fetchAll($select));
	}

}
