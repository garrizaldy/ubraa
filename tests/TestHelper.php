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
 * @package     TestHelper
 * @copyright   Copyright (c) 2007-2010 PHP User Group Philippines Inc. (http://www.phpugph.com)
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache Software License 2.0
 * @version     $Id:$
 */

/**
 * Start output buffering
 */
ob_start();

/**
 * Set error reporting to the level to which code must comply.
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Set default timezone
 */
date_default_timezone_set('Asia/Manila');

define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', BASE_PATH . '/application');

// Include path
set_include_path(
    '.'
    . PATH_SEPARATOR . BASE_PATH . '/application/controllers'
    . PATH_SEPARATOR . BASE_PATH . '/application/models'
    . PATH_SEPARATOR . BASE_PATH . '/library'
    . PATH_SEPARATOR . BASE_PATH . '/tests'
    . PATH_SEPARATOR . get_include_path()
);

// Define application environment
define('APPLICATION_ENV', 'testing');