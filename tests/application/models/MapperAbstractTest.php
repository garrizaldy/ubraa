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

require_once 'ControllerTestCase.php';

/**
 * Data mapper abstract test case.
 */
class Application_Model_MapperAbstractTest extends ControllerTestCase
{	
	public function testObject()
	{
		$mapper = $this->getMock(
					'Application_Model_MapperAbstract',
					array(
						'insert',
						'fetchRow',
						'fetchAll',
						'update',
						'deleteRecord'
						)
					);
		$this->assertType('Application_Model_MapperAbstract', $mapper);
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		return $mapper;
	}
	
	/**
	 * @depends testObject
	 */
	public function testAddMessageNoException($mapper)
	{
		$message = 'sampleMessageFromUnitTest';
		$mapper->addMessage($message);
		$this->assertTrue($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
		
		$firstMessage = $mapper->getMessages();
		$this->assertEquals(1, count($firstMessage));
		
		$this->assertType('array', $firstMessage);
		$firstMessage = reset($firstMessage);
		$this->assertType('string', $firstMessage);
		
		$this->assertEquals($firstMessage, $message);
		
		// clear
		$mapper->clearMessages();
	}
	
	/**
	 * @depends testObject
	 */
	public function testAddMessageWithException($mapper)
	{
		$message = 'testMessage';
		$exception = new Exception('Test exception');
		$mapper->addMessage($message, $exception);
		
		$this->assertTrue($mapper->hasMessages());
		$this->assertTrue($mapper->hasExceptions());
		
		$this->assertEquals(1, count($mapper->getMessages()));
		$this->assertEquals(1, count($mapper->getExceptions()));
		
		$messages = $mapper->getMessages();
		$exceptions = $mapper->getExceptions();
		
		$this->assertEquals($message, $messages[0]);
		$this->assertEquals($exception, $exceptions[0]);
		
		$mapper->clearMessages();
		$this->assertFalse($mapper->hasMessages());
		$this->assertTrue($mapper->hasExceptions());
		
		$mapper->clearExceptions();
		$this->assertFalse($mapper->hasExceptions());
	}
	
	/**
	 * @depends testObject
	 */
	public function testMoreMessagesAndExceptions($mapper)
	{
		$msg1 = 'test1';
		$msg2 = 'test2';
		$e1 = new Exception('test 1');
		$e2 = new Exception('test 2');
		
		$mapper->addMessage($msg1, $e1)
				->addMessage($msg2, $e2);
				
		$this->assertTrue($mapper->hasMessages());
		$this->assertTrue($mapper->hasExceptions());
		
		$this->assertEquals(2, count($mapper->getMessages()));
		$this->assertEquals(2, count($mapper->getExceptions()));
		
		$mapper->clearMessages()->clearExceptions();
		
		$this->assertFalse($mapper->hasMessages());
		$this->assertFalse($mapper->hasExceptions());
	}
}