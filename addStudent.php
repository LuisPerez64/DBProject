<?php
require_once('./DBFunctions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Insert A Student Results</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="bluebliss.css" />
	</head>
	<body>
		<div id="mainContentArea">
			<div id="contentBox">
				<div id="title">Insert A Student Results</div>
				
				<div id="linkGroup">
					<div class="link"><a href="home.php">Home</a></div>
					<div class="link"><a href="studentOperations.php">Student Operations</a></div>
					<div class="link"><a href="graduationList.php">Grads List</a></div>
					<div class="link"><a href="about.php">Lol</a></div>
				</div>
				
				<div id="blueBox">
					<div id="header"></div>
					<div class="contentTitle">Insert A Student Results</div>
					<div class="pageContent">
						<?php
						// Get the data that was relayed. Place it where it needs to be.
						$_name = $_POST['_name'];
						$_sid = $_POST['_sid'];
						$_iid = $_POST['_iid'];
						$_major = $_POST['_major'];
						$_degreeHeld = $_POST['_degreeHeld'];
						$_career = $_POST['_career'];
						$didIAdd = addStudent($_name,$_sid,$_iid,$_major, $_career, $_degreeHeld);
						$title = "<title>";
						$body = "<body>";
							if(!$didIAdd){
								$title = $title."Was Unable to Add Student. Unique SID Needed.";
								$body =$body."\n<h1>Was Unable to Add Student. Unique SID Needed.</h1>".
								"<h2>Please Click Button Below to Return to addition page</h2>";
								$locationToRedirect = "./insertStudent.php";
							}else {
								$title = $title."Student Added Successfully.";
								$body = $body."\n<h1>Student Added Successfully.</h1>".
								"<h2>Click Button To go Back to Home page</h2>";
								$locationToRedirect = "./home.php";
							}
							$title = $title."</title>";
						$body = $body."\n</body>";
						echo
						"
							<html>
										<head>
										$title
									</head>
									$body
									<button id='RedirectWhere' class = 'float-left submit-button'>
									CLICK ME!!
									</button>
									<button id='Insert Another' class = 'float-right submit-button'>
									Back To Insert
									</button>
							<script type='text/javascript'>
							document.getElementById('RedirectWhere').onclick = function () {
								location.href = '$locationToRedirect';
							};
							document.getElementById('Insert Another').onclick = function() {
								location.href = './insertStudent.php'
							};
							</script>

						</html>
						"
						?>
					</div>
					<div id="footer"><a href="http://www.aszx.net">Template Developed</a> by <a href="http://www.bryantsmith.com">bryant smith</a></div>
				</div>
			</div>
		</div>
	</body>
</html>