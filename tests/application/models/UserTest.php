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

class Application_Model_UserTest extends ControllerTestCase
{
	const TEST_DATA1 = 900;
	const TEST_DATA2 = 901;
	const TEST_DATA3 = 903;
	
	protected $_testData = array(
		self::TEST_DATA1 => array(
			'user_id' 	=> self::TEST_DATA1,
			'username'	=> 'user1',
			'password'	=> 'pass123',
			'email'		=> 'user@gmail.com'
		),
		self::TEST_DATA2 => array(
			'user_id'	=> self::TEST_DATA2,
			'username'	=> 'user2',
			'password'	=> 'pass456',
			'email'		=> 'modz@zend.com'
		),
		self::TEST_DATA3 => array(
			'user_id'	=> self::TEST_DATA3,
			'username'	=> 'admin',
			'password'	=> 'L33t$p3@k',
			'email'		=> 'root@local.host.com'
		)
	);
	
	public function testObject()
	{
		$user = new Application_Model_User;
		$this->assertType('Application_Model_User', $user);
	}
	
	/**
	 * @depends testObject
	 */
	public function testUserMapper()
	{
		$user = new Application_Model_User;
		
		$this->assertType('Application_Model_UserMapper', $user->getUserMapper());
		
		$mock = $this->getMock('Application_Model_UserMapper');
		$user->setUserMapper($mock);
		
		$this->assertEquals($mock, $user->getUserMapper());
	}
	
	/**
	 * @depends testObject
	 */
	public function testUserRoleMapper()
	{
		$user = new Application_Model_User;
		
		$this->assertType('Application_Model_UserRoleMapper', $user->getUserRoleMapper());
		
		$mock = $this->getMock('Application_Model_UserRoleMapper');
		$user->setUserRoleMapper($mock);
		
		$this->assertEquals($mock, $user->getUserRoleMapper());
	}
	
	/**
	 * @depends testObject
	 */
	public function testDefaultRoles()
	{
		$roles = array(1, 2, 3);
		
		$user = new Application_Model_User;
		$user->setDefaultRoles($roles);
		
		$this->assertEquals($roles, $user->getDefaultRoles());
	}
	
	/**
	 * @depends testObject
	 */
	public function testRegister()
	{
		$user = new Application_Model_User;
		
		// fake data mapper for user
		$mockUserMapper = $this->getMock(
			'Application_Model_UserMapper',
			array('add')
		);
		$mockUserMapper->expects($this->once())
			->method('add')
			->will($this->returnValue(self::TEST_DATA1));
			
		// fake data mapper for user role
		$mockUserRoleMapper = $this->getMock(
			'Application_Model_UserRoleMapper',
			array('add')
		);
		$mockUserRoleMapper->expects($this->any())
			->method('add')
			->will($this->returnValue(array(self::TEST_DATA1, 1)));
		
		// set data mappers
		$user->setUserMapper($mockUserMapper)
			->setUserRoleMapper($mockUserRoleMapper);
			
		// register now
		$userId = $user->register($this->_testData[self::TEST_DATA1]);
		$this->assertEquals(self::TEST_DATA1, $userId);
		
		// should have no messages/exceptions of any kind
		$this->assertFalse($user->getUserMapper()->hasExceptions());
		$this->assertFalse($user->getUserMapper()->hasMessages());
		$this->assertFalse($user->getUserRoleMapper()->hasExceptions());
		$this->assertFalse($user->getUserRoleMapper()->hasMessages());
	}
}