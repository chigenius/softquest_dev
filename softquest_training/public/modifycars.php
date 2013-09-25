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


