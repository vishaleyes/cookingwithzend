<?php
/**
 * Zend_Session_SaveHandler_Interface
 */
require_once 'Zend/Session/SaveHandler/Interface.php';

/**
 * Zend_Session_SaveHandler_Db
 * 
 * The class Zend_Session_SaveHandler_Db implements the interface Zend_Session_SaveHandler_Interface to 
 * provide session handling for databases
 * 
 * @package    Framework
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Copyright (c) 2007 Travello GmbH
 */
class Zend_Session_SaveHandler_Db implements Zend_Session_SaveHandler_Interface
{
    /**
     * @var object default instance of Zend_Db_Adapter_Abstract
     */
    static protected $_defaultDb;
    
    /**
     * @var array default table definitions
     */
    static protected $_defaultDef = array(
        'table'   => 'sessions',
        'primary' => 'id',
        'expire'  => 'expire',
        'updated' => 'updated',
        'data'    => 'data'
    );
    
    /**
     * @var integer maximum life time
     */
    static protected $_lifeTime = 1440;
    
    /**
     * @var integer permanent time
     */
    static protected $_permanentTime = 2592000;
    
    /**
     * @var Zend_Db_Adapter
     */
    protected $_db;

    /**
     * @var array table definitions
     */
    protected $_def;

    /**
     * Constructor to set db handler and table definitions
     */
    public function __construct($db = null, $tableDef = array())
    {
        // set db adapter
        if (!$db)
        {
            $this->_db = $this->getDefaultAdapter();
        } else {
        	$this->_db = $db;
        }
       
        // check if db adapter is set
        if (!$this->_db instanceof Zend_Db_Adapter_Abstract) {
            throw new Zend_Session_Exception('db object does not implement Zend_Db_Adapter_Abstract');
        }
        
        // set table definitions
        if (!$this->_def)
        {
            $this->_def = $this->getDefaultDefinitions();
        }
    }
    
	/**
     * Sets the default Zend_Db_Adapter
     *
     * @param Zend_Db_Adapter $db A Zend_Db_Adapter object.
     */
    static public final function setDefaultAdapter($db)
    {
        // make sure it's a Zend_Db_Adapter
        if (! $db instanceof Zend_Db_Adapter_Abstract) {
            throw new Zend_Session_Exception('db object does not implement Zend_Db_Adapter_Abstract');
        }
        
        Zend_Session_SaveHandler_Db::$_defaultDb = $db;
    }

    /**
     * Sets the default table definitions
     *
     * @param array $def Tabel definitions
     */
    static public final function setDefaultDefinitions($def = array())
    {
        // set table definitions if passed
        foreach($def as $key => $value)
        {
            if (isset(Zend_Session_SaveHandler_Db::$_defaultDef[$key]))
            {
                Zend_Session_SaveHandler_Db::$_defaultDef[$key] = $value;
            }
        }
    }

    /**
     * Sets the session lifetime value
     * 
     * @param integer $time lifetime for session 
     */
    static public final function setLifeTime($time = 1440)
    {
        // check for integer value
        if (is_integer($time))
        {
            Zend_Session_SaveHandler_Db::$_lifeTime = $time;
        }
        else
        {
            Zend_Session_SaveHandler_Db::$_lifeTime = (integer) get_cfg_var("session.gc_maxlifetime");
        }
    }
    
    /**
     * Sets the session permanent value
     * 
     * @param integer $time permanent for session 
     */
    static public final function setPermanentTime($time = 2592000)
    {
        // check for integer value
        if ($time > 0)
        {
            Zend_Session_SaveHandler_Db::$_permanentTime = $time;
        }
        else
        {
            Zend_Session_SaveHandler_Db::$_permanentTime = 30*24*60*60;
        }
    }
    
    /**
     * Get db adapter
     */
    public function getDefaultAdapter()
    {
        return Zend_Session_SaveHandler_Db::$_defaultDb;
    }
    
    /**
     * Get table definitions
     */
    public function getDefaultDefinitions()
    {
        return Zend_Session_SaveHandler_Db::$_defaultDef;
    }
    
    /**
     * Get expire time
     */
    public function getLifeTime()
    {
        return Zend_Session_SaveHandler_Db::$_lifeTime;
    }
    
    /**
     * Get permanent time
     */
    public function getPermanentTime()
    {
        return Zend_Session_SaveHandler_Db::$_permanentTime;
    }
    
    /**
     * Open Session - retrieve resources
     *
     * @param string $save_path
     * @param string $name
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * Close Session - free resources
     *
     */
    public function close()
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $id
     */
    public function read($id)
    {
        // build select to read session data for session id
        $select = $this->_db->select();
        $select->from($this->_def['table'], $this->_def['data']);
        $select->where($this->_def['primary'] . ' = ?', $id);
        $select->where($this->_def['expire'] . ' > ?', time());
        
        // read data
        $row = $this->_db->fetchOne($select);
      
        // return empty string if no active session found
        if (false === $row)
        {
            return '';
        }
        
        // return session data
        return $row;
    }

    /**
     * Write Session - commit data to resource
     *
     * @param string $id
     * @param mixed $data
     */
    public function write($id, $data)
    {
        // check for member id and permanent
        if ($pos = strpos($data, '|'))
        {
            $sessData = unserialize(substr($data, $pos + 1));
           
            if (isset($sessData['id']))
            {
                $memberId = $sessData['id'];
            }
          
            if (isset($sessData['sess_permanent']))
            {
                $permanent = $sessData['sess_permanent'];
            }
        }
        
        // set update time
        $updated = time();
        
        // calculate new expire date
        if (isset($permanent))
        {
            $expire = $updated + Zend_Session_SaveHandler_Db::$_permanentTime;
        }
        else
        {
            $expire = $updated + Zend_Session_SaveHandler_Db::$_lifeTime;
        }
   
        // build select to read if a dataset exists for session id 
        $select = $this->_db->select();
        $select->from($this->_def['table'], $this->_def['primary']);
        $select->where($this->_def['primary'] . ' = ?', $id);
        
        // read data
        $row = $this->_db->fetchRow($select);
        
        $newRow = array(
            $this->_def['expire' ] => $expire,
            $this->_def['updated'] => $updated,
            $this->_def['data'   ] => $data,
        );
        
        // set member id if available
        if (isset($memberId))
        {
            $newRow['user_id'] = $memberId;
        }
        
        // set permanent if available
        if (isset($permanent))
        {
            $newRow['sess_permanent'] = $permanent;
        }
        
        try
        {
            // if no dataset found, insert new dataset
            if (empty($row))
            {
                $newRow[$this->_def['primary']] = $id;
                
                $rows_affected = $this->_db->insert($this->_def['table'], $newRow);
            }
            // otherweise update existing dataset
            else
            {
                $where = $this->_db->quoteInto($this->_def['primary'] . ' = ?', $id);
                
                $rows_affected = $this->_db->update($this->_def['table'], $newRow, $where);
            }
            
            if (1 == $rows_affected)
            {
                // insert successful
                return true;
            }
        }
        // catch any PDOException
        catch (PDOException $e)
        {
        }
        
        // write not successful
        return false;
    }

    /**
     * Destroy Session - remove data from resource for
     * given session id
     *
     * @param string $id
     */
    public function destroy($id)
    {
        // build where clause to destroy session
        $where = $this->_db->quoteInto($this->_def['primary'] . ' = ?', $id);
        
        // destroy session
        $rows_affected = $this->_db->delete($this->_def['table'], $where);
        
        // return true if destroying was successful
        if (1 == $rows_affected)
        {
            return true;
        }
        
        // destroy not successful
        return false;
    }

    /**
     * Garbage Collection - remove old session data older
     * than $maxlifetime (in seconds)
     *
     * @param int $maxlifetime
     */
    public function gc($maxlifetime)
    {
        // build where clause to remove old sessions
        $where = $this->_db->quoteInto($this->_def['expire'] . ' < ?', time());
        
        // remove old session
        $rows_affected = $this->_db->delete($this->_def['table'], $where);
        
        // truncate table
        $this->_db->query('ALTER TABLE sessions TYPE = MyISAM');
        
        // successful removal of datasets
        if (0 < $rows_affected)
        {
            return true;
        }
        
        // no datasets removed
        return false;
    }

}
