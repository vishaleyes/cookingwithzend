<?php

class Rating extends Models_GenericModel {
	
	/*	Definite max rating allowed */
	const MAX_RATING = 5;
	
	/* Calculate rating for recipe */
	
	public function getRating($recipe_id)
	{
		
		/*	Get average rating for recipe_ip	*/
		$select = $this->db->select()->from('ratings',array("rating" => "AVG(value)"))->where('recipe_id = ?',$recipe_id);
		$avg = $this->db->fetchOne($select);

		/*	Round down to a sensible prescision, like 3.5	*/	
		return round($avg,1);
		
	}
	
	/**
	 * Check if anyone has rated a recipe already 
	 * 
     * @depreciated we can use recipe['ratings_count']
	 */
	
	public function isRated($recipe_id)
	{
		$select = $this->db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipe_id);
		
		if ($this->db->fetchOne($select) > 0)
			return true;
		else
			return false;
	}
	
	public function getRatingForm()
	{
		/*	Submit rating form	*/
		$submitRatingForm = new Zend_Form();
		$submitRatingForm->setAction('/rating/add/recipe_id/' . $this->recipe->id)
     									->setMethod('post')
     									->setAttrib('name','submit_rating')
     									->setAttrib('id','submit-rating')
     									->addElement('select','rating_value');
     	
     	for ($i = 1;$i <= Rating::MAX_RATING;$i++)
     	{
     		$submitRatingForm->rating_value->addMultiOption($i,$i . " out of " . Rating::MAX_RATING);
     	}
     	
     	$submitButton = new Zend_Form_Element_Submit('submit','submit_rating');
     	$submitButton->setLabel('Submit your rating');
     	$submitRatingForm->addElement($submitButton);
		
		return $submitRatingForm;
	}

}
