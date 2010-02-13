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
 * @package     ControllerTestCase
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

require_once 'TestHelper.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    public $application;

    public function setUp()
    {
        $this->application = new Zend_Application(
            APPLICATION_ENV,
            APPLICATION_PATH . '/configs/application.ini'
        );

        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }

    public function appBootstrap()
    {
        $this->application->bootstrap();
    }
}