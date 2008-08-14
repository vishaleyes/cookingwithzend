<?php

/**
 * Helper to tell us if the user has already rated this recipe or not
 */

class Zend_View_Helper_CheckUserRated
{

	/**
	 * Returns true if the user has already submitted a rating for this recipe
	 * @param $recipeId int
	 * @return bool
	 */

	public function checkUserRated( $recipeId )
	{
		$session = Zend_Registry::get('session');
		$db = Zend_Registry::get('db');
	
		$select = $db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipeId)
		  ->where("user_id = ?", $session->user['id'] );

		if ($db->fetchOne($select) > 0)
			return true;

		return false;
	}

}
