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
										<h1>SoftQuest Training Management System</h1>
									
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
													<div id="form_container">
	
		<h1><a>Untitled Form</a></h1>
		<form id="form_712122" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>Untitled Form</h2>
			<p>This is your form description. Click here to edit.</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Text </label>
		<div>
			<input id="element_1" name="element_1" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Paragraph </label>
		<div>
			<textarea id="element_2" name="element_2" class="element textarea medium"></textarea> 
		</div> 
		</li>		<li id="li_9" >
		<label class="description" for="element_9">Checkboxes </label>
		<span>
			<input type="checkbox" value="1" />
			<label class="choice" for="element_9_1">First option</label>
			<input id="element_9_2" name="element_9_2" class="element checkbox" type="checkbox" value="1" />
			<label class="choice" for="element_9_2">Second option</label>
			<input id="element_9_3" name="element_9_3" class="element checkbox" type="checkbox" value="1" />
			<label class="choice" for="element_9_3">Third option</label>

		</span> 
		</li>		<li id="li_10" >
		<label class="description" for="element_10">Multiple Choice </label>
		<span>
			<input id="element_10_1" name="element_10" class="element radio" type="radio" value="1" />
<label class="choice" for="element_10_1">First option</label>
<input id="element_10_2" name="element_10" class="element radio" type="radio" value="2" />
<label class="choice" for="element_10_2">Second option</label>
<input id="element_10_3" name="element_10" class="element radio" type="radio" value="3" />
<label class="choice" for="element_10_3">Third option</label>

		</span> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Number </label>
		<div>
			<input id="element_3" name="element_3" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Name </label>
		<span>
			<input id="element_4_1" name= "element_4_1" class="element text" maxlength="255" size="8" value=""/>
			<label>First</label>
		</span>
		<span>
			<input id="element_4_2" name= "element_4_2" class="element text" maxlength="255" size="14" value=""/>
			<label>Last</label>
		</span> 
		</li>		<li id="li_11" >
		<label class="description" for="element_11">Drop Down </label>
		<div>
		<select class="element select medium" id="element_11" name="element_11"> 
			<option value="" selected="selected"></option>
<option value="1" >First option</option>
<option value="2" >Second option</option>
<option value="3" >Third option</option>

		</select>
		</div> 
		</li>		<li class="section_break">
			<h3>Section Break</h3>
			<p></p>
		</li>		<li id="li_6" >
		<label class="description" for="element_6">Email </label>
		<div>
			<input id="element_6" name="element_6" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_7" >
		<label class="description" for="element_7">Phone </label>
		<span>
			<input id="element_7_1" name="element_7_1" class="element text" size="3" maxlength="3" value="" type="text"> -
			<label for="element_7_1">(###)</label>
		</span>
		<span>
			<input id="element_7_2" name="element_7_2" class="element text" size="3" maxlength="3" value="" type="text"> -
			<label for="element_7_2">###</label>
		</span>
		<span>
	 		<input id="element_7_3" name="element_7_3" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_7_3">####</label>
		</span>
		 
		</li>		<li id="li_8" >
		<label class="description" for="element_8">Date </label>
		<span>
			<input id="element_8_1" name="element_8_1" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_8_1">MM</label>
		</span>
		<span>
			<input id="element_8_2" name="element_8_2" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_8_2">DD</label>
		</span>
		<span>
	 		<input id="element_8_3" name="element_8_3" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_8_3">YYYY</label>
		</span>
	
		<span id="calendar_8">
			<img id="cal_img_8" class="datepicker" src="calendar.gif" alt="Pick a date.">	
		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_8_3",
			baseField    : "element_8",
			displayArea  : "calendar_8",
			button		 : "cal_img_8",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="712122" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
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