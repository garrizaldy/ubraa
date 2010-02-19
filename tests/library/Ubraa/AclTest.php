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

class Ubraa_Acl_Test extends ControllerTestCase
{
	/**
	 * Test data
	 */
	const GUEST = 120;
	const MEMBER = 121;
	const ADMIN = 122;
	
	const ARTICLE = 220;
	const COMMENT = 221;
	const GALLERY = 222;
	
	const ARTICLE_VIEW = 'ARTICLE_VIEW';
	const ARTICLE_ADD = 'ARTICLE_ADD';
	const ARTICLE_EDIT = 'ARTICLE_EDIT';
	const ARTICLE_DELETE = 'ARTICLE_DELETE';
	
	const COMMENT_ADD = 'COMMENT_ADD';
	const COMMENT_EDIT = 'COMMENT_EDIT';
	const COMMENT_DELETE = 'COMMENT_DELETE';
	
	const GALLERY_VIEW = 'GALLERY_VIEW';
	const GALLERY_ADD = 'GALLERY_ADD';
	const GALLERY_EDIT = 'GALLERY_EDIT';
	const GALLERY_DELETE = 'GALLERY_DELETE';
	
	const ALLOW = 1;
	const DENY = 0;
	
	protected $_testRoleData = array(
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
	
	protected $_testResourceData = array(
		self::ARTICLE => array(
			'resource_id' => 220,
			'resource_name' => 'article',
			'resource_description' => 'Article resource'
		),
		self::COMMENT => array(
			'resource_id' => 221,
			'resource_name' =>'comment',
			'resource_description' => 'Comments resource'
		),
		self::GALLERY => array(
			'resource_id' => 222,
			'resource_name' => 'gallery',
			'resource_description' => 'Gallery resource'
		)
	);
	
	protected $_testPrivilegeData = array(
		// viewing article privileges
		array(
			'role_id'			=> self::GUEST,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow guest to view article'
		),
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to view article'
		),
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to view article'
		),
		// we don't need to deny guest for ARTICLE_ADD, its automatic
		// not listed means denied
		
		// adding article privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to add article'
		),
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to add article'
		),
		// editting article privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to edit article'
		),
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to edit article'
		),
		// deleting articles privileges
		// only admin can delete
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to edit article'
		),
		// viewing gallery privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to view gallery'
		),
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to view gallery'
		),
		// adding gallery privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to add gallery'
		),
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to add gallery'
		),
		// editting gallery privileges 
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to edit gallery'
		),
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to edit gallery'
		),
		// deleting gallery privileges
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_DELETE,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow admin to delete gallery'
		)
	);	
	
	public function testObject()
	{
		$acl = new Ubraa_Acl;
		$this->assertType('Ubraa_Acl', $acl);
		
		return $acl;
	}
	
	/**
	 * @depends testObject
	 * @param Ubraa_Acl $acl
	 */
	public function testInit(Ubraa_Acl $acl)
	{
		$roleDataSource = $this->getMock(
			'Ubraa_Acl_Model_RoleMapper', array('getAll')
		);
		$roleDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testRoleData));
				   
		$resourceDataSource = $this->getMock(
			'Ubraa_Acl_Model_ResourceMapper', array('getAll')
		);
		$resourceDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testResourceData)
		);
			
		$privilegeDataSource = $this->getMock(
			'Ubraa_Acl_Model_PrivilegeMapper', array('getAll')
		);
		$privilegeDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testPrivilegeData)
		);
			
		Ubraa_Acl::setRoleDataSource($roleDataSource);
		Ubraa_Acl::setResourceDataSource($resourceDataSource);
		Ubraa_Acl::setPrivilegeDataSource($privilegeDataSource);
		
		$this->assertType('Ubraa_Acl', $acl->init());
		
		return $acl;
	}
	
	/**
	 * @depends testInit
	 * @param Ubraa_Acl $acl
	 */
	public function testRules(Ubraa_Acl $acl)
	{
		// test guest for viewing atricles
		$this->assertTrue($acl->isAllowed('guest', 'article', self::ARTICLE_VIEW));
		// guest can't add article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_ADD));
		// guest can't edit article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_EDIT));
		
		// more to go, got to go home
	}
}