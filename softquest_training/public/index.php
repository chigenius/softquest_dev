<?php
/********************************************
 ** SoftQuest Systems Limited
 ** Project Title: Training Management System
 ** Developer: Chibuzo Nwankpo
 ** Designation: Software Developer Trainee
 ** Date: Septemper 2013
 ** Description: This program is designed to management the feedback and testing
 ** processes of Certification Training by SoftQuest Systems Limited.
 **  
 ** -----------------------------------------
 ** Filename: /public/index.php
 ** Version: 1.0
 ** -----------------------------------------
 ** This is the landing page of the application
 ** 
 **/


/* Includes */
require_once '../includes/initialize.php';
include_layout_template('header.php');

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
					
				<!-- Intro -->
					<section id="intro">
					
			

						<div class="actions">
						
							<a href="student_home.php" class="button button-big">I'm a Student</a>
							<a href="#" class="button button-alt button-big">I'm a Trainer</a>
						</div>
					
					</section>

			</div>
		</div>
	</div>
</div>
    
<?
include_layout_template('footer.php');
?>