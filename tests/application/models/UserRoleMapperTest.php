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

class Application_Model_UserRoleMapperTest extends ControllerTestCase
{
	const TEST_DATA1 = 'test1';
	const TEST_DATA2 = 'test2';
	const TEST_DATA3 = 'test3';
	const TEST_DATA4 = 'test4';
	const TEST_DATA5 = 'test5';
	const TEST_DATA6 = 'test6';
	
	const TEST_USER1 = 901;
	const TEST_USER2 = 902;
	const TEST_USER3 = 903;
	
	const TEST_ROLE1 = 801;
	const TEST_ROLE2 = 802;
	const TEST_ROLE3 = 803;
	
	protected $_testData = array(
		// user 1 has role1, role2 and role3
		self::TEST_DATA1 => array(
			'user_id' 	=> self::TEST_USER1,
			'role_id'	=> self::TEST_ROLE1
		),
		self::TEST_DATA2 => array(
			'user_id'	=> self::TEST_USER1,
			'role_id'	=> self::TEST_ROLE2
		),
		self::TEST_DATA3 => array(
			'user_id'	=> self::TEST_USER1,
			'role_id'	=> self::TEST_ROLE3
		),
		// user 2 has only one role, role 3
		self::TEST_DATA4 => array(
			'user_id'	=> self::TEST_USER2,
			'role_id'	=> self::TEST_ROLE3
		),
		// user 3 has 2 roles, role 2 and role 3
		self::TEST_DATA5 => array(
			'user_id'	=> self::TEST_USER3,
			'role_id'	=> self::TEST_ROLE2
		),
		self::TEST_DATA6 => array(
			'user_id'	=> self::TEST_USER3,
			'role_id'	=> self::TEST_ROLE3
		)
	);
	
	public function testObject()
	{
		$user = new Application_Model_UserRoleMapper;
		$this->assertType('Application_Model_UserRoleMapper', $user);
		
		return $user;
	}
	
	/**
	 * @depends testObject
	 */
	public function testAddThemAll($user)
	{
		// for user 1, with 3 roles
		$result = $user->add($this->_testData[self::TEST_DATA1]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		$result = $user->add($this->_testData[self::TEST_DATA2]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		$result = $user->add($this->_testData[self::TEST_DATA3]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// for user 2, with 1 role
		$result = $user->add($this->_testData[self::TEST_DATA4]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// for user 3, with 2 roles
		$result = $user->add($this->_testData[self::TEST_DATA5]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		$result = $user->add($this->_testData[self::TEST_DATA6]);
		$this->assertTrue((boolean)$result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		return $user;
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testAddDuplicateUser1($user)
	{
		// add another user1 role 1 combination, should fail
		$data = array(
			'user_id' 	=> self::TEST_USER1,
			'role_id'	=> self::TEST_ROLE1
		);
		
		$result = $user->add($data);
		$this->assertFalse($result);
		$this->assertTrue($user->hasMessages());
		$this->assertTrue($user->hasExceptions());
		$user->reset();
		
		// add another user1 with role 2, should fail
		$data = array(
			'user_id' 	=> self::TEST_USER1,
			'role_id'	=> self::TEST_ROLE2
		);
		
		$result = $user->add($data);
		$this->assertFalse($result);
		$this->assertTrue($user->hasMessages());
		$this->assertTrue($user->hasExceptions());
		$user->reset();
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testGetUserRole($user)
	{
		// user 1 role 1 record
		$fromDb = $user->get(self::TEST_USER1, self::TEST_ROLE1);
		
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['user_id'], self::TEST_USER1);
		$this->assertEquals($fromDb['role_id'], self::TEST_ROLE1);
		
		// user 1 role 3 record
		$fromDb = $user->get(self::TEST_USER1, self::TEST_ROLE3);
		
		$this->assertType('array', $fromDb);
		$this->assertEquals($fromDb['user_id'], self::TEST_USER1);
		$this->assertEquals($fromDb['role_id'], self::TEST_ROLE3);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testGetUserRoles($user)
	{
		// user 1 has 3 roles
		$userRoles = $user->getUserRoles(self::TEST_USER1);
		$this->assertType('array', $userRoles);
		$this->assertEquals(3, count($userRoles));
		foreach ($userRoles as $ur)
		{
			$this->assertEquals($ur['user_id'], self::TEST_USER1);
		}
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testGetNonExisting($user)
	{
		// should found no data, not unless it exists in production
		$userRoles = $user->get(999, 999);
		$this->assertFalse($userRoles);
		$this->assertTrue($user->hasMessages());
		$user->reset();
		
		$userRoles = $user->getUserRoles(999);
		$this->assertFalse($userRoles);
		$this->assertTrue($user->hasMessages());
		$user->reset();
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testUpdateUserRole($user)
	{
		// update user 2 who has role 3, change role to 2
		$data = array('role_id' => self::TEST_ROLE2);
		$result = $user->save(self::TEST_USER2, self::TEST_ROLE3, $data);
		
		// should affect one record
		$this->assertEquals(1, $result);
		
		// get it
		$fromDb = $user->get(self::TEST_USER2, $data['role_id']);
		$this->assertType('array', $fromDb);
		
		// compare
		$this->assertEquals($fromDb['user_id'], self::TEST_USER2);
		$this->assertEquals($fromDb['role_id'], $data['role_id']);
		
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testUpdateNonExisting($user)
	{
		$data = array('role_id' => 999);
		$result = $user->save(999, 999, $data);
		
		$this->assertEquals(0, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
	}
	
	/**
	 * @depends testAddThemAll
	 */
	public function testDeleteAll($user)
	{
		// only user2 is updated to role 2 so the others is same as declared by default
		// delete user 2 roles one by one which has 2 roles
		$result = $user->delete(
			$this->_testData[self::TEST_DATA5]['user_id'],
			$this->_testData[self::TEST_DATA5]['role_id']
		);
		// one record deleted
		$this->assertEquals(1, $result);
		
		$result = $user->delete(
			$this->_testData[self::TEST_DATA6]['user_id'],
			$this->_testData[self::TEST_DATA6]['role_id']
		);
		// one record deleted
		$this->assertEquals(1, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// now lets delete user 1 who has 3 roles, delete them all
		$result = $user->delete(self::TEST_USER1);
		
		// 3 records deleted
		$this->assertEquals(3, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		// delete user 2
		$result = $user->delete(self::TEST_USER2, self::TEST_ROLE2);
		// one record deleted
		$this->assertEquals(1, $result);
		$this->assertFalse($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		
		return $user;
	}
	
	/**
	 * @depends testDeleteAll
	 */
	public function testGetAfterDelete($user)
	{
		// already deleted
		$fromDb = $user->get(
			$this->_testData[self::TEST_DATA5]['user_id'],
			$this->_testData[self::TEST_DATA5]['role_id']
		);
		$this->assertFalse($fromDb);
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		$user->reset();
		
		// already deleted
		$fromDb = $user->get(
			$this->_testData[self::TEST_DATA6]['user_id'],
			$this->_testData[self::TEST_DATA6]['role_id']
		);
		$this->assertFalse($fromDb);
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		$user->reset();
		
		// already deleted
		$userRoles = $user->getUserRoles(self::TEST_USER1);
		$this->assertFalse($userRoles);
		$this->assertTrue($user->hasMessages());
		$this->assertFalse($user->hasExceptions());
		$user->reset();
	}
}