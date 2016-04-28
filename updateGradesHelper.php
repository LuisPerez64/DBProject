<?php
require_once './DBFunctions.php';
// Post Contains all of the course ID's and the new grades for them
	$sid = $_POST['SID'];
	unset($_POST['SID']);
	foreach($_POST as $key => $value) { // Key: CID value:Grade
		updateGrades($sid, $key, $value);
	}
	echo "<h2>Update Complete. Heading to current Progress</h2> 
	<form action='currentProgress.php' method=POST id=submitMe>
		<input type=hidden value=$sid name='SID#'/>
	</form>
	<script>
	function wait3Secs(){
    	document.getElementById('submitMe').submit(); // SUBMIT FORM
	}
	setTimeout(wait3Secs, 1500);
	</script>";

		//echo $key."  ".$value."<br>";
		//echo $key."  ".$value."<br>";
	/*while($row = $_POST){
		updateGrades($_row['SID'], $row[], $grade)
	}*/
//	echo "Here";
?>