<?php

require_once(LIB_PATH . DS . 'database.php');

class Customer {

    protected static $table_name = "CUSTOMERS";
    protected static $db_fields = array('CUST_ID', 'CUST_TITLE', 'CUST_FNAME',
        'CUST_LNAME', 'CUST_ADDRESS', 'CUST_PHONE');
    public $CUST_ID;
    public $CUST_TITLE;
    public $CUST_FNAME;
    public $CUST_LNAME;
    public $CUST_ADDRESS;
    public $CUST_PHONE;


    public function full_name() {
        if (isset($this->CUST_FNAME) && isset($this->CUST_LNAME)) {
            return $this->CUST_FNAME . " " . $this->CUST_LNAME;
        } else {
            return "";
        }
    }

    // Common Database Methods
    public static function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public static function find_by_id($id = 0) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name .
                " WHERE CUST_ID='{$id}' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
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
        
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    private function has_attribute($attribute) {
        // get_object_vars returns an associative array with all attributes 
        // (incl. private ones!) as the keys and their 
        // current values as the value
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
        return isset($this->CUST_ID) ? $this->update() : $this->create();
    }

    private static function cust_id() {
        $number = mt_rand(20, 99999);
        
        if ($number > 9999) {
            $zeros = "";
        } else if ($number > 999) {
            $zeros = "0";
        } else if ($number > 99) {
            $zeros = "00";
        } else {
            $zeros = "000";
        }

        $custid = "C00" . $zeros . ($number);

        return $custid;
    }

    public function create() {
        global $database;

        $attributes = $this->sanitized_attributes();

        $sql = "INSERT INTO " . self::$table_name . " (";

        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $id = self::cust_id();
        $sql .= $id;
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";

        
        if ($database->query($sql)) {
            $this->CUST_ID = $id;
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
        $sql .= " WHERE CUST_ID=" . "'" . 
                $database->escape_value($this->CUST_ID) . "'";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function delete() {
        global $database;
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE CUST_ID=" . "'" . 
                $database->escape_value($this->CUST_ID) . "'";
        $sql .= " LIMIT 1";
        
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;

    }
       
    public static function display_customers($customers){
    echo "<table id='box-table-a' summary='Employee Pay Sheet'><thead><tr>";
        echo "<th>S/N</th>";
        echo "<th>ID</th>";
        echo "<th>Title</th>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Phone</th>";
        echo "<th>Address</th>";
        echo "<th>Modify</th>";
        echo "<th>X</th>";
        echo "</tr></thead>";
        echo "<tbody>";
        $i=1;
        foreach ($customers as $customer) {
            echo "<tr>";
            echo "<td>". $i . "</td>";
            echo "<td>". $customer->CUST_ID . "</td>";
            echo "<td>". $customer->CUST_TITLE . "</td>";
            echo "<td>". $customer->CUST_FNAME . "</td>";
            echo "<td>". $customer->CUST_LNAME . "</td>";
            echo "<td>". $customer->CUST_PHONE . "</td>";
            echo "<td>". $customer->CUST_ADDRESS . "</td>";
            echo "<td>"."<a href='modifycustomers.php?task=edit&cust_id=" .
                    $customer->CUST_ID ."'>Edit</a>". "</td>";
            echo "<td>"."<a href='modifycustomers.php?task=del&cust_id=" .
                    $customer->CUST_ID ."' onclick='confirmDelete()'>X</a>".
                    "</td>";
            echo "</tr>";
            $i++;
        }
            echo "</tbody>";
            echo "</table>";

        echo "<a href='modifycustomers.php?task=add' 
            class='button orange'>Add New Customer</a>";
}

    public function edit_customer($cust_id=NULL){
                        echo "<form action = 'modifycustomers.php' 
                            method='post'><p>";
                echo "<fieldset>";
                echo "<legend>{$cust_id}</legend>";
                echo "<p>";
                echo "<label for='title'>Title:</label>";
                echo "<input type='text' id='title' name='title' 
                value='{$this->CUST_TITLE}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='firstname'>First Name:</label>";
                echo "<input type='text' id='firstname' name = 'fname' 
                value='{$this->CUST_FNAME}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='lastname'>Last Name:</label>";
                echo "<input type='text' id='lastname' name = 'lname' 
                value='{$this->CUST_LNAME}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='phone'>Phone:</label>";
                echo "<input type='text' id='phone' name = 'phone' 
                value='{$this->CUST_PHONE}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='address'>Address:</label>";
                echo "<input type='text' id='address' name='address' 
                value='{$this->CUST_ADDRESS}'/>";
                echo "</p>";
                
                echo "<p>";
                echo "<input type='hidden' id='save' name='task' 
                    value ='save'/>";
                echo "<input type='hidden' id='cust_id' name='cust_id' 
                value ='{$cust_id}'/>";
                echo "<input type='submit' id='save' value='Save' 
                    class='button orange'/>";
                
                echo "</p></fieldset></form>";
                echo "<a href='modifycustomers.php?task=add' 
                    class='button orange'>Delete Record</a>";
    }
    public function add_customer(){
        
        echo "<form action = 'modifycustomers.php' method='post'><p>";
                echo "<fieldset>";
                echo "<legend>New Customer</legend>";
                echo "<p>";
                echo "<label for='title'>Title:</label>";
                echo "<input type='text' id='title' name='title' 
                    value='--Title--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='fname'>First Name:</label>";
                echo "<input type='text' id='fname' name = 'fname' 
                    value='--First Name--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='lname'>Last Name:</label>";
                echo "<input type='text' id='lname' name = 'lname' 
                    value='--Last Name--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='phone'>Phone:</label>";
                echo "<input type='text' id='phone' name = 'phone' 
                    value='--Phone--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='address'>Address:</label>";
                echo "<input type='text' id='address' name='address' 
                    value='--Address--'/>";
                echo "</p>";     
                echo "<p>";
                echo "<input type='hidden' id='save' name='task' 
                    value ='create'/>";
                echo "<input type='submit' id='save' value='Save' 
                    class='button orange'/>";
                echo "</p></fieldset></form>";
                
            }

    
}


?>