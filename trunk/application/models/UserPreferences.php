<?php

/**
 * Recipe_Model_UserPreferences an object to store the user preferences
 * either grabbed from the DB or held in the session for the user
 *
 */

class Recipe_Model_UserPreferences extends Recipe_Model_GenericModel
{

	/**
	 * A base set of preferences
	 * 
	 * @var array
	 */
	private $_PREF_BASE = array(
		'RecipesPerPage' => 5,
		'ItemsPerList' => 25
	);
	
	/**
	 * Store the current Prefs here
	 * @var unknown_type
	 */
	
	private $_data = array();
	
	/**
	 * 
	 * @param int $id
	 */
	
	public function __construct($id = null)
	{
		parent::__construct();
		// Set the current data to be the pref data
		$this->_data = $this->_PREF_BASE;
		if ($id !== null)
			$this->getDbPreferences($id);
	}
	
	/**
	 * Retrieve the Preferences from the Db
	 * 
	 * @param int $userID
	 */
	
	public function getDbPreferences($userID)
	{
		$select = $this->db->select()
			->from('user_preferences', array('preference', 'value'))
			->where('user_id = ?', $userID);
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
		if ( $result = $this->db->fetchPairs($select) )
			// Merge the data so I only have to update this file to add new prefs
			$this->_data = array_merge($this->_PREF_BASE, $result);
	}
	
	/**
	 * Retrieve the preference from the object
	 * 
	 * @param string $string
	 */
	
	public function getPreference($string)
	{
		if ( array_key_exists($string, $this->_data))
			return $this->_data[$string];
		return false;	
	}

	/* MAGIC METHODS */
	
	/**
	 * Used by serialize, we only really need the data
	 * @return array
	 */
	
	public function __sleep()
	{
		return array('_data');
	}
}
