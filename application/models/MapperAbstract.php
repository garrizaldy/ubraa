<?php
/**
 * Ubraa
 * 
 * NOTICE OF LICENSE

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @category    Ubraa
 * @package     Application_Model
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

abstract class Application_Model_MapperAbstract
{
	const NO_RECORD_FOUND 	= 'noRecordFound';
	const NO_RECORD_ADDED 	= 'noRecordAdded';
	const NO_RECORD_UPDATED = 'noRecordUpdated';
	const NO_RECORD_DELETED = 'noRecordDeleted';
	const GENERAL_DB_ERROR 	= 'generalDbError';
	const DB_ERROR_SELECT 	= 'dbErrorSelect';
	const DB_ERROR_INSERT 	= 'dbErrorInsert';
	const DB_ERROR_UPDATE 	= 'dbErrorUpdate';
	const DB_ERROR_DELETE 	= 'dbErrorDelete';
	
	/**
	 * Error message container templates
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::NO_RECORD_FOUND 	=> 'No record found',
		self::NO_RECORD_ADDED 	=> 'No record added',
		self::NO_RECORD_UPDATED => 'No record updated',
		self::NO_RECORD_DELETED => 'No record deleted',
		self::GENERAL_DB_ERROR 	=> 'A database error occured',
		self::DB_ERROR_SELECT 	=> 'An error occured while retrieving record from the database',
		self::DB_ERROR_INSERT 	=> 'An error occured while inserting record to the database',
		self::DB_ERROR_UPDATE 	=> 'An error occured while updating record to the database',
		self::DB_ERROR_DELETE 	=> 'An error occured while deleting record from the database'
	);
	
	/**
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_table;
	
	/**
	 * @var string
	 */
	protected $_tableGateway;
	
	/**
	 * Contains an array of messages set by the model
	 * when exceptions are catched inside this model class
	 * 
	 * @var array
	 */
	protected $_messages = array();
	
	/**
	 * Contains exceptions that are catched
	 * within the model. Exceptions are in plain/vanilla
	 * exception objects
	 * 
	 * @var array
	 */
	protected $_exceptions = array();
	
	/**
	 * Adds a message to the message stack
	 * 
	 * @param $message
	 * @param $exception
	 * 
	 * @return $this Provides fluent interface
	 */
	public function addMessage($message, $exception = null)
	{
		$this->_messages[] = $message;
		if ($exception)
		{
			$this->addException($exception);
		}
		return $this;
	}
	
	/**
	 * Returns messages
	 * 
	 * @return array
	 */
	public function getMessages()
	{
		return $this->_messages;
	}
	
	/**
	 * Returns true if and only if $_messages has a content
	 * 
	 * @return boolean
	 */
	public function hasMessages()
	{
		return !empty($this->_messages);
	}
	
	/**
	 * clearMessages()
	 * 
	 * @return $this Provides fluent interface
	 */
	public function clearMessages()
	{
		$this->_messages = array();
		return $this;
	}
	
	/**
	 * addException()
	 * 
	 * @param $exception
	 * @return $this Provides fluent interface
	 */
	public function addException($exception)
	{
		$this->_exceptions[] = $exception;
		return $this;
	}
	
	/**
	 * getExceptions()
	 * 
	 * @return array
	 */
	public function getExceptions()
	{
		return $this->_exceptions;
	}
	
	/**
	 * Returns true if and only if there are exceptions
	 * 
	 * @return boolean
	 */
	public function hasExceptions()
	{
		return !empty($this->_exceptions);
	}
	
	/**
	 * clearExceptions()
	 * 
	 * @return $this Provides fluent interface
	 */
	public function clearExceptions()
	{
		$this->_exceptions = array();
		return $this;
	}
	
	/**
	 * Resets all variables into default values
	 * Does not reset the table gateway object
	 * 
	 * @return $this Provides fluent interface
	 */
	public function reset()
	{
		$this->clearMessages();
		$this->clearExceptions();
		return $this;
	}
	
	/**
	 * Generic insert API
	 * 
	 * @param array $data
	 * 
	 * @return mixed $primaryKey|boolean false
	 */
	public function insert(array $data)
	{
		$table = $this->_getTable();
		
		try {
			return $table->insert($data);
		}
		catch (Zend_Db_Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_INSERT], $e);
		}
		
		return false;
	}
	
	/**
	 * Returns a record
	 * 
	 * @param Zend_Db_Select $select
	 * @return array|boolean false
	 */
	public function fetchRow(Zend_Db_Select $select)
	{
		$table = $this->_getTable();
		try {
			$result = $table->fetchRow($select);
			if ($result)
			{
				return $result->toArray();
			}
			$this->addMessage($this->_messageTemplates[self::NO_RECORD_FOUND]);
		}
		catch (Zend_Db_Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_INSERT], $e);
		}
		
		return false;
	}
	
	/**
	 * Returns a set of records
	 * 
	 * @param Zend_Db_Select $select
	 * @return array|boolean false
	 */
	public function fetchAll(Zend_Db_Select $select)
	{
		$table = $this->_getTable();
		try {
			$result = $table->fetchAll($select);
			if ($result)
			{
				$result = $result->toArray();
				if (!empty($result))
				{
					return $result;
				}
			}
			$this->addMessage($this->_messageTemplates[self::NO_RECORD_FOUND]);
		}
		catch (Zend_Db_Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_SELECT], $e);
		}
		
		return false;
	}
	
	/**
	 * Updates a record or records
	 * 
	 * @param array $data
	 * @param array $where
	 * @return int $affectedRows|boolean false
	 */
	public function update(array $data, array $where = array())
	{
		$table = $this->_getTable();
		try {
			return $table->update($data, $where);
		}
		catch (Zend_Db_Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_UPDATE], $e);
		}
		
		return false;
	}
	
	/**
	 * deleteRecord()
	 * 
	 * @param array $where
	 * @return int $affectedRows|boolean false
	 */
	public function deleteRecord(array $where = array())
	{
		$table = $this->_getTable();
		try {
			return $table->delete($where);
		}
		catch (Zend_Db_Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_DELETE], $e);
		}
		
		return false;
	}
	
	/**
	 * Returns the table gateway object
	 * If none is set on first use, it will use the
	 * _tableGateway variable as the class name
	 * to create the object
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	protected function _getTable()
	{
		if ($this->_table === null)
		{
			$this->_table = new $this->_tableGateway;
		}
		return $this->_table;
	}
	
	/**
	 * Sets the table gateway
	 *
	 * @param Zend_Db_Table_Abstract $table
	 * return $this Provides fluent interface
	 */
	public function setTable(Zend_Db_Table_Abstract $table)
	{
		$this->_table = $table;
		return $this;
	}
}
