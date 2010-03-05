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
 * @package     Ubraa_Acl_MapperTest
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

require_once 'ControllerTestCase.php';

/**
 * Role data mapper test case
 */
class Application_Model_RoleMapperTest extends ControllerTestCase
{	
	public function testObject()
	{
		$roleMapper = new Application_Model_RoleMapper;
		$this->assertType('Application_Model_RoleMapper', $roleMapper);
		
		return $roleMapper;
	}
	
	/**
	 * @depends testObject
	 * @param $roleMapper
	 */
	public function testAdd($roleMapper)
	{
		$data = array(
			'role_id' => 120,
			'role_name' => 'testAddRole',
			'role_description' => 'added by unit testing'
		);
		$result = $roleMapper->add($data);
		$this->assertTrue((boolean)$result);
		
		return array(
			'mapper' => $roleMapper,
			'data' => $data
		);
	}
	
	/**
	 * @depends testObject
	 * @param $roleMapper
	 */
	public function testAddDuplicate($roleMapper)
	{
		$data = array(
			'role_id' => 120,
			'role_name' => 'testAddRole',
			'role_description' => 'added by unit testing'
		);
		$result = $roleMapper->add($data);
		$this->assertFalse((boolean)$result);
		$this->assertTrue($roleMapper->hasExceptions());
		$this->assertTrue($roleMapper->hasMessages());
		
		$roleMapper->reset();
	}
	
	/**
	 * @depends testObject
	 * @param $roleMapper
	 */
	public function testAddInvalid($roleMapper)
	{
		// should fail because role_id is numeric
		$data = array(
			'role_id' => 'abcdefg',
			'role_name' => 'testAddRole',
			'role_description' => 'added by unit testing'
		);
		$result = $roleMapper->add($data);
		$this->assertFalse((boolean)$result);
		$this->assertTrue($roleMapper->hasExceptions());
		$this->assertTrue($roleMapper->hasMessages());
		
		$roleMapper->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGet(array $params)
	{
		$roleId = $params['data']['role_id'];
		$fromDb = $params['mapper']->get($roleId);
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['role_name'], $params['data']['role_name']);
		
		return $params;
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGetAll(array $params)
	{
		$result = $params['mapper']->getAll();
		$this->assertType('array', $result);
		
		$this->assertFalse($params['mapper']->hasExceptions());
		$this->assertFalse($params['mapper']->hasMessages());
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGetNotExisting(array $params)
	{
		$roleId = 0;
		$fromDb = $params['mapper']->get($roleId);
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testSave(array $params)
	{
		$roleId = $params['data']['role_id'];
		$updatedData = $params['data'];
		$updatedData['role_name'] = 'testAddRoleUp';
		
		$result = $params['mapper']->save($roleId, $updatedData);
		$this->assertTrue((boolean)$result);
		
		// test updated name
		$getData = $params['mapper']->get($roleId);
		$this->assertType('array', $getData);
		
		$this->assertEquals($updatedData['role_name'], $getData['role_name']);
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testSaveNonExisting(array $params)
	{
		$roleId = 0;
		$updatedData = array('role_name' => 'NewName');
		
		$result = $params['mapper']->save($roleId, $updatedData);
		
		// affected rows must be 0 with no failure messages
		
		$this->assertEquals(0, $result);
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testDelete(array $params)
	{
		$roleId = $params['data']['role_id'];
		$result = $params['mapper']->delete($roleId);
		$this->assertTrue((boolean)$result);
		
		// get it
		$fromDb = $params['mapper']->get($roleId);
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testDeleteNonExisting(array $params)
	{
		$roleId = $params['data']['role_id'];
		$result = $params['mapper']->delete($roleId);
		
		$this->assertEquals(0, $result);
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
}