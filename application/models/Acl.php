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
 * @package     Application_Model_Acl
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

class Application_Model_Acl extends Zend_Acl
{
	/**
	 * Role data source
	 * Usually a Application_Model_RoleMapper
	 */
	protected static $_roleDataSource = null;
	
	/**
	 * Resource data source
	 * Usually a Application_Model_ResourceMapper
	 */
	protected static $_resourceDataSource = null;
	
	/**
	 * Privilege data source
	 * Usually a Application_Model_PrivilegeMapper
	 */
	protected static $_privilegeDataSource = null;
	
	/**
	 * Temporary placeholder for roles
	 * and resources raw data
	 */
	protected $_temp = array(
		'roles' 	=> array(),
		'resources' => array()
	);
	
	/**
	 * Initializes data source
	 */
	public function __construct()
	{
		if (self::$_roleDataSource === null)
		{
			self::$_roleDataSource = new Application_Model_RoleMapper;
		}
		
		if (self::$_resourceDataSource === null)
		{
			self::$_resourceDataSource = new Application_Model_ResourceMapper;
		}
		
		if (self::$_privilegeDataSource === null)
		{
			self::$_privilegeDataSource = new Application_Model_PrivilegeMapper;
		}
	}
	
	/**
	 * Sets the role data source
	 *
	 * @param mixed $dataSource
	 * @return void
	 */
	public static function setRoleDataSource($dataSource)
	{
		self::$_roleDataSource = $dataSource;
	}
	
	/**
	 * Sets the resource data source
	 *
	 * @param mixed $dataSource
	 * @return void
	 */
	public static function setResourceDataSource($dataSource)
	{
		self::$_resourceDataSource = $dataSource;
	}
	
	/**
	 * Sets the privilege data source
	 *
	 * @param mixed $dataSource
	 * @return void
	 */
	public static function setPrivilegeDataSource($dataSource)
	{
		self::$_privilegeDataSource = $dataSource;
	}
	
	/**
	 * Initializes the whole acl
	 * and loads roles, resources and privileges (rules)
	 *
	 * @return $this Provides fluent interface
	 */
	public function init()
	{
		$this->_loadResources();
		$this->_loadRoles();
		$this->_loadPrivileges();
		
		// cleanup
		$this->_temp['roles'] = array();
		$this->_temp['resources'] = array();
		
		return $this;
	}
	
	/**
	 * Loads all resources
	 * 
	 * @return $this Provides fluent interface
	 */
	protected function _loadResources()
	{
		$resources = self::$_resourceDataSource->getAll();
		if (!empty($resources) && is_array($resources))
		{
			foreach ($resources as $resource)
			{
				$this->addResource(new Zend_Acl_Resource($resource['resource_name']));
				// cache it
				$this->_temp['resources'][$resource['resource_id']] = $resource['resource_name'];
			}
		}
		return $this;
	}
	
	/**
	 * Loads all roles
	 *
	 * @return $this Provides fluent interface
	 */
	protected function _loadRoles()
	{
		$roles = self::$_roleDataSource->getAll();
		
		if (!empty($roles) && is_array($roles))
		{
			foreach ($roles as $role)
			{
				$this->addRole(new Zend_Acl_Role($role['role_name']));
				// cache it
				$this->_temp['roles'][$role['role_id']] = $role['role_name'];
			}
		}
		return $this;
	}
	
	/**
	 * Loads all privileges
	 * Should be called only when roles and resources are loaded
	 *
	 * @return $this Provides fluent interface
	 */
	protected function _loadPrivileges()
	{
		$privileges = self::$_privilegeDataSource->getAll();
		if (!empty($privileges) && is_array($privileges))
		{
			foreach ($privileges as $priv)
			{
				$action = ($priv['allow']) ? 'allow' : 'deny';
				
				$resourceId = null;
				if (isset($this->_temp['resources'][$priv['resource_id']]) && $this->_temp['resources'][$priv['resource_id']])
				{
					$resourceId = $this->_temp['resources'][$priv['resource_id']];
				}
				$privName = (!empty($priv['privilege_name'])) ? $priv['privilege_name'] : null;
				// get the name from cache based on ids
				if (isset($this->_temp['roles'][$priv['role_id']]))
				{
					$this->$action(
						$this->_temp['roles'][$priv['role_id']],
						$resourceId,
						$privName
					);
				}
			}
		}
		return $this;
	}
	
	public function isAllowed($role = null, $resource = null, $privilege= null)
	{
		// check first if role exists
		if (!$this->hasRole($role))
		{
			return false;
		}
		
		// check first if resource exists
		if (!$this->has($resource))
		{
			return false;
		}
		
		return parent::isAllowed($role, $resource, $privilege);
	}
}