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
		
		$mockMapper = $this->getMock(
			'Application_Model_UserMapper',
			array('add', 'save', 'getById', 'getByUsername', 'delete')
		);
		
		$user->setMapper($mockMapper);
		
		$this->assertTrue((boolean)$user->getMapper());
		
		return $user;
	}
	
	/**
	 * @depends testObject
	 */
	public function testRegister($user)
	{
		$data = $this->_testData[self::TEST_DATA1];
		
		$result = $user->register($data);
		
		$this->assertTrue($result);
	}
}