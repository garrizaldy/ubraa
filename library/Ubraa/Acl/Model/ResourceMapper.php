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
 * @package     Ubraa_Acl_Model
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

/**
 * Data mapper for acl resources
 */
class Ubraa_Acl_Model_ResourceMapper extends Ubraa_Model_MapperAbstract
{
	protected $_tableGateway = 'Ubraa_Acl_Model_DbTable_Resource';
	
	/**
	 * Adds a new resource
	 *
	 * @param array $data
	 * @return mixed $primaryKey
	 */
	public function add(array $data)
	{
		return $this->insert($data);
	}
	
	/**
	 * Retrieves a resource
	 *
	 * @param int $resourceId
	 * @return array|boolean false
	 */
	public function get($resourceId)
	{
		$table = $this->_getTable();
		$select = $table->select()
					->where('resource_id = ?', $resourceId);
					
		return $this->fetchRow($select);
	}
	
	/**
	 * Retrieves a resource by its name
	 *
	 * @param string $name
	 * @return array|boolean false
	 */
	public function getByName($resourceName)
	{
		$table = $this->_getTable();
		$select = $table->select()
					->where('resource_name = ?', $resourceName);
					
		return $this->fetchRow($select);
	}
	
	/**
	 * Returns all resources
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
	 * Updates a resource
	 *
	 * @param int $resourceId
	 * @param array $data
	 * @return int $affectedRows|boolean false
	 */
	public function save($resourceId, array $data)
	{
		$table = $this->_getTable();
		$where = array();
		
		$where[] = $table->getDefaultAdapter()->quoteInto('resource_id = ?', $resourceId);
		
		return $this->update($data, $where);
	}
	
	/**
	 * Deletes a resource
	 *
	 * @param int $resourceId
	 * return int $affectedRows|boolean false
	 */
	public function delete($resourceId)
	{
		$table = $this->_getTable();
		
		$where = array();
		$where[] = $table->getDefaultAdapter()->quoteInto('resource_id = ?', $resourceId);
		
		return $this->deleteRecord($where);
	}
}