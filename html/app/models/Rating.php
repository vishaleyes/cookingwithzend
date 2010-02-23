<?php

class Models_Rating extends Models_GenericModel
{

	public function getRatings($recipe_id, $max = 5)
	{
		$select = $this->db->select()
			->from(array('r' => 'ratings'))
			->joinLeft(array('u' => 'users'), 'r.user_id = u.id', array(
				'username' => 'u.name'
			))
			->where('recipe_id = ?', $recipe_id)
			->limit($max);
		return $this->db->fetchAll($select);
	}
	
	/**
	 * Calculate rating for recipe
	 * 
	 * @param int $recipe_id
	 * @return int
	 */
	
	public function getAvgRating($recipe_id)
	{	
		/*	Get average rating for recipe_ip	*/
		$select = $this->db->select()->from('ratings',array("rating" => "AVG(value)"))->where('recipe_id = ?',$recipe_id);
		$avg = $this->db->fetchOne($select);

		/*	Round down to a sensible prescision, like 3.5	*/	
		return round($avg,1);
	}
	
	/**
	 * Check if this user has rated this recipe already 
	 * 
	 * @param int $recipe_id
	 * @param int $user_id
	 * @return bool
	 */
	
	public function hasRated($recipe_id, $user_id)
	{
		$select = $this->db->select()
		  ->from('ratings',array("numberOfRatings" => "COUNT(*)"))
		  ->where("recipe_id = ?", $recipe_id)
		  ->where("user_id = ?", $user_id);
		
		if ($this->db->fetchOne($select) > 0)
			return true;
		
		return false;
	}
	
}
