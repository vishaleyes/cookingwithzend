<?php

class Rating extends Zend_Db_Table_Abstract {
	
	protected $_name = "ratings";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'User',
			'refColumns'	=> 'id'
		),
		'Recipe' => array(
			'columns'       => 'recipe_id',
			'refTableClass' => 'Recipe',
			'refColumns'    => 'id'
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
	
	/* Add user_id to insert query. */
	
	public function insert($params)
	{
		$params['user_id'] = Zend_Registry::get('session')->user['id'];
		
		return parent::insert($params);
	}
	
	/* Calculate rating for recipe */
	
	public function getRating($recipe_id)
	{
		
		
		
		/*	Get average rating for recipe_ip	*/
		$select = $this->db->select()->from('ratings',array("rating" => "AVG(value)"))->where('recipe_id = ?',$recipe_id); //("SELECT AVG(value) FROM ratings WHERE recipe_id = $recipe_id");
		$avg = $this->db->fetchOne($select);

		/*	Round down to a sensible prescision, like 3.5	*/	
		return round($avg,1);
		
	}
	
	

}
