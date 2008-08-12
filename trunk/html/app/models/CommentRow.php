<?php

class CommentRow extends Zend_Db_Table_Row_Abstract {

	public function delete()
	{
		$recipe = $this->findParentRecipe();
		$recipe->comments_count--;
		$recipe->save();
		parent::delete();
	}

}
