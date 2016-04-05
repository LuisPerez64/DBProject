<?php
require_once('./DBFunctions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Current Progress</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="bluebliss.css" />
	</head>
	<body>
		<div id="mainContentArea">
			<div id="contentBox">
				<div id="title">Current Progress</div>
				
				<div id="linkGroup">
					<div class="link"><a href="home.php">Home</a></div>
					<div class="link"><a href="studentOperations.php">Student Operations</a></div>
					<div class="link"><a href="graduationList.php">Grads List</a></div>
					<div class="link"><a href="about.php">Lol</a></div>
				</div>
				
				<div id="blueBox">
					<div id="header"></div>
					<div class="contentTitle">Current Progress</div>
					<div class="pageContent">
						<?php
						if (array_key_exists('SID#',$_POST)){ // Not the first time on this page. Actual Output.
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
								location.href = './updateStudent.php';
							};
							</script>
							";
							}
							else { // SID in DB Student Exists Display Data
							$q = mysqli_fetch_array($result);
							$name = $q['name']; $SID = $q['SID']; $IID = $q['IID']; $major = $q['major'];
							$degreeHeld = $q['degreeheld']; $career = $q['career'];
							echo"<h3>Displaying Information For $name</h3>";
              eligibleToGraduate($sid); // Just testing for now. 
              echo"
							<form action='./studentOperations.php'>
								<table border='0'>
									<tbody>
										<tr>
											<td>Name</td>
											<td align='left'>
												<input type='text' name='_name' size='20' maxlength='20'
												value = '$name'/>
											</td>
										</tr>
										<tr>
											<td>Student ID#</td>
											<td align='left'>
												<input type='text' name='_sid' size='20' maxlength='20'
												value = '$SID' readonly/>
											</td>
										</tr>
										<tr>
											<td>Advisor</td>
											<td>
												<input type='text' name='_iid' size='5' maxlength='5'
												value = '$IID'/>
											</td>
										</tr>
										<tr>
											<td>Major</td>
											<td>
												<input type='text' name='_major' size='5' maxlength='5'
												value = '$major'/>
											</td>
										</tr>
										<tr>
											<td>Degree Held</td>
											<td>
												<input type='text' name='_degreeHeld' size='20' maxlength='20'
												value = '$degreeHeld'/>
											</td>
										</tr>
										<tr>
											<td>Scholastic Career</td>
											<td>
												<input type='text' name='_career' size='20' maxlength='20'
												value = '$career'/>
											</td>
										</tr>
										<tr>
											<td colspan='5' align='center'>
												<input type='submit' value='Return To Student Operations' />
											</td>
										</tr>
									</tbody>
								</table>
							</form>";
							}
						}else { // First Time Around ask for SID
						echo "
						<p>Please Input the Students ID# which you wish to alter the Data of.</p>
						<form action='./currentProgress.php' method = 'post'>
							SID <input type='text' name='SID#'>
							<br>
							<input type='submit' value='Get Info'>
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