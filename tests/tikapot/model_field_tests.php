<?php
/*
 * Tikapot
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
require_once(home_dir . "lib/simpletest/autorun.php");
require_once(home_dir . "framework/model.php");
require_once(home_dir . "framework/model_fields/init.php");

class TestModelFK extends Model
{
	public function __construct() {
		parent::__construct();
		$this->add_field("val_prop", new CharField("", $max_length=7));
		$this->add_field("other_prop", new FKField("testModels.TestFKModel"));
	}
}

class ModelFieldTest extends UnitTestCase {
	function testFKField() {
		$field = new FKField("testModels.TestFKModel");
		$field->test_prop = "hello";
		$field->save();
		$this->assertTrue($field->validate());
		$model = new TestModelFK();
		$model->val_prop = "testme";
		$model->other_prop->test_prop = "testtoo";
		$this->assertEqual($model->other_prop->test_prop, "testtoo");
		$id = $model->save();
		$test = TestModelFK::get($id);
		$this->assertTrue($test);
		$this->assertEqual($test->other_prop->test_prop, $model->other_prop->test_prop);
	}
	
	function testCharField() {
		$field = new CharField(5, "a");
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
		
		$field = new DateField($auto_now_add = True, $auto_now = True);
		$field->pre_save(Null, True);
		$this->assertEqual($field->get_value(), date(DateField::$FORMAT, time()));
		$field = new DateField($auto_now_add = True, $auto_now = False);
		$field->pre_save(Null, True);
		$this->assertNotEqual($field->get_value(), date(DateField::$FORMAT, time()));
		$field->pre_save(Null, False);
		$this->assertEqual($field->get_value(), date(DateField::$FORMAT, time()));
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
		
		$field = new DateTimeField($auto_now_add = True, $auto_now = True);
		$field->pre_save(Null, True);
		$this->assertEqual($field->get_value(), date(DateTimeField::$FORMAT, time()));
		$field = new DateTimeField($auto_now_add = True, $auto_now = False);
		$field->pre_save(Null, True);
		$this->assertNotEqual($field->get_value(), date(DateTimeField::$FORMAT, time()));
		$field->pre_save(Null, False);
		$this->assertEqual($field->get_value(), date(DateTimeField::$FORMAT, time()));
	}
}
?>

