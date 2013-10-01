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

$form = Form::find_by_id("F1");
//$form_id = !empty($_GET['form_id'])? $_GET['form_id'] : $_POST['form_id'];
$questions = Question::find_by_formid('F1');
//$test = $_POST['F1_QN1'];
// $test;
$form_id = 'F1';
$save_sql = "INSERT INTO ".$form_id."_SUBMISSIONS (";

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
												<li><a href="/">Home</a></li>
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
	
		<h1><a>Thank You!</a></h1>
			<?
			$i = 0;
			foreach ($questions as $question){
				$qid = $question->Q_ID;

				$answer = !empty($_GET[$question->Q_ID])? 
						$_GET[$question->Q_ID] : $_POST[$question->Q_ID];
				if ($i==0)
					$save_sql .= "{$qid}";
				else
					$save_sql .= ",{$qid}";
				$i++;
			}
			$save_sql .= ") VALUES (";
			$x = 0;
			foreach ($questions as $question){
				$qid = $question->Q_ID;

				$answer = !empty($_GET[$question->Q_ID])? 
						$_GET[$question->Q_ID] : $_POST[$question->Q_ID];
				if ($x==0)
					$save_sql .= "'{$answer}'";
				else
					$save_sql .= ",'{$answer}'";
				$x++;
			}
			$save_sql .= ")"; 
			//echo $save_sql;
			global $database;
			
			$database->query($save_sql);
			
			echo "Thank You for Your Submission";
?>

	
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