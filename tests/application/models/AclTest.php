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
 * @package     Application_Model_AclTest
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

require_once 'ControllerTestCase.php';

class Application_Model_AclTest extends ControllerTestCase
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
		// allow all privileges to admin for all resources
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> 0,
			'privilege_name'	=> '',
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow all privileges to admin for article'
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
		// editting article privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to edit article'
		),
		// comment privileges for guests, member and admin
		// member has add privilege for comment (add only and no more)
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::COMMENT,
			'privilege_name'	=> self::COMMENT_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to add comment'
		),
		// viewing gallery privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to view gallery'
		),
		// adding gallery privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to add gallery'
		),
		// editting gallery privileges 
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to edit gallery'
		)
	);	
	
	public function testObject()
	{
		$acl = new Application_Model_Acl;
		$this->assertType('Application_Model_Acl', $acl);
		
		return $acl;
	}
	
	/**
	 * @depends testObject
	 * @param Application_Model_Acl $acl
	 */
	public function testInit(Application_Model_Acl $acl)
	{
		$roleDataSource = $this->getMock(
			'Application_Model_RoleMapper', array('getAll')
		);
		$roleDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testRoleData));
				   
		$resourceDataSource = $this->getMock(
			'Application_Model_ResourceMapper', array('getAll')
		);
		$resourceDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testResourceData)
		);
			
		$privilegeDataSource = $this->getMock(
			'Application_Model_PrivilegeMapper', array('getAll')
		);
		$privilegeDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testPrivilegeData)
		);
			
		Application_Model_Acl::setRoleDataSource($roleDataSource);
		Application_Model_Acl::setResourceDataSource($resourceDataSource);
		Application_Model_Acl::setPrivilegeDataSource($privilegeDataSource);
		
		$this->assertType('Application_Model_Acl', $acl->init());
		
		return $acl;
	}
	
	/**
	 * @depends testInit
	 * @param Application_Model_Acl $acl
	 */
	public function testRulesArticles(Application_Model_Acl $acl)
	{
		// test guest for viewing atricles
		// guest can only view articles
		$this->assertTrue($acl->isAllowed('guest', 'article', self::ARTICLE_VIEW));
		// guest can't add article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_ADD));
		// guest can't edit article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_EDIT));
		// guest can't delete article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_DELETE));
		
		// test member for article privileges
		// member can view articles
		$this->assertTrue($acl->isAllowed('member', 'article', self::ARTICLE_VIEW));
		// member can add article
		$this->assertTrue($acl->isAllowed('member', 'article', self::ARTICLE_ADD));
		// member can edit article
		$this->assertTrue($acl->isAllowed('member', 'article', self::ARTICLE_EDIT));
		// member can't delete article
		$this->assertFalse($acl->isAllowed('member', 'article', self::ARTICLE_DELETE));
		
		// test admin for article privileges
		// admin can view articles
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_VIEW));
		// admin can add article
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_ADD));
		// admin can edit article
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_EDIT));
		// admin can delete article
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_DELETE));
	}
	
	/**
	 * @depends testInit
	 * @param Application_Model_Acl
	 */
	public function testRulesComment(Application_Model_Acl $acl)
	{
		// test guest for comment privileges
		// has no privilege for add, edit and delete
		// view? well comment is included in article anyway
		$this->assertFalse($acl->isAllowed('guest', 'comment', self::COMMENT_ADD));
		// guest can't edit comment
		$this->assertFalse($acl->isAllowed('guest', 'comment', self::COMMENT_EDIT));
		// guest can't delete comment
		$this->assertFalse($acl->isAllowed('guest', 'comment', self::COMMENT_DELETE));
		
		// test member for comment privileges
		// member can add comment
		$this->assertTrue($acl->isAllowed('member', 'comment', self::COMMENT_ADD));
		// member can't edit comment
		$this->assertFalse($acl->isAllowed('member', 'comment', self::COMMENT_EDIT));
		// member can't delete comment
		$this->assertFalse($acl->isAllowed('member', 'comment', self::COMMENT_DELETE));
		
		// test admin for comment privileges
		// admin can add comment
		$this->assertTrue($acl->isAllowed('admin', 'comment', self::COMMENT_ADD));
		// admin can edit comment
		$this->assertTrue($acl->isAllowed('admin', 'comment', self::COMMENT_EDIT));
		// admin can delete comment
		$this->assertTrue($acl->isAllowed('admin', 'comment', self::COMMENT_DELETE));
	}
	
	/**
	 * @depends testInit
	 * @param Application_Model_Acl $acl
	 */
	public function testRulesGallery(Application_Model_Acl $acl)
	{
		// test guest privileges for gallery
		// guest can't view gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_VIEW));
		// guest can't add gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_ADD));
		// guest can't edit gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_EDIT));
		// guest can't delete gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_DELETE));
		
		// test member privileges for gallery
		// member can view gallery
		$this->assertTrue($acl->isAllowed('member', 'gallery', self::GALLERY_VIEW));
		// member can add gallery
		$this->assertTrue($acl->isAllowed('member', 'gallery', self::GALLERY_ADD));
		// member can edit gallery
		$this->assertTrue($acl->isAllowed('member', 'gallery', self::GALLERY_EDIT));
		// member can't delete gallery
		$this->assertFalse($acl->isAllowed('member', 'gallery', self::GALLERY_DELETE));
		
		// test admin privileges for gallery
		// admin can view gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_VIEW));
		// admin can add gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_ADD));
		// admin can edit gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_EDIT));
		// admin can delete gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_DELETE));
	}
	
	/**
	 * @depends testInit
	 * @param Application_Model_Acl $acl
	 */
	public function testNonExisting(Application_Model_Acl $acl)
	{
		$this->assertFalse($acl->isAllowed('unknown_user'));
		
		// test unknown resource
		$this->assertFalse($acl->isAllowed('guest', 'unknown_resource'));
		
		// test unknown privilege
		$this->assertFalse($acl->isAllowed('member', 'article', 'unknown_privilege'));
	}
}