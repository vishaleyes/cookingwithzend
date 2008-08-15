<?php

class Zend_View_Helper_StarRating
{

	public function starRating( $number )
	{
		$session = Zend_Registry::get('session');
		
		// Logged in?
		if ( ! $session->user )
			return $this->displayRating( $number );

		// Has this user already rated?
		$db = Zend_Registry::get('db');

		$select = $db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipeId)
		  ->where("user_id = ?", $session->user['id'] );

		if ($db->fetchOne($select) > 0)
			return $this->displayRating( $number );

		return $this->displayRating( $number, false );		
	}

	public function displayRating( $number, $readOnly = true )
	{
		$output = '';

		for( $i = 1; $i <= Rating::MAX_RATING; $i++ ) {
			$output .= '<input name="rating_star" type="radio" class="star"';
			if ( $readOnly === true )
				$output .= ' disabled="disabled"';

			if ( $number == $i )
				$output .= ' checked="checked"';

			$output .= '/>';
		}

		return $output;
	}

}
