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


