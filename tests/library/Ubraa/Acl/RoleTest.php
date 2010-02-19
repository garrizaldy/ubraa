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
 * @package     Ubraa_AclTest
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

require_once 'ControllerTestCase.php';

/**
 * Privilege data mapper test case
 */
class Ubraa_Acl_RoleTest extends ControllerTestCase
{
	/**
	 * Test data
	 */
	const GUEST = 120;
	const MEMBER = 121;
	const ADMIN = 122;
	
	protected $_testData = array(
		self::GUEST => array(
			'role_id' => 120,
			'role_name' => 'guest',
			'role_description' => 'Guest users / not logged in'
		),
		self::MEMBER => array(
			'role_id' => 121,
			'role_name' =>'member',
			'role_description' => 'Logged in user'
		),
		self::ADMIN => array(
			'role_id' => 122,
			'role_name' => 'admin',
			'role_description' => 'Admin user'
		)
	);
	
	public function testObject()
	{
		$role = new Ubraa_Acl_Role('test');
		$this->assertType('Ubraa_Acl_Role', $role);
	}
	
	public function testSetDataSource()
	{
		$dataSource = $this->getMock(
			'Ubraa_Acl_Model_RoleMapper',
			array('add', 'get', 'getAll', 'save', 'delete')
		);

		// setup data source mock methods
		// these methods must be called otherwise expectation error will occur
		$dataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testData));

		Ubraa_Acl_Role::setDataSource($dataSource);
		$this->assertTrue(Ubraa_Acl_Role::hasDataSource());
		
		$roleData = Ubraa_Acl_Role::getAllRoles();
		$this->assertType('array', $roleData);
		
		return $roleData;
	}
	
	/**
	 * @depends testSetDataSource
	 * @param array $roleData
	 */
	public function testRoleObject(array $roleData)
	{
		foreach ($roleData as $key => $row)
		{
			$role = new Ubraa_Acl_Role($row['role_name']);
			$this->assertType('Ubraa_Acl_Role', $role);
			$this->assertEquals((string)$role, $roleData[$key]['role_name']);
		}
	}
}