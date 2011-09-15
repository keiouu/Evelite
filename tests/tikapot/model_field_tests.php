<?php
/*
 * Tikapot
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
global $home_dir;
require_once($home_dir . "lib/simpletest/autorun.php");
require_once($home_dir . "framework/model.php");
require_once($home_dir . "framework/model_fields/init.php");

class ModelFieldTest extends UnitTestCase {
	function testCharField() {
		$field = new CharField("a", 5);
		$this->assertEqual($field->get_value(), "a");
		$field->set_value("abcde");
		$this->assertTrue($field->validate());
		$field->set_value("abcdef");
		$this->assertFalse($field->validate());
	}
	
	function testIntField() {
		$field = new IntField(1, 9, False, "");
		$this->assertEqual($field->get_value(), 1);
		$field->set_value(123456789);
		$this->assertTrue($field->validate());
		$field->set_value(1234567891);
		$this->assertFalse($field->validate());
		$field->set_value(12345.34);
		$this->assertFalse($field->validate());
		$field->set_value("NotanInt");
		$this->assertFalse($field->validate());
		$field->set_value("");
		$this->assertTrue($field->validate());
	}
	
	function testBoolField() {
		$field = new BooleanField(False);
		$this->assertTrue($field->validate());
		$this->assertFalse($field->get_value());
		$field->set_value(True);
		$this->assertTrue($field->get_value());
		$this->assertTrue($field->validate());
		$field->set_value("3");
		$this->assertFalse($field->validate());
		$field->set_value("true");
		$this->assertFalse($field->validate()); // Only accept true bools :)
	}
	
	function testDateField() {
		$field = new DateField();
		$this->assertTrue($field->validate());
		$field->set_value("1999-01-21");
		$this->assertTrue($field->validate());
		$field->set_value("199-01-21");
		$this->assertFalse($field->validate());
		$field->set_value("abcd-ef-gh");
		$this->assertFalse($field->validate());
		$field->set_value(date("Y-m-d"));
		$this->assertTrue($field->validate());
	}
	
	function testDateTimeField() {
		$field = new DateTimeField();
		$this->assertTrue($field->validate());
		$field->set_value("1999-01-21 24:54:21");
		$this->assertTrue($field->validate());
		$field->set_value("1999-01-21 24/54/21");
		$this->assertFalse($field->validate());
		$field->set_value("199-01-21 12:3:2");
		$this->assertFalse($field->validate());
		$field->set_value("abcd-ef-gh ad:fe:gt");
		$this->assertFalse($field->validate());
		$field->set_value(date("Y-m-d h:m:s"));
		$this->assertTrue($field->validate());
	}
}
?>

