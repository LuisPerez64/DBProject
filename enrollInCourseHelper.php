<?php
require_once './DBFunctions.php';
//echo $_POST['enroll'];
if(!array_key_exists('enroll',$_POST)){
	echo"
	<script type='text/javascript'>
		location.href = './studentOperations.php'
	</script>";	
}
foreach ($_POST['enroll'] as $value) {
	$row = unserialize(base64_decode($value));
	addClass($row['SID'], $row['CID'], 'NA', $row['SEM'], $row['Year'], $row['secID']);	
	
}
echo "
	<h1>Succesfully Inserted Student into Selected Courses.</h1>
	<h2>Redirection in 3 Seconds</h2>
		<script>
		function wait3Secs(){
			location.href = './enrollInCourse.php';
		}
	setTimeout(wait3Secs, 3000);
	</script>"
?>