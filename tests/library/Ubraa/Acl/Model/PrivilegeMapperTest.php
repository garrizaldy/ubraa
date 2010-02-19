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
 * Privilege data mapper test case
 */
class Ubraa_Acl_Model_PrivilegeMapperTest extends ControllerTestCase
{	
	public function testObject()
	{
		$privMapper = new Ubraa_Acl_Model_PrivilegeMapper;
		$this->assertType('Ubraa_Acl_Model_PrivilegeMapper', $privMapper);
		
		return $privMapper;
	}
	
	/**
	 * @depends testObject
	 * @param Ubraa_Acl_Model_PrivilegeMapper $privMapper
	 */
	public function testAdd(Ubraa_Acl_Model_PrivilegeMapper $privMapper)
	{
		$data = array(
			'role_id' => 120,
			'resource_id' => 120,
			'privilege_name' => 'TEST_PRIVILEGE',
			'privilege_description' => 'test privilege added by unit test',
			'allow' => 1
		);
		
		$result = $privMapper->add($data);
		$this->assertTrue((boolean)$result);
		
		return array(
			'mapper' => $privMapper,
			'data' => $data
		);
	}
	
	/**
	 * @depends testAdd
	 * @param array $param
	 */
	public function testAddDuplicate(array $params)
	{
		$result = $params['mapper']->add($params['data']);
		
		$this->assertFalse($result);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertTrue($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	/** Disabled currently because it still inserts
	 * and converts string to 0
	public function testAddInvalid(array $params)
	{
		$data = array(
			'role_id' => 'abc',
			'resource_id' => 'def',
			'privilege_name' => 'TEST',
			'privilege_description' => 'test privilege added by unit test',
			'allow' => 0
		);
		
		$result = $params['mapper']->add($data);
		
		$this->assertFalse($result);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertTrue($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}**/
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGet(array $params)
	{
		$fromDb = $params['mapper']->get(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['privilege_name'], $params['data']['privilege_name']);
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
	public function testGetNonExisting(array $params)
	{
		$fromDb = $params['mapper']->get(0, 0, 'TEST');
		
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testSave(array $params)
	{
		$newData = array('privilege_description' => 'new description');
		$result = $params['mapper']->save(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name'],
			$newData
		);
		
		$this->assertEquals(1, $result);
		
		// get it back
		$fromDb = $params['mapper']->get(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		$this->assertType('array', $fromDb);
		$this->assertNotEquals(
			$fromDb['privilege_description'],
			$params['data']['privilege_description']
		);
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testSaveNonExisting(array $params)
	{
		$newData = array('privilege_description' => 'New desc 123');
		$result = $params['mapper']->save(0, 0, 'abc', $newData);
		
		// no record affected
		$this->assertEquals(0, $result);
		
		// no failure messages
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testDelete(array $params)
	{
		$result = $params['mapper']->delete(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		// one record affected
		$this->assertEquals(1, $result);
		
		// no failure messages
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		// should not exists
		$fromDb = $params['mapper']->get(
			$params['data']['role_id'],
			$params['data']['resource_id'],
			$params['data']['privilege_name']
		);
		
		// should not found a record, with message but no exceptions
		$this->assertFalse($fromDb);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testDeleteNonExisting(array $params)
	{
		$result = $params['mapper']->delete(0, 0, 'abc');
		
		// no record affected
		$this->assertEquals(0, $result);

		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
}