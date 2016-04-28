<?php
require_once('./DBFunctions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Update Student Info</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="bluebliss.css" />
	</head>
	<body>
		<div id="mainContentArea">
			<div id="contentBox">
				<div id="title">Update Student Info</div>
				
				<div id="linkGroup">
					<div class="link"><a href="home.php">Home</a></div>
					<div class="link"><a href="studentOperations.php">Student Operations</a></div>
					<div class="link"><a href="graduationList.php">Grads List</a></div>
				</div>
				
				<div id="blueBox">
					<div id="header"></div>
					<div class="contentTitle">Update Info</div>
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
								location.href = './updateStudent.php';
							};
							</script>
							";
							}
							else { // SID in DB Student Exists Display Data
							$q = mysqli_fetch_array($result);
							$name = $q['name']; $SID = $q['SID']; $IID = $q['IID']; $major = $q['major'];
							$degreeHeld = $q['degreeheld']; $career = $q['career'];
							echo"
							<p>Change All values needed to be changed. Please Do not Alter SID. When done with changes Submit.</p>
							<form action='./updateStudentHelper.php' method='post'>
								<!--
								Dictates a table object. Explicitly defines its body, as well as the inputs for its fields.
								-->
								<table border='10'>
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
												<input type='submit' value='Update Student Data' />
											</td>
										</tr>
									</tbody>
								</table>
							</form>";
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
						<form method = 'post'>
							$out
							<input type=submit value='Update Student Data'/>	
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