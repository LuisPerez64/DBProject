<?php
require_once('./DBFunctions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Transfer New Student Transcript</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="bluebliss.css" />
	</head>
	<body>
		<div id="mainContentArea">
			<div id="contentBox">
				<div id="title">Transfer New Student Transcript</div>
				
				<div id="linkGroup">
					<div class="link"><a href="home.php">Home</a></div>
					<div class="link"><a href="studentOperations.php">Student Operations</a></div>
					<div class="link"><a href="graduationList.php">Grads List</a></div>
				</div>
				
				<div id="blueBox">
					<div id="header"></div>
					<div class="contentTitle">Update Student Transcript</div>
					<div class="pageContent">
						<?php
							$sid = $_POST['SID#'];
							$query = "SELECT name FROM students where SID = '$sid'";							// SID in DB Student Exists Display Data
							$result = QueryDB($query, 3);
							$name = mysqli_fetch_array($result)['name'];
							echo "<h2>$name Can Be enrolled in</h2>";
							displayCourseList($sid);

						?>
					</div>
					<div id="footer"><a href="http://www.aszx.net">Template Developed</a> by <a href="http://www.bryantsmith.com">bryant smith</a></div>
				</div>
			</div>
		</div>
	</body>
</html>