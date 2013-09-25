<?php
require_once(LIB_PATH.DS.'database.php');

class Rental {
	
	protected static $table_name="RENTALS";
        protected static $db_fields = array('RENTAL_ID', 'REG_NO', 'CUST_ID', 'RENTAL_DATE', 'DUE_DATE');
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
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE RENTAL_ID='{$id}' LIMIT 1");
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
            echo "<td>"."<a href='modifyrentals.php?task=del&r_id=" .$rental->RENTAL_ID ."' onclick='confirmDelete()'>X</a>". "</td>";
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
                echo "<input type='text' id='firstname' name = 'fname' value='{$this->CUST_FNAME}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='lastname'>Last Name:</label>";
                echo "<input type='text' id='lastname' name = 'lname' value='{$this->CUST_LNAME}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='phone'>Phone:</label>";
                echo "<input type='text' id='phone' name = 'phone' value='{$this->CUST_PHONE}'/>";
                echo "</p>";
                echo "<p>";
                echo "<label for='address'>Address:</label>";
                echo "<input type='text' id='address' name='address' value='{$this->CUST_ADDRESS}'/>";
                echo "</p>";
                
                echo "<p>";
                echo "<input type='hidden' id='save' name='task' value ='save'/>";
                echo "<input type='hidden' id='cust_id' name='cust_id' value ='{$cust_id}'/>";
                echo "<input type='submit' id='save' value='Save' class='button orange'/>";
                echo "</p></fieldset></form>";
                echo "<a href='modifycustomers.php?task=add' class='button orange'>Delete Record</a>";
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