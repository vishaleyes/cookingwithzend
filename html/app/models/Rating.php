<?php

class Rating extends Zend_Db_Table_Abstract {
	
	protected $_name = "ratings";
	protected $_primary = array( "recipe_id", "user_id" );

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
	
	/*	Definite max rating allowed */
	const MAX_RATING = 5;

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
		$select = $this->db->select()->from('ratings',array("rating" => "AVG(value)"))->where('recipe_id = ?',$recipe_id);
		$avg = $this->db->fetchOne($select);

		/*	Round down to a sensible prescision, like 3.5	*/	
		return round($avg,1);
		
	}
	
	/* Check if current user has rated a recipe already */
	
	public function checkIfUserHasRated($recipe_id)
	{
		// Multiple WHERE clauses = AND, use ?'s and pass the parameter as a variable this way Zend quotes it for you - CL
		$select = $this->db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipe_id)
		  ->where("user_id = ?", Zend_Registry::get('session')->user['id'] );

		if ($this->db->fetchOne($select) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	/* Check if anyone has rated a recipe already */
	
	public function isRated($recipe_id)
	{
		$select = $this->db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipe_id);
		
		if ($this->db->fetchOne($select) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
			
	}

}
