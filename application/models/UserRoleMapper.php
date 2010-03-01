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

class Application_Model_UserRoleMapper extends Ubraa_Model_MapperAbstract
{
	protected $_tableGateway = 'Application_Model_DbTable_UserRole';
	
	/**
	 * Adds a new user role record
	 *
	 * @param array $data
	 * @return mixed $primaryKey
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Retrieves a user role record by its userid and roleId
	 *
	 * @param int $userId
	 * @param int $roleId
	 * @return array|boolean false
	 */
	public function get($userId, $roleId)
	{
		$table = $this->_getTable();
		$select = $table->select()
			->where('user_id = ?', $userId)
			->where('role_id = ?', $roleId);
			
		return $this->fetchRow($select);
	}
	
	/**
	 * Retrieves user's role records by its userId
	 *
	 * @param string $userId
	 * @return array|boolean false
	 */
	public function getUserRoles($userId)
	{
		$table = $this->_getTable();
		$select = $table->select()
			->where('user_id = ?', $userId);
			
		return $this->fetchAll($select);
	}
	
	
	/**
	 * Updates user's role record
	 *
	 * @param int $userId
	 * @param array $data
	 * @return int $affectedRows
	 */
	public function save($userId, $roleId, array $data)
	{
		$table = $this->_getTable();
		$db = $table->getDefaultAdapter();
		
		$where = array();
		$where[] = $db->quoteInto('user_id = ?', $userId);
		$where[] = $db->quoteInto('role_id = ?', $roleId);
		
		return $this->update($data, $where);
	}
	
	/**
	 * Deletes user's role record or records
	 * 
	 * @param int $userId
	 * @param int $roleId Optional
	 * @return int $affectedRows
	 */
	public function delete($userId, $roleId = null)
	{
		$table = $this->_getTable();
		$db = $table->getDefaultAdapter();
		
		$where = array();
		$where[] = $db->quoteInto('user_id = ?', $userId);
		
		if ($roleId)
		{
			$where[] = $db->quoteInto('role_id = ?', $roleId);
		}
		
		return $this->deleteRecord($where);
	}
}