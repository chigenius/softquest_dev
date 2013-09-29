
<?php
require_once(LIB_PATH.DS.'database.php');

class Question {
	
    protected static $table_name="QUESTIONS";
    protected static $db_fields = array('Q_ID', 'Q_TYPE', 'FORM_ID','Q_SECTION');
    public $Q_ID;
    public $Q_TYPE;
    public $FORM_ID;
    public $Q_SECTION;
    //public $S_ID;

	
public static function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public static function find_by_id($id = 0) {
        $result_array = self::find_by_sql(
        "SELECT * FROM " . self::$table_name . 
        " WHERE Q_ID='{$id}' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_type($type) {
        $result_array = self::find_by_sql(
        "SELECT * FROM " . self::$table_name . 
        " WHERE Q_TYPE='{$type}'");
        return !empty($result_array) ? $result_array : false;
    }
    public static function find_by_section($section) {
        $result_array = self::find_by_sql(
        "SELECT * FROM " . self::$table_name . 
        " WHERE Q_SECTION='{$section}'");
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
        return isset($this->Q_ID) ? $this->update() : $this->create();
    }

    private static function q_id() {
        $number = mt_rand(12, 99);
        global $database;

        if ($number > 999) {
            $zeros = "";
        } else if ($number > 99) {
            $zeros = "0";
        } else {
            $zeros = "00";
        }

        $formid = "F" . $number;
        
        return $formid;
    }

    public function create() {
        global $database;

        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $id = self::form_id();
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
        $sql .= " WHERE FORM_ID=" . "'" . $database->escape_value($this->FORM_ID) . "'";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function delete() {
        global $database;
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE FORM_ID=" . "'" . $database->escape_value($this->FORM_ID) . "'";
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

