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

/**
 * Data mapper for acl roles
 */
class Application_Model_PrivilegeMapper extends Application_Model_MapperAbstract
{
	protected $_tableGateway = 'Application_Model_DbTable_Privilege';
	
	/**
	 * Adds a new privilege
	 * Data must contain a role_id, resource_id and privilege name
	 * to complete the privilege
	 *
	 * @param array $data
	 * @return mixed $primaryKey
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Retrieves a privilege
	 *
	 * @param int $roleId
	 * @param int $resourceId
	 * @param string $privilegeName
	 *
	 * @return array|boolean false
	 */
	public function get($roleId, $resourceId, $privilegeName)
	{
		$table = $this->_getTable();
		$select = $table->select()
					->where('role_id = ?', $roleId)
					->where('resource_id = ?', $resourceId)
					->where('privilege_name = ?', $privilegeName);
					
		return $this->fetchRow($select);
	}
	
	/**
	 * Returns all privileges
	 *
	 * @return array|boolean false
	 */
	public function getAll()
	{
		$table = $this->_getTable();
		$select = $table->select();
		return $this->fetchAll($select);
	}
	
	/**
	 * Updates a privilege
	 *
	 * @param int $roleId
	 * @param int $resourceId
	 * @param string $privilegeName
	 * @param array $data
	 *
	 * @return int $affectedRows|boolean false
	 */
	public function save($roleId, $resourceId, $privilegeName, array $data)
	{
		$table = $this->_getTable();
		$db = $table->getDefaultAdapter();
		
		$where = array();
		$where[] = $db->quoteInto('role_id = ?', $roleId);
		$where[] = $db->quoteInto('resource_id = ?', $resourceId);
		$where[] = $db->quoteInto('privilege_name = ?', $privilegeName);
		
		return $this->update($data, $where);
	}
	
	/**
	 * Deletes a privilege
	 *
	 * @param int $roleId
	 * @param int $resourceId
	 * @param string $privilegeName
	 *
	 * @return int $affectedRows
	 */
	public function delete($roleId, $resourceId, $privilegeName)
	{
		$table = $this->_getTable();
		$db = $table->getDefaultAdapter();
		
		$where = array();
		$where[] = $db->quoteInto('role_id = ?', $roleId);
		$where[] = $db->quoteInto('resource_id = ?', $resourceId);
		$where[] = $db->quoteInto('privilege_name = ?', $privilegeName);
		
		return $this->deleteRecord($where);
	}
}