---------------------------------------------------------------------------------------
INDEX.PHP
---------------------------------------------------------------------------------------

<?php
//require_once '../includes/database.php';
require_once '../includes/initialize.php';
include_layout_template('header.php');

?>


                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="274" align="left" valign="top">
                        
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
                        <td colspan="3" align="left" height="25"></td>
                        </tr>
                      <tr>
                        <td width="93" align="left"><img src="images/thumb1_28.jpg" alt="" width="86" height="86" /></td>
                        <td width="93" align="left"><img src="images/thumb2_30.jpg" alt="" width="86" height="86" /></td>
                        <td align="left"><img src="images/thumb3_32.jpg" alt="" width="86" height="86" /></td>
                      </tr>
                      <tr>
                        <td height="8" colspan="3" align="left"></td>
                        </tr>
                      <tr>
                        <td align="left"><img src="images/thumb4_40.jpg" alt="" width="86" height="86" /></td>
                        <td align="left"><img src="images/thumb5_41.jpg" alt="" width="86" height="86" /></td>
                        <td align="left"><img src="images/thumb6_42.jpg" alt="" width="86" height="86" /></td>
                      </tr>
                      <tr>
                        <td height="8" colspan="3" align="left"></td>
                        </tr>
                      <tr>
                        <td align="left"><img src="images/thumb7_50.jpg" alt="" width="86" height="86" /></td>
                        <td align="left"><img src="images/thumb8_51.jpg" alt="" width="86" height="86" /></td>
                        <td align="left"><img src="images/thumb9_52.jpg" alt="" width="86" height="86" /></td>
                      </tr>
                    </table></td>
                    <td width="10"></td>
                    <td width="303" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="27"></td>
                      </tr>
                      <tr>
                        <td align="left" class="heading1">About Us </td>
                      </tr>
                      <tr>
                        <td height="10"></td>
                      </tr>
                      <tr>
                        <td align="left" class="text1">
                            This is a system design to keep records for a Wheels on Me Administration. <br /><br />
                            It was designed for a coursework in CSC-438 Internet and Web Technology,
                            By <b>Professor Apkar Salatian</b>
                            <br /><br /><br />
                        </td>
                      </tr>
    
<?
include_layout_template('footer.php');
?>

---------------------------------------------------------------------------------------
MODIFYCARS.PHP
---------------------------------------------------------------------------------------

<? require_once '../includes/initialize.php';
include_layout_template('header.php');?>


                    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="10"></td>
        <td width="303" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="27"></td></tr>
                      <tr><td align="left" class="heading1">Modify Cars</td></tr>
                      <tr>
                        <td align="center" height="10">
<?
//definintion of page types

//$page = !empty($_GET['task'])? $_GET['task'] : 'view';
$task = !empty($_GET['task'])? $_GET['task'] : $_POST['task'];
$car_id = !empty($_GET['car_id'])? $_GET['car_id'] : NULL;

$edited = false;
$currentcar = Car::find_by_id($car_id);
$cars = Car::find_all();

?>

    <?
    switch ($task) {
        case 'save':
            $car_id = $_POST['car_id'] ;
            $currentcar = Car::find_by_id($car_id);
            $currentcar->CAR_MODEL = !empty($_POST['model'])? $_POST['model'] : $currentcar->CAR_MODEL;
            $currentcar->CAR_MAKE = !empty($_POST['make'])? $_POST['make'] : $currentcar->CAR_MAKE;
            $currentcar->COST = !empty($_POST['cost'])? $_POST['cost'] : $currentcar->COST;
            $currentcar->S_ID = !empty($_POST['supplier'])? $_POST['supplier'] : $currentcar->S_ID;
            $saved = $currentcar->save();
            $confirmation = $saved? "You have updated -- ". $currentcar->REG_NO . "<br/><br/>" : "Data was not updated";
            echo $confirmation;

            echo "<a href='modifycars.php?task=view' class='button orange'>Browse Cars</a>";
            break;
        case 'edit':
            $currentcar->edit_car($car_id);
            break;
        case 'view':
            Car::display_cars($cars);
            break;
        case 'add':
            Car::add_car($cars);

            break;
        case 'create':
            global $database;
            $newcar = new Car();
            
            $suppliers = Supplier::find_by_name($_POST['supplier']);
            foreach ($suppliers as $value) {
                $sid =  $value->S_ID;
            }  

            $newcar->CAR_MODEL = !empty($_POST['model'])? $_POST['model'] : NULL;
            $newcar->CAR_MAKE = !empty($_POST['make'])? $_POST['make'] : NULL;
            $newcar->COST = !empty($_POST['cost'])? $_POST['cost'] : NULL;
            $newcar->S_ID = !empty($_POST['supplier'])? $sid : NULL;
            $created = $newcar->save();
            
            $confirmation = $created? "You have added <br />". $newcar->CAR_MODEL . "<br/><br/>" ."Registration Number:"
                    .$newcar->REG_NO ."<br/><br/>": "Data was not updated";
            echo $confirmation;

            echo "<a href='modifycars.php?task=view' class='button orange'>Browse Cars</a>";
            break;
        case 'del':
            $currentcar = Car::find_by_id($car_id);
            echo $currentcar->delete()? "You have deleted ".$currentcar->REG_NO ."<br /><br />" : "Sorry, this car is 
                currently on loan and cannot be deleted<br /><br />";
            
            echo "<a href='modifycars.php?task=view' class='button orange'>Browse Cars</a>";
            break;

        default:
            
            break;
    }
    ?>

                            
<?

?>
<?
include_layout_template('footer.php');
?>




---------------------------------------------------------------------------------------
MODIFYCUSTOMERS.PHP
---------------------------------------------------------------------------------------
<? require_once '../includes/initialize.php';
include_layout_template('header.php');?>


                    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="10"></td>
        <td width="303" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="27"></td></tr>
                      <tr><td align="left" class="heading1">Modify Customers</td></tr>
                      <tr>
                        <td align="center" height="10">
<?
//definintion of page types

//$page = !empty($_GET['task'])? $_GET['task'] : 'view';
$task = !empty($_GET['task'])? $_GET['task'] : $_POST['task'];
$cust_id = !empty($_GET['cust_id'])? $_GET['cust_id'] : NULL;

$current_customer = Customer::find_by_id($cust_id);
$customers = Customer::find_all();

?>

    <?
    switch ($task) {
        case 'save':
            $cust_id = $_POST['cust_id'] ;
            $currentcust = Customer::find_by_id($cust_id);
            $currentcust->CUST_TITLE = !empty($_POST['title'])? $_POST['title'] : $currentcust->CUST_TITLE;
            $currentcust->CUST_FNAME = !empty($_POST['fname'])? $_POST['fname'] : $currentcust->CUST_FNAME;
            $currentcust->CUST_LNAME = !empty($_POST['lname'])? $_POST['lname'] : $currentcust->CUST_LNAME;
            $currentcust->CUST_PHONE = !empty($_POST['phone'])? $_POST['phone'] : $currentcust->CUST_PHONE;
            $currentcust->CUST_ADDRESS = !empty($_POST['address'])? $_POST['address'] : $currentcust->CUST_ADDRESS;
            
            $saved = $currentcust->save();
            $confirmation = $saved? "You have updated -- ". $currentcust->CUST_ID . "<br/><br/>" : "Data was not updated";
            echo $confirmation;

            echo "<a href='modifycustomers.php?task=view' class='button orange'>Browse Customers</a>";
            break;
        case 'edit':
            $current_customer->edit_customer($cust_id);
            
            break;
        case 'view':
            Customer::display_customers($customers);
            
            break;
        case 'add':
            Customer::add_customer();

            break;
        case 'create':
            $newcustomer = new Customer();
            
            $newcustomer->CUST_TITLE = !empty($_POST['title'])? $_POST['title'] : NULL;
            $newcustomer->CUST_FNAME = !empty($_POST['fname'])? $_POST['fname'] : NULL;
            $newcustomer->CUST_LNAME = !empty($_POST['lname'])? $_POST['lname'] : NULL;
            $newcustomer->CUST_PHONE = !empty($_POST['phone'])? $_POST['phone'] : NULL;
            $newcustomer->CUST_ADDRESS = !empty($_POST['address'])? $_POST['address'] : NULL;
            $created = $newcustomer->save();
            
            $confirmation = $created? "You have added <br />". $newcustomer->CUST_FNAME . 
                    $newcustomer->CUST_LNAME ."<br/><br/>" ."ID Number:"
                    .$newcustomer->CUST_ID ."<br/><br/>" : "Data was not updated";
            echo $confirmation;

            echo "<a href='modifycustomers.php?task=view' class='button orange'>Browse Customers</a>";
            break;
        case 'del':
            $currentcust = Customer::find_by_id($cust_id);
            echo $currentcust->delete()? "You have deleted ".$currentcust->CUST_ID ."<br /><br />" : "Delete Operation Failed";
            
            echo "<a href='modifycustomers.php?task=view' class='button orange'>Browse Customers</a>";
            break;

        default:
            
            break;
    }
    ?>

                            
<?

?>
<?
include_layout_template('footer.php');
?>



---------------------------------------------------------------------------------------
MODIFYRENTALS.PHP
---------------------------------------------------------------------------------------

<? require_once '../includes/initialize.php';
include_layout_template('header.php');?>
                    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="10"></td>
        <td width="303" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="27"></td></tr>
                      <tr><td align="left" class="heading1">Modify Loans</td></tr>
                      <tr>
                        <td align="center" height="10">
<?
//definintion of page types

$task = !empty($_GET['task'])? $_GET['task'] : $_POST['task'];
$r_id = !empty($_GET['r_id'])? $_GET['r_id'] : NULL;

$current_rental = Rental::find_by_id($r_id);
$rentals = Rental::find_all();

?>

    <?
    switch ($task) {
        case 'save':
            $s_id = $_POST['s_id'] ;
            $currentsupplier = Supplier::find_by_id($s_id);
            $currentsupplier->S_NAME = !empty($_POST['sname'])? $_POST['sname'] : $currents->S_NAME;
            $currentsupplier->S_ADDRESS = !empty($_POST['saddress'])? $_POST['saddress'] : $currents->S_ADDRESS;
            $currentsupplier->S_PHONE = !empty($_POST['sphone'])? $_POST['sphone'] : $currents->S_PHONE;
            
            $saved = $currentsupplier->save();
            $confirmation = $saved? "You have updated -- ". $currentsupplier->S_ID . "<br/><br/>" : "Data was not updated";
            echo $confirmation;

            echo "<a href='modifysuppliers.php?task=view' class='button orange'>Browse Suppliers</a>";
            break;
        case 'edit':
            $current_supplier->edit_supplier($s_id);
            
            break;
        case 'view':
            Rental::display_rentals($rentals);
            break;
        case 'add':
            Rental::add_rental();

            break;
        case 'create':
            $newrental = new Rental();
            
            $newrental->REG_NO = !empty($_POST['car'])? $_POST['car'] : NULL;
            $newrental->CUST_ID = !empty($_POST['customer'])? $_POST['customer'] : NULL;
            //$newrental->DUE_DATE = !empty($_POST['sphone'])? $_POST['sphone'] : NULL;
            
            $created = $newrental->save();
            
            if($created){
                echo "You have rented out <br />". $newrental->REG_NO . 
                     "<br/><br/>";
                echo "<a href='modifyrentals.php?task=view' class='button orange'>Browse Rentals</a>";
            } else {
                echo "The car is not available for rental <br />";
                echo "<a href='modifyrentals.php?task=add' class='button orange'>Rent Another</a>";
            }
//            $confirmation = $created? "You have rented out <br />". $newrental->REG_NO . 
//                     "<br/><br/>" : "The car is not available for rental <br />";
//            echo $confirmation;

            
            break;
        case 'del':
            $current_rental = Rental::find_by_id($r_id);
            echo $current_rental->delete()? "You have deleted ".$current_rental->REG_NO ."<br /><br />" : "Delete Operation Failed";
            
            echo "<a href='modifyrentals.php?task=view' class='button orange'>Browse Rentals</a>";
            break;

        default:
            
            break;
    }
    ?>

<?
include_layout_template('footer.php');
?>




---------------------------------------------------------------------------------------
MODIFYSUPPLIERS.PHP
---------------------------------------------------------------------------------------

<? require_once '../includes/initialize.php';
include_layout_template('header.php');?>
                    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="10"></td>
        <td width="303" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="27"></td></tr>
                      <tr><td align="left" class="heading1">Modify Suppliers</td></tr>
                      <tr>
                        <td align="center" height="10">
<?
//definintion of page types

$task = !empty($_GET['task'])? $_GET['task'] : $_POST['task'];
$s_id = !empty($_GET['s_id'])? $_GET['s_id'] : NULL;

$current_supplier = Supplier::find_by_id($s_id);
$suppliers = Supplier::find_all();

?>

    <?
    switch ($task) {
        case 'save':
            $s_id = $_POST['s_id'] ;
            $currentsupplier = Supplier::find_by_id($s_id);
            $currentsupplier->S_NAME = !empty($_POST['sname'])? $_POST['sname'] : $currents->S_NAME;
            $currentsupplier->S_ADDRESS = !empty($_POST['saddress'])? $_POST['saddress'] : $currents->S_ADDRESS;
            $currentsupplier->S_PHONE = !empty($_POST['sphone'])? $_POST['sphone'] : $currents->S_PHONE;
            
            $saved = $currentsupplier->save();
            $confirmation = $saved? "You have updated -- ". $currentsupplier->S_ID . "<br/><br/>" : "Data was not updated";
            echo $confirmation;

            echo "<a href='modifysuppliers.php?task=view' class='button orange'>Browse Suppliers</a>";
            break;
        case 'edit':
            $current_supplier->edit_supplier($s_id);
            
            break;
        case 'view':
            Supplier::display_suppliers($suppliers);
            break;
        case 'add':
            Supplier::add_supplier();

            break;
        case 'create':
            $newsupplier = new Supplier();
            
            $newsupplier->S_NAME = !empty($_POST['sname'])? $_POST['sname'] : NULL;
            $newsupplier->S_ADDRESS = !empty($_POST['saddress'])? $_POST['saddress'] : NULL;
            $newsupplier->S_PHONE = !empty($_POST['sphone'])? $_POST['sphone'] : NULL;
            
            $created = $newsupplier->save();
            
            $confirmation = $created? "You have added <br />". $newsupplier->S_NAME . 
                     "<br/><br/>" : "Data was not updated";
            echo $confirmation;

            echo "<a href='modifysuppliers.php?task=view' class='button orange'>Browse Suppliers</a>";
            break;
        case 'del':
            $currentsupplier = Supplier::find_by_id($s_id);
            echo $currentsupplier->delete()? "You have deleted ".$currentsupplier->S_ID ."<br /><br />" : "Delete Operation Failed";
            
            echo "<a href='modifysuppliers.php?task=view' class='button orange'>Browse Suppliers</a>";
            break;

        default:
            
            break;
    }
    ?>

<?
include_layout_template('footer.php');
?>




---------------------------------------------------------------------------------------
REPORT.PHP
---------------------------------------------------------------------------------------

    <? require_once '../includes/initialize.php';
include_layout_template('header.php');?>
                    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="10"></td>
        <td width="303" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="27"></td></tr>
                      <tr><td align="left" class="heading1">Report</td></tr>
                      <tr>
                        <td align="center" height="10">
<?

$customers = Customer::find_all();
global $database;

$sql_gen = "SELECT CONCAT(`CUSTOMERS`.CUST_FNAME, ' ', `CUSTOMERS`.CUST_LNAME)'Name',";
$sql_gen .="`CARS`.CAR_MODEL, `SUPPLIERS`.S_NAME, `RENTALS`.RENTAL_DATE, `RENTALS`.DUE_DATE";
$sql_gen .=" FROM CUSTOMERS, SUPPLIERS, CARS, RENTALS";
$sql_gen .=" WHERE `CUSTOMERS`.CUST_ID = `RENTALS`.CUST_ID AND";
$sql_gen .=" `RENTALS`.REG_NO = `CARS`.REG_NO AND";
$sql_gen .=" `CARS`.S_ID = `SUPPLIERS`.S_ID";
$sql_gen .=" ORDER BY `CUSTOMERS`.CUST_FNAME";

$gen_result = $database->query($sql_gen);

$task = !empty($_GET['task'])? $_GET['task'] : $_POST['task'];
$cust = !empty($_POST['cust_id'])? $_POST['cust_id'] : 'C0000001';
$current_cust = Customer::find_by_id($cust);

$sql_cust = "SELECT CONCAT(`CUSTOMERS`.CUST_FNAME, ' ', `CUSTOMERS`.CUST_LNAME)'Name',";
$sql_cust .="`CARS`.CAR_MODEL, `SUPPLIERS`.S_NAME, `RENTALS`.RENTAL_DATE, `RENTALS`.DUE_DATE";
$sql_cust .=" FROM CUSTOMERS, SUPPLIERS, CARS, RENTALS";
$sql_cust .=" WHERE `CUSTOMERS`.CUST_ID = '{$cust}' AND";
$sql_cust .=" `CUSTOMERS`.CUST_ID = `RENTALS`.CUST_ID AND";
$sql_cust .=" `RENTALS`.REG_NO = `CARS`.REG_NO AND";
$sql_cust .=" `CARS`.S_ID = `SUPPLIERS`.S_ID";

$cust_result = $database->query($sql_cust);

?>
<?
if($task == 'form'){
        echo "<form action = 'report.php' method='post'><p>";        
        echo "<fieldset>";
        echo "<legend>Generate Report</legend>";
        echo "<p><label for='make'>Customer:</label><select name='cust_id'>";

        foreach ($customers as $customer) {
            echo "<option value='{$customer->CUST_ID}'>{$customer->full_name()}</option>";
        }        
        echo "</select></p>";

        echo "<p>";
        echo "<input type='hidden' id='customer' name='task' value ='customer'/>";
        echo "<input type='submit' id='customer' value='Get Report' class='button orange'/>";
        echo "</p></fieldset></form>";

        echo "<a href='report.php?task=general' class='button orange'>View Complete Report</a>";
}
?>
    <?
    switch ($task) {
        case 'customer':
            echo "Report for ". $current_cust->full_name();
            
            echo "<table id='box-table-a' summary='Employee Pay Sheet'><thead><tr>";
            echo "<th>S/N</th>";
            echo "<th>Name</th>";
            echo "<th>Car Model</th>";
            echo "<th>Supplier</th>";
            echo "<th>Rental Date</th>";
            echo "<th>Due Date</th>";
            echo "</tr></thead>";
            echo "<tbody>";
            $i=1;
            while($record = $database->fetch_array($cust_result)){
            
                echo "<tr>";
                echo "<td>". $i . "</td>";
                echo "<td>". $record['Name'] . "</td>";
                echo "<td>". $record['CAR_MODEL'] . "</td>";
                echo "<td>". $record['S_NAME'] . "</td>";
                echo "<td>". $record['RENTAL_DATE'] . "</td>";
                echo "<td>". $record['DUE_DATE'] . "</td>";
                echo "</tr>";
                $rentals = $i++;
                    
            }
                
            echo "</tbody>";
            echo "</table>";
            
            echo "Total Number of Cars = ".$rentals . "<br /><br />";
            echo "<a href='report.php?task=form' class='button orange'>Get New Report</a>";
            break;
        
        case 'general':
            
            echo "<table id='box-table-a' summary='Employee Pay Sheet'><thead><tr>";
            echo "<th>S/N</th>";
            echo "<th>Name</th>";
            echo "<th>Car Model</th>";
            echo "<th>Supplier</th>";
            echo "<th>Rental Date</th>";
            echo "<th>Due Date</th>";
            echo "</tr></thead>";
            echo "<tbody>";
            $i=1;
            while($record = $database->fetch_array($gen_result)){
                echo "<tr>";
                echo "<td>". $i . "</td>";
                echo "<td>". $record['Name'] . "</td>";
                echo "<td>". $record['CAR_MODEL'] . "</td>";
                echo "<td>". $record['S_NAME'] . "</td>";
                echo "<td>". $record['RENTAL_DATE'] . "</td>";
                echo "<td>". $record['DUE_DATE'] . "</td>";
                echo "</tr>";
                $i++;
            }
                
            echo "</tbody>";
            echo "</table>";
            echo "<a href='report.php?task=form' class='button orange'>Get New Report</a>";
            
            break;
        
        default:
            
            break;
    }
    ?>

<?
include_layout_template('footer.php');
?>




---------------------------------------------------------------------------------------
SEARCH.PHP
---------------------------------------------------------------------------------------

<? require_once '../includes/initialize.php';
include_layout_template('header.php');?>
                    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="10"></td>
        <td width="303" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="27"></td></tr>
                      <tr><td align="left" class="heading1">Search Cars</td></tr>
                      <tr>
                        <td align="center" height="10">
<?
$sql1 = "SELECT DISTINCT CAR_MAKE FROM CARS";   
$sql2 = "SELECT REG_NO FROM CARS";   
$cars = Car::find_by_sql($sql1);
$carsregno = Car::find_by_sql($sql2);

$key = $_POST['searchkey'];
//echo $key;
//definintion of page types

$task = !empty($_GET['task'])? $_GET['task'] : $_POST['task'];

if($key == 'carid'){
    $car_id = $_POST['carid'];
    //echo $car_id;
    echo "Showing Results for ".$car_id . "<br /><br />";
    $car_result = Car::find_by_id($car_id);
} else if($key == 'make'){
    $carmake = $_POST['carmake'];
    echo "Showing Results for ".$carmake . "<br /><br />";
    $cars_result = Car::find_by_make($carmake);
}

?>

    <?
    switch ($task) {
        case 'search':
            Car::search_cars($cars, $carsregno);
            break;
        case 'query':
            
            Car::display_cars($cars_result, $task, $car_result);
            break;
        default:
            
            break;
    }
    ?>

<?
include_layout_template('footer.php');
?>


