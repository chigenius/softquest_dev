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


$form_id = !empty($_GET['formid'])? $_GET['formid'] : $_POST['formid'];
echo $form_id;
echo "hey";
$form = Form::find_by_id($form_id);
//$questions = 
?>

		<!-- Header Wrapper -->
			<div id="header-wrapper">
				<div class="5grid-layout">
					<div class="row">
						<div class="12u">
						
							<!-- Header -->
								<section id="header">
									
									<!-- Logo -->
										<h1>SoftQuest Training Management System</h1>
									
									<!-- Nav -->
										<nav id="nav" class="mobileUI-site-nav">
											<ul>
												<li><a href="">Home</a></li>
												<li>
													<a href="">Take Test</a>
													
												</li>
												<li class="current_page_item"><a href="#">Course Feedback</a></li>
												
											</ul>
										</nav>

								</section>

						</div>
					</div>
				</div>
			</div>
		
		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div class="5grid-layout">
					<div class="row">
						<div class="12u">
							
							<!-- Portfolio -->
								<section>
									<div class="5grid">
										<div class="row">
											<div class="12u mobileUI-main-content">
												
												<!-- Content -->
													<div id="form_container">
	
		<h1><a><?echo $form->FORM_TITLE;?></a></h1>
		<form id="form_712122" class="appnitro"  method="post" action="submitform.php">
					<div class="form_description">
			<h2><?echo $form->FORM_TITLE; ?></h2>
			<p><?echo $form->INSTRUCTIONS; ?></p>
		</div>						
			
			<?Question::display_qns($form->FORM_ID);?>
			<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />

		</form>	
	</div>

											</div>
										</div>
									</div>
								</section>

						</div>
					</div>
				</div>
			</div>

<?
include_layout_template('footer.php');
?>