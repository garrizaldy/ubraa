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

class Application_Model_UserMapper extends Application_Model_MapperAbstract
{
	protected $_tableGateway = 'Application_Model_DbTable_User';
	
	/**
	 * Adds a new user record
	 *
	 * @param array $data
	 * @return mixed $primaryKey
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Retrieves a user record by its ID
	 *
	 * @param int $userId
	 * @return array|boolean false
	 */
	public function getById($userId)
	{
		$table = $this->_getTable();
		$select = $table->select()
			->where('user_id = ?', $userId);
			
		return $this->fetchRow($select);
	}
	
	/**
	 * Retrieves a user record by its username
	 *
	 * @param string $username
	 * @return array|boolean false
	 */
	public function getByUsername($username)
	{
		$table = $this->_getTable();
		$select = $table->select()
			->where('username = ?', $username);
			
		return $this->fetchRow($select);
	}
	
	
	/**
	 * Updates a user record
	 *
	 * @param int $userId
	 * @param array $data
	 * @return int $affectedRows
	 */
	public function save($userId, array $data)
	{
		$table = $this->_getTable();
		
		$where = array();
		$where[] = $table->getDefaultAdapter()->quoteInto('user_id = ?', $userId);
		
		return $this->update($data, $where);
	}
	
	/**
	 * Deletes a user record
	 * 
	 * @param int $userId
	 * @return int $affectedRows
	 */
	public function delete($userId)
	{
		$table = $this->_getTable();
		
		$where = array();
		$where[] = $table->getDefaultAdapter()->quoteInto('user_id = ?', $userId);
		
		return $this->deleteRecord($where);
	}
}