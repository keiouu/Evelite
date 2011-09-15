<?php
/*
 * Evelite
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

global $home_dir;
require_once($home_dir . "lib/simpletest/autorun.php");

class AllTests extends TestSuite {
    function AllTests() {
    	global $home_dir;
        $this->TestSuite('All tests');
        $this->addFile($home_dir . 'tests/tikapot/session_test.php');
        $this->addFile($home_dir . 'tests/tikapot/database_test.php');
        $this->addFile($home_dir . 'tests/tikapot/timer_test.php');
        $this->addFile($home_dir . 'tests/tikapot/model_test.php');
        $this->addFile($home_dir . 'tests/tikapot/model_table_test.php');
        $this->addFile($home_dir . 'tests/tikapot/modelquery_test.php');
        $this->addFile($home_dir . 'tests/tikapot/model_field_tests.php');
    }
}
?>

