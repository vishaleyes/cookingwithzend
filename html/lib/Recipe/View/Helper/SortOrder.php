<?php

/**
 * A view helper that displays a link for sorting the column
 * Used for sorting a table of information
 */
class Recipe_View_Helper_SortOrder extends Zend_View_Helper_Abstract
{
    
    /**
     * Return the html code to have the header of the column sortable
     * @param $field_name : name of the field on which order by must be effected
     * @param $display_name : name to display in the header of the column
     * @param $search_criteria : search criteria
     * @return string : the html code to put in the header
     */
	public function sortOrder($field_name, $display_name, $search_criteria)
 	{
 		if($field_name === $search_criteria->getFieldName()) 
    	{
    		switch($search_criteria->getDirection())
    		{
	    		case Recipe_SearchCriteria::DIRECTION_ASC:
    				$direction = Recipe_SearchCriteria::DIRECTION_DESC;
    				$arrow = '<img src="/images/icons/arrow_up.png" alt="sort-asc"/>';
    				break;
    			case Recipe_SearchCriteria::DIRECTION_DESC:
	    			$direction = Recipe_SearchCriteria::DIRECTION_ASC;
    				$arrow = '<img src="/images/icons/arrow_down.png" alt="sort-desc"/>';
    				break;
    		}
    	} else {
    		$direction = Recipe_SearchCriteria::DIRECTION_ASC;
    		$arrow = null;
    	}
    	
       	$urlHelper = $this->view->getHelper('Url');
		$output = '<a href="';
		$output .= $urlHelper->url(array('order' => $field_name, 'direction' => $direction));
		$output .= '">'. $display_name . '</a>' . $arrow;

    	return $output;
    }

    /**
     * Sets up the view
     * @param Zend_View_Interface $view
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}

?>