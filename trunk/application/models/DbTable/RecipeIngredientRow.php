<?php
class RecipeIngredientRow extends Zend_Db_Table_Row_Abstract
{
	public function __construct(array $config = array())
	{
		parent::__construct($config);
		$this->fetchIngredient();
		$this->fetchMeasurement();
	}

	private function fetchIngredient()
	{
		$this->_data['name'] = $this->findParentIngredient()->name;
	}

	private function fetchMeasurement()
	{
		if ( !empty( $this->measurement_id ) ) {
			$this->_data['measurement'] = $this->findParentMeasurement()->name;
			$this->_data['measurement_abbr'] = $this->findParentMeasurement()->abbreviation;
		}
	}

}