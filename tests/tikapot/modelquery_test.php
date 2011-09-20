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
require_once(home_dir . "framework/model_query.php");
require_once(home_dir . "framework/database.php");

class CustomModelQuery extends ModelQuery { public function count() { return 50; } }

class ModelQueryTestModel extends Model
{
	public function __construct() {
		parent::__construct();
		$this->add_field("test_prop", new CharField("", $max_length=70));
		$this->add_field("other_prop", new NumericField(4.5));
	}
}

class ModelQueryCustomQueryTestModel extends ModelQueryTestModel
{	
	protected static function get_modelquery($query = array()) { return new CustomModelQuery(new static(), $query); }
}


class ModelQueryTest extends UnitTestCase {
	function testModelManagerOverride() {
		$obj = new ModelQueryCustomQueryTestModel();
		$this->assertEqual(count(ModelQueryCustomQueryTestModel::all()), 50);
	}
	
	function testModelManager() {
		$db = Database::create();
		$obj = new ModelQueryTestModel();
		$obj->create_table(); // This is here to prevent the query below failing if no table exists yet
		$db->query("DELETE FROM " . $obj->get_table_name() . ";");
		$obj->test_prop = "Hello";
		$obj->other_prop = 1.0;
		$id = $obj->save();
		$this->assertTrue($id > 0);
		$this->assertEqual(ModelQueryTestModel::get($id)->test_prop, $obj->test_prop);
		$obj->test_prop = "Bye!";
		$obj->save();
		$this->assertEqual(ModelQueryTestModel::get($id)->test_prop, "Bye!");
		$obj->test_prop = "Hi!";
		$obj->other_prop = 2.0;
		$obj->save();
		$this->assertEqual(ModelQueryTestModel::get($id)->test_prop, "Hi!");
		$this->assertEqual(ModelQueryTestModel::get($id)->other_prop, 2.0);
		$obj1 = new ModelQueryTestModel();
		$obj1->other_prop = 6.0;
		$obj1->save();
		$obj2 = new ModelQueryTestModel();
		$obj2->other_prop = 9.0;
		$obj2id = $obj2->save();
		$this->assertEqual(ModelQueryTestModel::all()->order_by("other_prop")->get(0)->other_prop, 2.0);
		$this->assertEqual(ModelQueryTestModel::all()->order_by(array("other_prop", "DESC"))->get(0)->other_prop, 9.0);
		$this->assertEqual(count(ModelQueryTestModel::find(array("id"=>$obj2id))), 1);
		$obj2->delete();
		$this->assertEqual(count(ModelQueryTestModel::find(array("id"=>$obj2id))), 0);
		
		// Test shortcuts
		list($obj, $created) = ModelQueryTestModel::get_or_create(array("test_prop" => "goctst"));
		$this->assertTrue($created);
		$this->assertEqual($obj->test_prop, "goctst");
		$this->assertEqual(ModelQueryTestModel::get(array("test_prop" => "goctst"))->test_prop, "goctst");
		list($obj, $created) = ModelQueryTestModel::get_or_create(array("test_prop" => "goctst"));
		$this->assertFalse($created);
	}
}

?>

