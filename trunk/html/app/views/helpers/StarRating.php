<?php

class Zend_View_Helper_StarRating
{

	public function starRating( $recipeId )
	{
		$session = Zend_Registry::get('session');
	
		// fetch the rating
		$ra = new Rating();
		$rating = $ra->getRating( $recipeId );

		// Logged in?
		if ( ! $session->user )
			return $this->displayRating( $rating );

		$r = new Recipe();
		if ( $r->isOwner( $recipeId ) )
			return $this->displayRating( $rating );

		// Has this user already rated?
		$db = Zend_Registry::get('db');

		$select = $db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipeId)
		  ->where("user_id = ?", $session->user['id'] );

		// If we get a result show the user the overall rating
		if (count($db->fetchOne($select)) > 0)
			return $this->displayRating( $rating );

		// Otherwise show the rating but make it clickable
		return $this->displayRating( $rating, false );		
	}

	public function displayRating( $number, $readOnly = true )
	{
		$output = '';

		for( $i = 1; $i <= Rating::MAX_RATING; $i++ ) {
			$output .= '<input name="rating_star" type="radio" class="auto-submit-star"';
			$output .= ' value="'.$i.'"';
			if ( $readOnly === true )
				$output .= ' disabled="disabled"';

			if ( $number == $i )
				$output .= ' checked="checked"';

			$output .= '/>';
		}

		return $output;
	}

}
