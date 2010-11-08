<?php

class Model_RecipeTest extends ControllerTestCase
{
	protected $_model;

	public function setUp()
	{
		parent::setUp();
		$this->_model = new Recipe_Model_Recipe();
	}

	public function testGetResouceId()
	{
		$this->assertEquals('recipe', $this->_model->getResourceId() );
	}

	public function testConsrtuctWithId()
	{
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet(
            $this->getConnection()
        );
        $ds->addTable('zfbugs', 'SELECT * FROM zfbugs');

	}



	/*
	public function __construct($id = null)
	public function getRecipe($recipe_id)
	public function getRecipes($userID = null, $order = null, $direction = 'ASC')
	public function getRecipesSelect($userID = null, $order = null, $direction = 'ASC')
	public function getIngredients( $recipe_id )
	public function getMethods( $recipe_id )
	 */

}