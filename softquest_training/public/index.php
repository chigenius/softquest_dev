<?php
//require_once '../includes/database.php';
require_once '../includes/initialize.php';
include_layout_template('header2.php');

?>


                   		<!-- Header Wrapper -->
			<div id="header-wrapper">
				<div class="5grid-layout">
					<div class="row">
						<div class="12u">
						
							<!-- Header -->
								<section id="header">
									
									<!-- Logo -->
										<h1><a href="#">SoftQuest Training Management System</a></h1>
			

								</section>

						</div>
					</div>
					<div class="row">
						<div class="12u">

						</div>
					</div>
					<div class="row">
						<div class="12u">
								
							<!-- Intro -->
								<section id="intro">
								
						

									<div class="actions">
										<a href="#" class="button button-big">I'm a Student</a>
										<a href="#" class="button button-alt button-big">I'm a Trainer</a>
									</div>
								
								</section>

						</div>
					</div>
				</div>
			</div>
    
<?
include_layout_template('footer2.php');
?>

<!--SELECT `CUSTOMERS`.`CUST_FNAME`, `CUSTOMERS`.CUST_LNAME, `RENTALS`.RENTAL_ID , `RENTALS`.REG_NO 
FROM `CUSTOMERS`
LEFT JOIN `RENTALS` 
ON `CUSTOMERS`.CUST_ID = `RENTALS`.CUST_ID-->