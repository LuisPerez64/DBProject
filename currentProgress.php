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
								location.href = './currentProgress.php';
							};
							</script>
							";
							}
							else { // SID in DB Student Exists Display Data
							$q = mysqli_fetch_array($result);
							$name = $q['name']; $SID = $q['SID']; $IID = $q['IID']; $major = $q['major'];
							$degreeHeld = $q['degreeheld']; $career = $q['career'];
							echo"<h4>Displaying Information For $name</h4>";
              $gpa = eligibleToGraduate($sid); 
              $query = "SELECT name FROM instructors WHERE IID = $IID";
              $advisor = QueryDB($query)[0];
              $p = number_format($gpa['GPA'],2); // Output normalized GPA	
              echo"
              <table style = 'width:50%'>
                <tr>
                  <th>Name</th>
                  <td>$name</td>
                </tr>
                <tr>
                  <th>Student ID#</th>
                  <td>$SID</td>
                </tr>
                <tr>
                  <th>GPA</th>
                  <td>$gpa[letterGrade]: $p </td>
                </tr>
                <tr>
                  <th>Advisor</th>
                  <td>$advisor</td>
                </tr>  
                <tr>  
                  <th>Major</th>
                  <td>$major</td>
                </tr>
                <tr>
                  <th>Degree Held</th>
                  <td>$degreeHeld</td>
                </tr>
                <tr>
                  <th>Scholastic Career</th>
                  <td>$career</td>
                </tr>
                <tr>
                	<th>Grauation Eligibility:</th>
                </tr>
                <tr>
                	<td>$gpa[canThey]</td>
                </tr>	
              </table>
              <button id = 'backToOps' class='float-left submit-button'>
              Return To Student Operations
              </button>

              <script type='text/javascript'>
              document.getElementById('backToOps').onclick = function () {
                location.href = './studentOperations.php';
              };
              </script>
              <br>";
              // Attain the courses that PS has taken.
              echo "<h4><br>$name's Scholastic Information</h4>";
              gradeData($SID);
							}
						}else { // First Time Around ask for SID
						echo "
						<p>Please Input the Students ID# which you wish to view the progress of</p>
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