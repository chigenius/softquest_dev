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


