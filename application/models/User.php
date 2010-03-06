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
 * @package     Application_Model
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

class Application_Model_User
{
	/**
	 * @var Application_Model_UserMapper
	 */
	protected $_userMapper;
	
	/**
	 * @var Application_Model_UserRoleMapper
	 */
	protected $_userRoleMapper;
	
	/**
	 * Default roles for newly registered users
	 * Contains role ids
	 * 
	 * @var array
	 */
	protected $_defaultRoles;
	
	/**
	 * @var string
	 */
	private $_salt = 'ea559da0994340048c3e13586d1fb760';
	
	/**
	 * Sets the data mapper
	 *
	 * @param Application_Model_UserMapper $mapper
	 * @return $this Provides fluent interface
	 */
	public function setUserMapper(Application_Model_MapperAbstract $mapper)
	{
		$this->_userMapper = $mapper;
		
		return $this;
	}
	
	/**
	 * Returns the user data mapper
	 *
	 * @return Application_Model_UserMapper
	 */
	public function getUserMapper()
	{
		if ($this->_userMapper === null)
		{
			$this->_userMapper = new Application_Model_UserMapper;
		}
		
		return $this->_userMapper;
	}
	
	/**
	 * Sets the user role data mapper
	 *
	 * @param Application_Model_UserRoleMapper
	 * @return $this Provides fluent interface
	 */
	public function setUserRoleMapper(Application_Model_MapperAbstract $mapper)
	{
		$this->_userRoleMapper = $mapper;
		
		return $this;
	}
	
	/**
	 * Returns the user role data mapper
	 *
	 * @return Application_Model_UserRoleMapper
	 */
	public function getUserRoleMapper()
	{
		if ($this->_userRoleMapper === null)
		{
			$this->_userRoleMapper = new Application_Model_UserRoleMapper;
		}
		
		return $this->_userRoleMapper;
	}
	
	/**
	 * Sets the default roles for newly registered users
	 *
	 * @param array $roles Contains role ids
	 * @return $this Provides fluent interface
	 */
	public function setDefaultRoles(array $roles)
	{
		$this->_defaultRoles = $roles;
		
		return $this;
	}
	
	/**
	 * Returns the default roles for newly registered users
	 *
	 * @todo Add mechanism to retrieve default roles from a certain source
	 * The source can be a config file or from database, or whatever
	 *
	 * @return array
	 */
	public function getDefaultRoles()
	{
		if ($this->_defaultRoles === null)
		{
			// default role, restricted user, hard coded as of now
			$this->_defaultRoles = array(2);
		}
		
		return $this->_defaultRoles;
	}
	
	/**
	 * Returns a salted-hashed value of a string
	 * Only applicable for user password (no more)
	 * Salt is not shared outside the class
	 *
	 * @param string $value
	 * @return string
	 */
	protected function _hash($value)
	{
		return sha1($this->_salt . $value);
	}
	
	/**
	 * Registers a new user
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function register(array $data)
	{
		// hash password
		$data['password'] = $this->_hash($data['password']);
		
		$userId = $this->getUserMapper()->add($data);
		if ($userId)
		{
			// set default roles
			$defaultRoles = $this->getDefaultRoles();
			$userRoleMapper = $this->getUserRoleMapper();
			foreach ($defaultRoles as $role)
			{
				$userRoleMapper->add(array($userId, $role));
			}
		}
		
		return $userId;
	}
}