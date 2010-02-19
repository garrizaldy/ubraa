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

class Ubraa_Acl_Resource extends Zend_Acl_Resource
{
	/**
	 * Data source / data mapper for resources
	 * @var mixed object
	 */
	protected static $_dataSource = null;
	
    /**
     * Sets the Resource identifier
     * Also sets the data source
     *
     * @param  string $resourceId
     * @return void
     */
    public function __construct($resourceId)
    {
		if (self::$_dataSource === null)
		{
			self::$_dataSource = new Ubraa_Acl_Model_ResourceMapper;
		}
		
		parent::__construct($resourceId);
    }
	
	/**
	 * Sets the data source for resources
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
	
	public static function getDataSource()
	{
		return self::$_dataSource;
	}
	
	/**
	 * Returns all resources from data source
	 *
	 * @return array
	 */
	public static function getAllResources()
	{
		return self::$_dataSource->getAll();
	}
}