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
									
									<!-- Nav -->
										<nav id="nav" class="mobileUI-site-nav">
											<ul>
												<li><a href="index.html">Home</a></li>
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
													<article class="box is-post">
														<a href="http://facebook.com/DreametryDoodle" class="image image-full"><img src="images/pic01.jpg" alt="" /></a>
														<header>
															<h2>We wan Know Wetin You Think</h2>
															<span class="byline">Which Course You take?</span>
															<form>
																<select>
																<option>Microsoft Excel Intermediate</option>
																<option>Microsoft Excel Advanced</option>
																
																</select>
																<br>
																<input type="submit" value="Begin Feedback!" />
															</form>
														</header>
														
													</article>

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