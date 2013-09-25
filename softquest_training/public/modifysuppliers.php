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


