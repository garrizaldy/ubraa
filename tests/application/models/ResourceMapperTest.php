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
 * @package     Application_MapperTest
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

require_once 'ControllerTestCase.php';

/**
 * Resource data mapper test case
 */
class Application_Model_ResourceMapperTest extends ControllerTestCase
{	
	public function testObject()
	{
		$resourceMapper = new Application_Model_ResourceMapper;
		$this->assertType('Application_Model_ResourceMapper', $resourceMapper);
		
		return $resourceMapper;
	}
	
	/**
	 * @depends testObject
	 * @param $resourceMapper
	 */
	public function testAdd($resourceMapper)
	{
		$data = array(
			'resource_id' => 120,
			'resource_name' => 'testAddResource',
			'resource_description' => 'added by unit testing'
		);
		$result = $resourceMapper->add($data);
		$this->assertTrue((boolean)$result);
		
		return array(
			'mapper' => $resourceMapper,
			'data' => $data
		);
	}
	
	/**
	 * @depends testObject
	 * @param $resourceMapper
	 */
	public function testAddDuplicate($resourceMapper)
	{
		$data = array(
			'resource_id' => 120,
			'resource_name' => 'testAddResource',
			'resource_description' => 'added by unit testing'
		);
		$result = $resourceMapper->add($data);
		$this->assertFalse((boolean)$result);
		$this->assertTrue($resourceMapper->hasExceptions());
		$this->assertTrue($resourceMapper->hasMessages());
		
		$resourceMapper->reset();
	}
	
	/**
	 * @depends testObject
	 * @param $resourceMapper
	 */
	public function testAddInvalid($resourceMapper)
	{
		// should fail because resource_id is numeric
		$data = array(
			'resource_id' => 'abcdefg',
			'resource_name' => 'testAddResource',
			'resource_description' => 'added by unit testing'
		);
		$result = $resourceMapper->add($data);
		$this->assertFalse((boolean)$result);
		$this->assertTrue($resourceMapper->hasExceptions());
		$this->assertTrue($resourceMapper->hasMessages());
		
		$resourceMapper->reset();
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGet(array $params)
	{
		$resourceId = $params['data']['resource_id'];
		$fromDb = $params['mapper']->get($resourceId);
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['resource_name'], $params['data']['resource_name']);
		
		return $params;
	}
	
	/**
	 * @depends testAdd
	 * @param array $params
	 */
	public function testGetByName(array $params)
	{
		$resourceName = $params['data']['resource_name'];
		
		$fromDb = $params['mapper']->getByName($resourceName);
		$this->assertType('array', $fromDb);
		$this->assertEquals($resourceName, $fromDb['resource_name']);
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
		$resourceId = 0;
		$fromDb = $params['mapper']->get($resourceId);
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
		$resourceId = $params['data']['resource_id'];
		$updatedData = $params['data'];
		$updatedData['resource_name'] = 'testAddResourceUp';
		
		$result = $params['mapper']->save($resourceId, $updatedData);
		$this->assertTrue((boolean)$result);
		
		// test updated name
		$getData = $params['mapper']->get($resourceId);
		$this->assertType('array', $getData);
		
		$this->assertEquals($updatedData['resource_name'], $getData['resource_name']);
	}
	
	/**
	 * @depends testGet
	 * @param array $params
	 */
	public function testSaveNonExisting(array $params)
	{
		$resourceId = 0;
		$updatedData = array('resource_name' => 'NewName');
		
		$result = $params['mapper']->save($resourceId, $updatedData);
		
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
		$resourceId = $params['data']['resource_id'];
		$result = $params['mapper']->delete($resourceId);
		$this->assertTrue((boolean)$result);
		
		// get it
		$fromDb = $params['mapper']->get($resourceId);
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
		$resourceId = $params['data']['resource_id'];
		$result = $params['mapper']->delete($resourceId);
		
		$this->assertEquals(0, $result);
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
}