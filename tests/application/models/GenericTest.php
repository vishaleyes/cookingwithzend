<?php

/**
 * Now I know I am not 'allowed' to test an abstract class but Im going to via another class
 *
 * @package default
 * @author Chris Lock
 */

require_once BASE_PATH."/application/models/GenericModel.php";
require_once "Zend/Db/Table/Abstract.php";

class Recipe_Model_DbTable_Mock extends Zend_Db_Table_Abstract {
	protected $_name = 'test';
}

class Recipe_Model_Mock extends Recipe_Model_GenericModel implements ArrayAccess
{
	protected $_data = array('foo'=>'bar');
}

class Model_GenericTest extends ControllerTestCase
{

	protected $_model;

	public function setUp()
	{
		parent::setUp();
		$this->_model = new Recipe_Model_Mock();
	}
	
	public function testConstruct()
	{
		$this->assertInstanceOf('Recipe_Model_GenericModel', $this->_model);
		$this->assertInstanceOf('Zend_Db_Table_Abstract', $this->_model->table);
		$this->assertInstanceOf('Zend_Log' , $this->_model->log);
	}

	public function testImplementationArrayAccess()
	{
		// offsetGet
		$this->assertEquals('bar', $this->_model['foo']);
		// offsetSet
		$this->_model['foo'] = 'jim';
		$this->assertEquals('jim', $this->_model['foo']);
		// offsetExists
		$this->assertTrue(isset($this->_model['foo']));
		// offsetUnset
		unset($this->_model['foo']);
		$this->assertFalse(isset($this->_model['foo']));
	}

	public function testSleep()
	{
		$foo = serialize($this->_model);
		$actual = serialize($this->_model);
		$this->assertContains('_data', $actual);
		$this->assertContains(get_class($this->_model), $actual);
	}

	/**
     * @expectedException Zend_Db_Statement_Exception
     */
	public function testGetSingleByFieldException()
	{
		// Testing trying to get a column that does not exist
		$row = $this->_model->fetchSingleByField('foo', 'bar');
	}

	public function testGetSingleByField()
	{
		// @todo tie this into a db test
		$row = $this->_model->fetchSingleByField('id', 407);
		$this->assertFalse($row);
		$row = $this->_model->fetchSingleByField('id', 1);
		$this->assertTrue(is_array($row));
		$this->assertTrue(isset($this->_model['id']));
	}

	/**
     * @expectedException Zend_Db_Statement_Exception
     */
	public function testGetByFieldException()
	{
		// Testing trying to get a column that does not exist
		$row = $this->_model->fetchByField('foo', 'bar');
	}

	public function testGetByField()
	{
		// @todo tie this into a db test
		$rowSet = $this->_model->fetchByField('id', 407);
		$this->assertTrue(empty($rowSet));

		$rowSet = $this->_model->fetchByField('id', 1);
		$this->assertEquals(1, count($rowSet));
	}

	/**
     * @expectedException Zend_Db_Statement_Exception
     */
	public function testFetchForMultiSelectException()
	{
		// Testing trying to get a column that does not exist
		$row = $this->_model->fetchForMultiSelect('id', 'idontexist');;
	}

	public function testFetchForMultiSelect()
	{
		// @todo tie this into a db test
		$rowSet = $this->_model->fetchForMultiSelect('id', 'table');
		$this->assertEquals(1, count($rowSet));
	}

}