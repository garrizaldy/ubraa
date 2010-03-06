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
     * Singleton instance
     *
     * @var Application_Model_Acl
     */
    protected static $_instance = null;
	
	/**
	 * Role data source
	 *
	 * @var Application_Model_RoleMapper
	 */
	protected $_roleDataSource = null;
	
	/**
	 * Resource data source
	 * 
	 * @var Application_Model_ResourceMapper
	 */
	protected $_resourceDataSource = null;
	
	/**
	 * Privilege data source
	 * 
	 * @var Application_Model_PrivilegeMapper
	 */
	protected $_privilegeDataSource = null;
	
	/**
	 * Temporary placeholder for roles
	 * and resources raw data
	 */
	protected $_temp = array(
		'roles' 	=> array(),
		'resources' => array()
	);
	
    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * Returns an instance of Application_Model_Acl
     *
     * Singleton pattern implementation
     *
     * @return Application_Model_Acl Provides a fluent interface
     */
    public static function getInstance()
    {
        if (self::$_instance === null)
		{
            self::$_instance = new self();
        }

        return self::$_instance;
    }
	
	/**
	 * Sets the role data source
	 *
	 * @param Application_Model_RoleMapper $dataSource
	 * @return $this Provides fluent interface
	 */
	public function setRoleDataSource(Application_Model_MapperAbstract $dataSource)
	{
		$this->_roleDataSource = $dataSource;
		
		return $this;
	}
	
	/**
	 * Returns the role data source object
	 *
	 * @return Application_Model_RoleMapper
	 */
	public function getRoleDataSource()
	{
		if ($this->_roleDataSource === null)
		{
			$this->_roleDataSource = new Application_Model_RoleMapper;
		}
		
		return $this->_roleDataSource;
	}
	
	/**
	 * Sets the resource data source
	 *
	 * @param Application_Model_ResourceMapper $dataSource
	 * @return $this Provides fluent interface
	 */
	public function setResourceDataSource(Application_Model_MapperAbstract $dataSource)
	{
		$this->_resourceDataSource = $dataSource;
		
		return $this;
	}
	
	/**
	 * Returns the resource data source
	 *
	 * @return Application_Model_ResourceMapper
	 */
	public function getResourceDataSource()
	{
		if ($this->_resourceDataSource === null)
		{
			$this->_resourceDataSource = new Application_Model_ResourceMapper;
		}
		
		return $this->_resourceDataSource;
	}
	
	/**
	 * Sets the privilege data source
	 *
	 * @param Application_Model_PrivilegeMapper $dataSource
	 * @return $this Provides fluent interface
	 */
	public function setPrivilegeDataSource(Application_Model_MapperAbstract $dataSource)
	{
		$this->_privilegeDataSource = $dataSource;
		
		return $this;
	}
	
	/**
	 * Returns the privilege data source
	 *
	 * @return Application_Model_PrivilegeMapper
	 */
	public function getPrivilegeDataSource()
	{
		if ($this->_privilegeDataSource === null)
		{
			$this->_privilegeDataSource = new Application_Model_PrivilegeMapper;
		}
		
		return $this->_privilegeDataSource;
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
		$resources = $this->getResourceDataSource()->getAll();
		
		if (!empty($resources) && is_array($resources))
		{
			foreach ($resources as $resource)
			{
				$parent = null;
				if (isset($this->_temp['resources'][$resource['parent_resource']]))
				{
					$parent = $this->_temp['resources'][$resource['parent_resource']];
				}
				$this->addResource(new Zend_Acl_Resource($resource['resource_name']), $parent);
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
		$roles = $this->getRoleDataSource()->getAll();
		
		if (!empty($roles) && is_array($roles))
		{
			foreach ($roles as $role)
			{
				$parent = null;
				if (isset($this->_temp['roles'][$role['parent_role']]))
				{
					$parent = $this->_temp['roles'][$role['parent_role']];
				}
				$this->addRole(new Zend_Acl_Role($role['role_name']), $parent);
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
		$privileges = $this->getPrivilegeDataSource()->getAll();
		
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