<?php

require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{

	protected $_application;
	protected $_filesDir;
	private $_db;

 	public function setUp()
    {
		$this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
		$this->getFrontController()->setParam('bootstrap', $this->_application->getBootstrap());
		$this->getConnection();
    }

	public function appBootstrap()
	{
		$this->_application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
		$this->_application->bootstrap();
	}

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */

    protected function getConnection()
    {
        if($this->_db == null) {
			$options = $this->_application->getOptions();
            $schema = $options['resources']['db']['params']['dbname'];
            $db = $this->_application->getBootstrap()->getPluginResource('db')->getDbAdapter();
            $this->_db = new Zend_Test_PHPUnit_Db_Connection($db, $schema);
            Zend_Db_Table_Abstract::setDefaultAdapter($this->_db->getConnection());
        }
        return $this->_db;
    }

	/**
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */

	protected function getDataSet()
	{
		return $this->createFlatXmlDataSet(
			dirname(__FILE__) . '/_files/seed.xml'
		);
	}

}