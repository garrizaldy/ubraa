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
	protected $_mapper;
	
	/**
	 * Sets the data mapper
	 *
	 * @param mixed $mapper
	 * @return $this Provides fluent interface
	 */
	public function setMapper(Application_Model_MapperAbstract $mapper)
	{
		$this->_mapper = $mapper;
		
		return $this;
	}
	
	/**
	 * Returns the data mapper
	 *
	 * @return mixed $mapper
	 */
	public function getMapper()
	{
		return $this->_mapper;
	}
	
	/**
	 * Initializes the model
	 */
	public function __construct()
	{
		$this->setMapper(new Application_Model_UserMapper);
	}
	
	/**
	 * Registers a new user
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function register(array $data)
	{
		$mapper = $this->getMapper();
		$mapper->add($data);
		
		return true;
	}
}