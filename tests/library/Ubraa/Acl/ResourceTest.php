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
class Ubraa_Acl_ResourceTest extends ControllerTestCase
{
	/**
	 * Test data
	 */
	const ARTICLE = 120;
	const COMMENT = 121;
	const GALLERY = 122;
	
	protected $_testData = array(
		self::ARTICLE => array(
			'resource_id' => 120,
			'resource_name' => 'article',
			'resource_description' => 'Article resource'
		),
		self::COMMENT => array(
			'resource_id' => 121,
			'resource_name' =>'comment',
			'resource_description' => 'Comments resource'
		),
		self::GALLERY => array(
			'resource_id' => 122,
			'resource_name' => 'gallery',
			'resource_description' => 'Gallery resource'
		)
	);
	
	public function testObject()
	{
		$resource = new Ubraa_Acl_Resource('test');
		$this->assertType('Ubraa_Acl_Resource', $resource);
	}
	
	public function testSetDataSource()
	{
		$dataSource = $this->getMock(
			'Ubraa_Acl_Model_ResourceMapper',
			array('add', 'get', 'getAll', 'save', 'delete')
		);

		// setup data source mock methods
		// these methods must be called otherwise expectation error will occur
		$dataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testData));

		Ubraa_Acl_Resource::setDataSource($dataSource);
		$this->assertTrue(Ubraa_Acl_Resource::hasDataSource());
		$resourceData = Ubraa_Acl_Resource::getAllResources();
		
		$this->assertType('array', $resourceData);
		
		return $resourceData;
	}
	
	/**
	 * @depends testSetDataSource
	 * @param array $resourceData
	 */
	public function testResourceObject(array $resourceData)
	{
		foreach ($resourceData as $key => $row)
		{
			$resource = new Ubraa_Acl_Resource($row['resource_name']);
			$this->assertType('Ubraa_Acl_Resource', $resource);
			$this->assertEquals((string)$resource, $resourceData[$key]['resource_name']);
		}
	}
}