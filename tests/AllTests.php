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
 * @package     AllTests
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

require_once './TestHelper.php';

require_once './application/AllTests.php';

class AllTests
{
	public static function main()
    {
    	$parameters = array();
    	
    	if (TESTS_GENERATE_REPORT && extension_loaded('xdebug'))
        {
            $parameters['reportDirectory'] = TESTS_GENERATE_REPORT_TARGET;
        }
        
        PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
    }
    
	public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Ubraa');
        $suite->addTest(Controllers_AllTests::suite());
        return $suite;
    }	
}