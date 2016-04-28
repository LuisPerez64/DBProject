<?php
require_once('./DBFunctions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Enroll Student In Course</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="bluebliss.css" />
	</head>
	<body>
		<div id="mainContentArea">
			<div id="contentBox">
				<div id="title">Enroll Student In Course</div>
				
				<div id="linkGroup">
					<div class="link"><a href="home.php">Home</a></div>
					<div class="link"><a href="studentOperations.php">Student Operations</a></div>
					<div class="link"><a href="graduationList.php">Grads List</a></div>
				</div>
				
				<div id="blueBox">
					<div id="header"></div>
					<div class="contentTitle">Enroll Student in Course(s)</div>
					<div class="pageContent">
						<?php
						if (array_key_exists('SID#',$_POST)){
							$sid = $_POST['SID#'];
							$query = "Select * FROM students WHERE SID= $sid";
							$result = QueryDB($query, 3);
							
							if(!mysqli_num_rows($result)) {// Student Does not exists
							echo "
							<p>This SID: $sid Does not match anyone in the Database</p>
							<button id='otherSID' class = 'float-left submit-button'>
							Input Other SID
							</button>
							<script type='text/javascript'>
							document.getElementById('otherSID').onclick = function () {
								location.href = './enrollInCourse.php';
							};
							</script>
							";
							}
							else { // SID in DB Student Exists Display Data
							$name = mysqli_fetch_array($result)['name'];
							echo "<h2>$name Is Eligible to Take</h2>";
							classesAvailable($sid);
							}
						}else { // First Time Around ask for SID
								$query = "SELECT name, sid from students";
						$ret = QueryDB($query, 3);
						$out="<select name=SID#><option/> ";
						while($row = mysqli_fetch_array($ret)){
							$out.="<option value=$row[sid]>$row[name]</option>";
						}
						$out .= "</select>";

						echo "
						<p>Please Input the Students ID# which you wish to view the progress of</p>
						<form method='post'>
							$out
							<input type=submit value='Enroll Student In Courses'/>	
						</form>
						";
						}
						?>
					</div>
					<div id="footer"><a href="http://www.aszx.net">Template Developed</a> by <a href="http://www.bryantsmith.com">bryant smith</a></div>
				</div>
			</div>
		</div>
	</body>
</html>