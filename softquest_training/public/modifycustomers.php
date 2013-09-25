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


