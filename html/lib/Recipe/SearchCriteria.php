<?php

class Recipe_SearchCriteria 
{
	
	// Constant that represent the direction of the sort
	CONST DIRECTION_ASC = 'ASC';
	CONST DIRECTION_DESC = 'DESC';
	
	// Sort number page
	private $_data;
	
	// Filters on field i.e. : array('my_field_name' => 'my_filter_value')
	private $filter_fields = array();
	
	/**
	 * Construct a criteria that represent the sort page of a module
	 * @param $page_number: the number of the page
	 * @param $field_name: 
	 * @param $direction
	 */
	public function __construct($page_number, $field_name, $direction)
	{
		$this->_data['page_number'] = $page_number;
		$this->_data['field_name'] = $field_name;
		$this->_data['direction'] = $direction;
	}
	
	/**
	 * Set the sort page number
	 * @return void
	 */
	public function setPageNumber($page) 
	{
		$this->_data['page_number'] = $page;
	}
	
	/**
	 * Set the sort field name
	 * @return void
	 */
	public function setFieldName($field) 
	{
		$this->_data['field_name'] = $field;
	}
	
	/**
	 * Set the sort column direction
	 * @return void
	 */
	public function setDirection($direction) 
	{
		$this->_data['direction'] = $direction;
	}
	
	/**
	 * Get the sort page number
	 * @return int: the number of the page
	 */
	public function getPageNumber() 
	{
		return $this->_data['page_number'];
	}
	
	/**
	 * Get the name of the sort field
	 * @return string: the name of the field
	 */
	public function getFieldName()
	{
		return $this->_data['field_name'];
	}
	
	/**
	 * Get the direction sort
	 * @return string: the current sort
	 */
	public function getDirection()
	{
		return $this->_data['direction'];
	}
	
	/**
	 * Set the filter on a field
	 * @param $field_name: name of the field to apply the filter
	 * @param $field_value: value of the filter
	 * @return void
	 */
	public function setField($field_name, $field_value)
	{
		$this->filter_fields[$field_name] = $field_value;
	}
	
	/**
	 * Get the filter on a field
	 * @param $field_name: name of the field on which 
	 * @return the value of the field or null if not exist
	 */
	public function getField($field_name)
	{
		if(key_exists($field_name, $this->filter_fields)) 
		{
			return $this->filter_fields[$field_name];
		} else {
			return null;
		}
	}
}
?>