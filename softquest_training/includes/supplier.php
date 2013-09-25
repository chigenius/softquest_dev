<?php
require_once(LIB_PATH.DS.'database.php');

class Supplier {
	
	protected static $table_name="SUPPLIERS";
        protected static $db_fields = array('S_ID','S_NAME','S_ADDRESS', 'S_PHONE');
	public $S_ID;
	public $S_NAME;
	public $S_ADDRESS;
	public $S_PHONE;
	
  public static function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public static function find_by_id($id = 0) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE S_ID='{$id}' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function find_by_name($name) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE S_NAME='{$name}'");
        return !empty($result_array) ? $result_array : false;
    }
    public static function find_by_sql($sql = "") {
        global $database;
        $result_set = $database->query($sql);
        $object_array = array();
        while ($row = $database->fetch_array($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

    private static function instantiate($record) {
        // Could check that $record exists and is an array
        $object = new self;
        // More dynamic, short-form approach:
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    private function has_attribute($attribute) {
        // get_object_vars returns an associative array with all attributes 
        // (incl. private ones!) as the keys and their current values as the value
        $object_vars = get_object_vars($this);
        // We don't care about the value, we just want to know if the key exists
        // Will return true or false
        return array_key_exists($attribute, $object_vars);
    }

    protected function attributes() {
        // return an array of attribute names and their values
        $attributes = array();
        foreach (self::$db_fields as $field) {
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    protected function sanitized_attributes() {
        global $database;
        $clean_attributes = array();
        // sanitize the values before submitting
        // Note: does not alter the actual value of each attribute
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $database->escape_value($value);
        }
        return $clean_attributes;
    }

    public function save() {
        // A new record won't have an id yet.
        return isset($this->S_ID) ? $this->update() : $this->create();
    }

    private static function s_id() {
        //$number = self::number_of_sups();
        $number = mt_rand(10, 999);
        if ($number > 99) {
            $zeros = "";
        } else if($number >9 ){
            $zeros = "0";
        } else {
            $zeros = "00";
        }

        $sid = "S" . $zeros . ($number);

        return $sid;
    }

    public function create() {
        global $database;
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";

        $sql .= self::s_id();
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";

        if ($database->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        global $database;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE " . self::$table_name . " SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE S_ID=" . "'" . $database->escape_value($this->S_ID) . "'";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function delete() {
        global $database;
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE S_ID=" . "'" . $database->escape_value($this->S_ID) . "'";
        $sql .= " LIMIT 1";
        
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }
 
    public static function display_suppliers($suppliers){
    echo "<table id='box-table-a' summary='Employee Pay Sheet'><thead><tr>";
        echo "<th>S/N</th>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>Address</th>";
        echo "<th>Phone</th>";
        echo "<th>Modify</th>";
        echo "<th>X</th>";
        echo "</tr></thead>";
        echo "<tbody>";
        $i=1;
        foreach ($suppliers as $supplier) {
            echo "<tr>";
            echo "<td>". $i . "</td>";
            echo "<td>". $supplier->S_ID . "</td>";
            echo "<td>". $supplier->S_NAME . "</td>";
            echo "<td>". $supplier->S_ADDRESS . "</td>";
            echo "<td>". $supplier->S_PHONE . "</td>";
            echo "<td>"."<a href='modifysuppliers.php?task=edit&s_id=" .$supplier->S_ID ."'>Edit</a>". "</td>";
            echo "<td>"."<a href='modifysuppliers.php?task=del&s_id=" .$supplier->S_ID ."' onclick='confirmDelete()'>X</a>". "</td>";
            echo "</tr>";
            $i++;
        }
            echo "</tbody>";
            echo "</table>";

        echo "<a href='modifysuppliers.php?task=add' class='button orange'>Add New Supplier</a>";
}

    public function edit_supplier($s_id=NULL){
                        echo "<form action = 'modifysuppliers.php' method='post'><p>";
                echo "<fieldset>";
                echo "<legend>{$s_id}</legend>";
                echo "<p>";
                echo "<label for='sname'>Name:</label>";
                echo "<input type='text' id='sname' name='sname' value='{$this->S_NAME}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='saddress'>Address:</label>";
                echo "<input type='text' id='saddress' name = 'saddress' value='{$this->S_ADDRESS}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='sphone'>Phone:</label>";
                echo "<input type='text' id='sphone' name = 'sphone' value='{$this->S_PHONE}'/>";
                echo "</p>";
                
                echo "<p>";
                echo "<input type='hidden' id='save' name='task' value ='save'/>";
                echo "<input type='hidden' id='s_id' name='s_id' value ='{$s_id}'/>";
                echo "<input type='submit' id='save' value='Save' class='button orange'/>";
                echo "</p></fieldset></form>";
                
                echo "<a href='modifysuppliers.php?task=add' class='button orange'>Delete Record</a>";
    }
    public function add_supplier(){
        echo "<form action = 'modifysuppliers.php' method='post'><p>";
                echo "<fieldset>";
                echo "<legend>New Supplier</legend>";
                echo "<p>";
                echo "<label for='sname'>Name:</label>";
                echo "<input type='text' id='sname' name='sname' value='--Name--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='saddress'>Address:</label>";
                echo "<input type='text' id='saddress' name = 'saddress' value='--Address--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='sphone'>Phone:</label>";
                echo "<input type='text' id='sphone' name = 'sphone' value='--Phone--'/>";
                echo "</p>";
                
                echo "<input type='hidden' id='save' name='task' value ='create'/>";
                
                echo "<input type='submit' id='save' value='Save' class='button orange'/>";
                
                echo "</p></fieldset></form>";
                
            }    
}

?>