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
 * @package     Application_ModelTest
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

require_once 'ControllerTestCase.php';

class Application_Model_UserMapperTest extends ControllerTestCase
{
	public function testObject()
	{
		$user = new Application_Model_UserMapper;
		$this->assertType('Application_Model_UserMapper', $user);
		
		return $user;
	}
	
	/**
	 * @depends testObject
	 */
	public function testAdd($user)
	{
		$data = array(
			'user_id'	=> 900,
			'username'	=> 'testUserX001',
			'password'	=> 'testPassword',
			'email'		=> 'test@email.com'
		);
		
		$result = $user->add($data);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		return array(
			'data' => $data,
			'mapper' => $user
		);
	}
	
	/**
	 * @depends testAdd
	 */
	public function testAddDuplicateId(array $params)
	{
		$result = $params['mapper']->add($params['data']);
		$this->assertFalse($result);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertTrue($params['mapper']->hasExceptions());
		
		// clear up
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 */
	public function testDuplicateUsername(array $params)
	{
		$data = $params['data'];
		// change only the email and id
		$data['user_id'] = 901;
		$data['email'] = 'another_email_01@dd.com';
		
		$result = $params['mapper']->add($data);
		$this->assertFalse($result);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertTrue($params['mapper']->hasExceptions());
		
		// clear up
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 */
	public function testDuplicateEmail(array $params)
	{
		$data = $params['data'];
		// change only the username and id
		$data['user_id'] = 901;
		$data['username'] = 'testUserX002';
		
		$result = $params['mapper']->add($data);
		$this->assertFalse($result);
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertTrue($params['mapper']->hasExceptions());
		
		// clear up
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetById(array $params)
	{
		$id = $params['data']['user_id'];
		
		$result = $params['mapper']->getById($id);
		$this->assertType('array', $result);
		$this->assertEquals($result['username'], $params['data']['username']);
		
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetByUsername(array $params)
	{
		$username = $params['data']['username'];
		
		$result = $params['mapper']->getByUsername($username);
		
		$this->assertType('array', $result);
		$this->assertEquals($result['username'], $params['data']['username']);
		
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetNonExistingId(array $params)
	{
		$id = 1000;
		
		$result = $params['mapper']->getById($id);
		$this->assertFalse($result);
		
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 */
	public function testGetNonExistingUsername(array $params)
	{
		$username = 'testUserX003';
		
		$result = $params['mapper']->getByUsername($username);
		$this->assertFalse($result);
		
		$this->assertTrue($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		$params['mapper']->reset();
	}
	
	/**
	 * @depends testAdd
	 */
	public function testSave(array $params)
	{
		$oldEmail = $params['data']['email'];
		
		$id = $params['data']['user_id'];
		$data = array('email' => 'test2@email.com');
		
		$result = $params['mapper']->save($id, $data);
		// only one row affected
		$this->assertEquals(1, $result);
		
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		// get it
		$fromDb = $params['mapper']->getById($id);
		$this->assertType('array', $fromDb);
		$this->assertNotEquals($oldEmail, $fromDb['email']);
		
		return array(
			'mapper' => $params['mapper'],
			'data' => $fromDb
		);
	}
	
	/**
	 * @depends testSave
	 */
	public function testSaveNonExisting(array $params)
	{
		$id = 902;
		$data = array('email' => 'test3@email.com');
		
		$result = $params['mapper']->save($id, $data);
		$this->assertFalse((boolean)$result);

		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testSave
	 */
	public function testDelete(array $params)
	{
		$id = $params['data']['user_id'];
		$result = $params['mapper']->delete($id);
		// only one record deleted
		$this->assertEquals(1, $result);
		
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
	
	/**
	 * @depends testSave
	 */
	public function testDeleteNonExisting(array $params)
	{
		$id = $params['data']['user_id'];
		$result = $params['mapper']->delete($id);
		// no record deleted
		$this->assertEquals(0, $result);
		
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
		
		// another
		$id = 999;
		$result = $params['mapper']->delete($id);
		// no record deleted
		$this->assertEquals(0, $result);
		
		$this->assertFalse($params['mapper']->hasMessages());
		$this->assertFalse($params['mapper']->hasExceptions());
	}
}