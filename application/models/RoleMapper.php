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
class Application_Model_RoleMapper extends Application_Model_MapperAbstract
{
	protected $_tableGateway = 'Application_Model_DbTable_Role';
	
	/**
	 * Adds a new role
	 *
	 * @param array $data
	 * @return mixed $primaryKey
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Retrieves a role
	 *
	 * @param int $roleId
	 * @return array|boolean false
	 */
	public function get($roleId)
	{
		$table = $this->_getTable();
		$select = $table->select()
					->where('role_id = ?', $roleId);
					
		return $this->fetchRow($select);
	}
	
	/**
	 * Returns all roles
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
	 * Updates a role
	 *
	 * @param int $roleId
	 * @param array $data
	 * @return int $affectedRows|boolean false
	 */
	public function save($roleId, array $data)
	{
		$table = $this->_getTable();
		$where = array();
		
		$where[] = $table->getDefaultAdapter()->quoteInto('role_id = ?', $roleId);
		
		return $this->update($data, $where);
	}
	
	/**
	 * Deletes a role
	 *
	 * @param int $roleId
	 * return int $affectedRows|boolean false
	 */
	public function delete($roleId)
	{
		$table = $this->_getTable();
		
		$where = array();
		$where[] = $table->getDefaultAdapter()->quoteInto('role_id = ?', $roleId);
		
		return $this->deleteRecord($where);
	}
}