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
 * @package     Ubraa_Acl
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

class Ubraa_Acl_Role extends Zend_Acl_Role
{
	/**
	 * Data source / data mapper for roles
	 * @var mixed object
	 */
	protected static $_dataSource = null;
	
    /**
     * Sets the Role identifier
     * Also sets the data source
     *
     * @param  string $roleId
     * @return void
     */
    public function __construct($roleId)
    {
		if (self::$_dataSource === null)
		{
			self::$_dataSource = new Ubraa_Acl_Model_RoleMapper;
		}
		
		parent::__construct($roleId);
    }
	
	/**
	 * Sets the data source for roles
	 *
	 * @param mixed $dataSource
	 * @return void
	 */
	public static function setDataSource($dataSource)
	{
		self::$_dataSource = $dataSource;
	}
	
	/**
	 * clearDataSource()
	 *
	 * @return void
	 */
	public static function clearDataSource()
	{
		self::$_dataSource = null;
	}
	
	/**
	 * Returns true if and only if data source is set
	 *
	 * @return boolean
	 */
	public static function hasDataSource()
	{
		return (boolean)self::$_dataSource;
	}
	
	/**
	 * Returns all resources from data source
	 *
	 * @return array
	 */
	public static function getAllRoles()
	{
		return self::$_dataSource->getAll();
	}
}