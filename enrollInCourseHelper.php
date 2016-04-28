<?php
require_once './DBFunctions.php';
//echo $_POST['enroll'];
if(!array_key_exists('enroll',$_POST)){
	echo"
	<script type='text/javascript'>
		location.href = './studentOperations.php'
	</script>";	
}
echo $_POST['fromNew'];
if(array_key_exists('fromNew', $_POST)){
foreach ($_POST['enroll'] as $value) {
	$row = unserialize(base64_decode($value));
	addClass($_POST['SID'], $row['CID'], 'NA', $_POST['sem'], date('Y'), $_POST['secID']);	
}

echo "
	<form action='insertGrades.php' method=POST id=submitMe>
		<input type=hidden value=$_POST[SID] name='SID#'/>
	</form>
	<script>
    	document.getElementById('submitMe').submit(); // SUBMIT FORM
	</script>
	";
}else{
foreach ($_POST['enroll'] as $value) {
	$row = unserialize(base64_decode($value));
	addClass($row['SID'], $row['CID'], 'NA', $row['SEM'], $row['Year'], $row['secID']);	
echo "
	<h1>Succesfully Inserted Student into Selected Courses.</h1>
	<h2>Redirection in 3 Seconds</h2>
		<script>
		function wait3Secs(){
			location.href = './enrollInCourse.php';
		}
	setTimeout(wait3Secs, 3000);
	</script>";
}


}
?>