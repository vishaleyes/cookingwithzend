<?php

/**
 * Uses a Jquery plugin to provide a nice user interface to rate things
 * Requires http://www.fyneworks.com/jquery/star-rating/
 * @param $itemId int
 * @return $output string HTML output to be echo'ed to browser
 */

class Zend_View_Helper_StarRating
{

	public function starRating( $recipeId, $value = null )
	{
		$session = Zend_Registry::get('session');
		$log = Zend_Registry::get('log');
	
		// fetch the rating
		$ra = new Rating();
		$rating = $ra->getRating( $recipeId );

		// Logged in?
		if ( ! $session->user )
			return $this->displayRating( $rating );

		// If were passing through a value we already know what to display and are probably read only
		if ( isset( $value ) )
			return $this->displayRating( $value );

		//$log->debug( $session->user['name'] . ' is logged in' );

		$r = new Recipe();
		if ( $r->isOwner( $recipeId ) )
			return $this->displayRating( $rating );
		
		//$log->debug( $session->user['name'] . ' is not the owner' );

		// Has this user already rated?
		$db = Zend_Registry::get('db');

		$select = $db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipeId)
		  ->where("user_id = ?", $session->user['id'] );

		//$log->debug( $select->__toString() );
		//$log->debug( $db->fetchOne( $select ) );

		// If we get a result show the user the overall rating
		if ( $db->fetchOne($select) > 0)
			return $this->displayRating( $rating );

		//$log->debug( $session->user['name'] . ' has not already rated this' );

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
