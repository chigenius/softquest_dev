------------------------------------------------------------------------------------------------
CAR.PHP
------------------------------------------------------------------------------------------------
<?php
require_once(LIB_PATH.DS.'database.php');

class Car {
	
    protected static $table_name="CARS";
    protected static $db_fields = array('REG_NO', 'CAR_MAKE', 'CAR_MODEL', 'COST', 'S_ID');
    public $REG_NO;
    public $CAR_MAKE;
    public $CAR_MODEL;
    public $COST;
    public $S_ID;

	
public static function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public static function find_by_id($id = 0) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE REG_NO='{$id}' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_make($make) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE CAR_MAKE='{$make}'");
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
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $database->escape_value($value);
        }
        return $clean_attributes;
    }

    public function save() {
        // A new record won't have an id yet.
        return isset($this->REG_NO) ? $this->update() : $this->create();
    }

    private static function car_id() {
        $number = mt_rand(12, 9999);
        global $database;

        if ($number > 999) {
            $zeros = "";
        } else if ($number > 99) {
            $zeros = "0";
        } else {
            $zeros = "00";
        }

        $carid = "CRN" . $zeros . ($number);
        
        return $carid;
    }

    public function create() {
        global $database;

        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $id = self::car_id();
        $sql .= $id;
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
       
        if ($database->query($sql)) {
            $this->REG_NO = $id;
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
        $sql .= " WHERE REG_NO=" . "'" . $database->escape_value($this->REG_NO) . "'";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function delete() {
        global $database;
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE REG_NO=" . "'" . $database->escape_value($this->REG_NO) . "'";
        $sql .= " LIMIT 1";
 
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;

    }
    
    public static function display_cars($cars, $task = NULL, $car=NULL){
    echo "<table id='box-table-a' summary='Employee Pay Sheet'><thead><tr>";
        echo "<th>S/N</th>";
        echo "<th>Model</th>";
        echo "<th>Make</th>";
        echo "<th>Cost</th>";
        echo "<th>Reg Num</th>";
        echo "<th>Modify</th>";
        echo "<th>X</th>";
        echo "</tr></thead>";
        echo "<tbody>";
        $i=1;
        echo $cars->CAR_MODEL;
        if(!$car == NULL){
           
            echo "<tr>";
            echo "<td>". $i . "</td>";
            echo "<td>". $car->CAR_MODEL . "</td>";
            echo "<td>". $car->CAR_MAKE . "</td>";
            echo "<td>". $car->COST . "</td>";
            echo "<td>". $car->REG_NO . "</td>";
            echo "<td>"."<a href='modifycars.php?task=edit&car_id=" .$car->REG_NO ."'>Edit</a>". "</td>";
            echo "<td>"."<a href='modifycars.php?task=del&car_id=" .$car->REG_NO ."' onclick='confirmDelete()'>X</a>". "</td>";
            echo "</tr>";
        }else {
            
            foreach ($cars as $car) {
                echo "<tr>";
                echo "<td>". $i . "</td>";
                echo "<td>". $car->CAR_MODEL . "</td>";
                echo "<td>". $car->CAR_MAKE . "</td>";
                echo "<td>". $car->COST . "</td>";
                echo "<td>". $car->REG_NO . "</td>";
                echo "<td>"."<a href='modifycars.php?task=edit&car_id=" .$car->REG_NO ."'>Edit</a>". "</td>";
                echo "<td>"."<a href='modifycars.php?task=del&car_id=" .$car->REG_NO ."' onclick='confirmDelete()'>X</a>". "</td>";
                echo "</tr>";
                $i++;
            }
        
        }
            echo "</tbody>";
            echo "</table>";

        if($task == 'query'){
        echo "<a href='search.php?task=search' class='button orange'>Back to Search</a>";
        } else {
            echo "<a href='modifycars.php?task=add' class='button orange'>Add New Car</a>";
        }
}


    public function edit_car($car_id=NULL){
                echo "<form action = 'modifycars.php' method='post'><p>";
                echo "<fieldset>";
                echo "<legend>{$car_id}</legend>";
                echo "<p>";
                echo "<label for='carmodel'>Car Model:</label>";
                echo "<input type='text' id='carmodel' name='model' value='{$this->CAR_MODEL}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='carmake'>Car Make:</label>";
                echo "<input type='text' id='carmake' name = 'make' value='{$this->CAR_MAKE}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='cost'>Cost:</label>";
                echo "<input type='text' id='cost' name='cost' value='{$this->COST}'/>";

                echo "</p>";
                echo "<p>";
                echo "<input type='hidden' id='save' name='task' value ='save'/>";
                echo "<input type='hidden' id='car_id' name='car_id' value ='{$car_id}'/>";
                echo "<input type='submit' id='save' value='Save' class='button orange'/>";
                echo "</p></fieldset></form>";
                echo "<a href='modifycars.php?task=add' class='button orange'>Delete Record</a>";
    }
    public function search_cars($cars, $carsregno){
                echo "<form action = 'search.php' method='post'><p>";
                        
                echo "<fieldset>";
                echo "<legend>Search Cars</legend>";
                echo "<p id = 'key'>";
                echo "<input type='radio' name='searchkey' value='carid' id='searchkey'/>Search by RegNum</t>";
                echo "<input type='radio' name='searchkey' value='make' id='searchkey'/>Search By Make";
                echo "</p>";
//                
                echo "<p id = 'carid'><label for='dropdown'>Car Reg Num:</label><select name='carid'>";
                echo "<option></option>";
                foreach ($carsregno as $car) {
                    echo "<option>{$car->REG_NO}</option>";
                }        
		echo "</select></p>";
                
                echo "<p id = 'carmake'><label for='dropdown'>Car Make:</label><select name='carmake'>";
                echo "<option></option>";
                foreach ($cars as $car) {
                    echo "<option>{$car->CAR_MAKE}</option>";
                }        
		echo "</select></p>";

                echo "<p>";
                echo "<input type='hidden' id='query' name='task' value ='query'/>";
                echo "<input type='submit' id='query' value='Search' class='button orange'/>";
                echo "</p></fieldset></form>";
    }
    public function add_car(){
        $suppliers = Supplier::find_all();
        echo "<form action = 'modifycars.php' method='post'><p>";
                echo "<fieldset>";
                echo "<legend>New Car</legend>";
                echo "<p>";
                echo "<label for='carmodel'>Car Model:</label>";
                echo "<input type='text' id='carmodel' name='model' value='--Car Model--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='carmake'>Car Make:</label>";
                echo "<input type='text' id='carmake' name = 'make' value='--Car Make--'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='cost'>Cost:</label>";
                echo "<input type='text' id='cost' name='cost' value='--Cost--'/>";
                echo "</p>";
                
                echo "<p><label for='dropdown'>Supplier:</label><select name='supplier'>";
                foreach ($suppliers as $supplier) {
                    echo "<option>{$supplier->S_NAME}</option>";
                }
		echo "</select></p>";
                echo "<p>";
                echo "<input type='hidden' id='save' name='task' value ='create'/>";
                echo "<input type='submit' id='save' value='Save' class='button orange'/>";
                echo "</p></fieldset></form>";
                
            }
}

?>


------------------------------------------------------------------------------------------------
CUSTOMER.PHP
------------------------------------------------------------------------------------------------
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

------------------------------------------------------------------------------------------------
SUPPLIER.PHP
------------------------------------------------------------------------------------------------

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
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name .
        " WHERE S_ID='{$id}' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function find_by_name($name) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name .
        " WHERE S_NAME='{$name}'");
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
            echo "<td>"."<a href='modifysuppliers.php?task=edit&s_id=" .$supplier->S_ID .
            "'>Edit</a>". "</td>";
            echo "<td>"."<a href='modifysuppliers.php?task=del&s_id=" .$supplier->S_ID .
            "' onclick='confirmDelete()'>X</a>". "</td>";
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
                echo "<input type='text' id='saddress' name = 'saddress' 
                value='{$this->S_ADDRESS}'/>";
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
                
                echo "<a href='modifysuppliers.php?task=add' 
                class='button orange'>Delete Record</a>";
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

------------------------------------------------------------------------------------------------
RENTAL.PHP
------------------------------------------------------------------------------------------------

<?php
require_once(LIB_PATH.DS.'database.php');

class Rental {
	
	protected static $table_name="RENTALS";
        protected static $db_fields = 
        array('RENTAL_ID', 'REG_NO', 'CUST_ID', 'RENTAL_DATE', 'DUE_DATE');
        protected static $db_insert_fields = array('REG_NO', 'CUST_ID', 'DUE_DATE');
	public $RENTAL_ID;
	public $REG_NO;
	public $CUST_ID;
	public $RENTAL_DATE;
	public $DUE_DATE;

// Common Database Methods
        
    function __construct() {
        $date = getdate();
        $currentdate  = $date[year] ."-". $date[mon] ."-". $date[mday];
        $duedate = strtotime("+21 days", strtotime($currentdate));
        $this->RENTAL_DATE = $currentdate;
        $this->DUE_DATE = date("Y-m-d", $duedate);
    }
    
    public static function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public static function find_by_id($id = 0) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name .
        " WHERE RENTAL_ID='{$id}' LIMIT 1");
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
        foreach (self::$db_insert_fields as $field) {
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
        return isset($this->RENTAL_ID) ? $this->update() : $this->create();
    }


    public function create() {
        global $database;
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        if ($database->query($sql)) {
            $this->RENTAL_ID = $database->insert_id();
            $this->RENTAL_DATE = $this->rental_date();
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
        $sql .= " WHERE RENTAL_ID=" . "'" . $database->escape_value($this->RENTAL_ID) . "'";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function delete() {
        global $database;
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE RENTAL_ID=" . "'" . $database->escape_value($this->RENTAL_ID) . "'";
        $sql .= " LIMIT 1";
        
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    private static function number_of_rentals() {
        global $database;
        $result = $database->query("SELECT * FROM " . self::$table_name);
        $number = $database->num_rows($result);

        return $number;
    }
    protected function rental_date() {
        global $database;
        $result_set = $database->query("SELECT RENTAL_DATE FROM " 
                . self::$table_name . " WHERE RENTAL_ID = " 
                .$this->RENTAL_ID ."" );
        $result = $database->fetch_assoc($result_set);

        return $result['RENTAL_DATE'];
    }

      
    public static function display_rentals($rentals){
    
        echo "<table id='box-table-a' summary='Employee Pay Sheet'><thead><tr>";
        echo "<th>S/N</th>";
        echo "<th>Car RegNum</th>";
        echo "<th>Customer ID</th>";
        echo "<th>Rental Date</th>";
        echo "<th>Due Date</th>";
        echo "<th>X</th>";
        echo "</tr></thead>";
        echo "<tbody>";
        $i=1;
        foreach ($rentals as $rental) {
            echo "<tr>";
            echo "<td>". $i . "</td>";
            echo "<td>". $rental->REG_NO . "</td>";
            echo "<td>". $rental->CUST_ID . "</td>";
            echo "<td>". $rental->RENTAL_DATE . "</td>";
            echo "<td>". $rental->DUE_DATE . "</td>";
            echo "<td>"."<a href='modifyrentals.php?task=del&r_id=" .
            $rental->RENTAL_ID ."' onclick='confirmDelete()'>X</a>". "</td>";
            echo "</tr>";
            $i++;
        }
            echo "</tbody>";
            echo "</table>";

        echo "<a href='modifyrentals.php?task=add' class='button orange'>Add New Rental</a>";
}

    public function edit_rental($r_id=NULL){
                        echo "<form action = 'modifycustomers.php' method='post'><p>";
                echo "<fieldset>";
                echo "<legend>{$cust_id}</legend>";
                echo "<p>";
                echo "<label for='title'>Title:</label>";
                echo "<input type='text' id='title' name='title' value='{$this->CUST_TITLE}'/>";
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
                echo "<input type='text' id='phone' name = 'phone' value='{$this->CUST_PHONE}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='address'>Address:</label>";
                echo "<input type='text' id='address' name='address' 
                value='{$this->CUST_ADDRESS}'/>";
                echo "</p>";
                
                echo "<p>";
                echo "<input type='hidden' id='save' name='task' value ='save'/>";
                echo "<input type='hidden' id='cust_id' name='cust_id' value ='{$cust_id}'/>";
                echo "<input type='submit' id='save' value='Save' class='button orange'/>";
                echo "</p></fieldset></form>";
                echo "<a href='modifycustomers.php?task=add' 
                class='button orange'>Delete Record</a>";
    }
    public function add_rental(){
        $cars = Car::find_all();
        $customers = Customer::find_all();
        
        echo "<form action = 'modifyrentals.php' method='post'><p>";
                echo "<fieldset>";
                echo "<legend>New Rental</legend>";
                
                echo "<p><label for='dropdown'>Car:</label><select name='car'>";
                foreach ($cars as $car) {
                    $value = $car->REG_NO;
                    echo "<option>{$value}</option>";
                }
                        
		echo "</select></p>";
                
                echo "<p><label for='dropdown'>Customer:</label><select name='customer'>";
                foreach ($customers as $customer) {
                    echo "<option>{$customer->CUST_ID}</option>";
                }
                        
		echo "</select></p>";
                                echo "<p>";
                echo "<input type='hidden' id='save' name='task' value ='create'/>";
                echo "<input type='submit' id='save' value='Save' class='button orange'/>";
                echo "</p></fieldset></form>";
                
            }
}

?>
