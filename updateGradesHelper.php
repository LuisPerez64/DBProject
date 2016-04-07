<?php
require_once './DBFunctions.php';
// Post Contains all of the course ID's and the new grades for them
	$sid = $_POST['SID'];
	unset($_POST['SID']);
	foreach($_POST as $key => $value) { // Key: CID value:Grade
		updateGrades($sid, $key, $value);
	}
	echo "<h2>Update Complete. Heading to current Progress</h2> 
	<script>
	function wait3Secs(){
		location.href = './currentProgress.php';
	}
	setTimeout(wait3Secs, 3000);
	</script>";

		//echo $key."  ".$value."<br>";
		//echo $key."  ".$value."<br>";
	/*while($row = $_POST){
		updateGrades($_row['SID'], $row[], $grade)
	}*/
//	echo "Here";
?>