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


