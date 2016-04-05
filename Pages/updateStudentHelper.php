<?php
require_once './DBFunctions.php';

$_name = $_POST['_name'];
$_sid = $_POST['_sid'];
$_iid = $_POST['_iid'];
$_major = $_POST['_major'];
$_degreeHeld = $_POST['_degreeHeld'];
$_career = $_POST['_career'];

$query =
"UPDATE students
	SET name = '$_name', IID = '$_iid', major = '$_major', degreeheld = '$_degreeHeld', career = '$_career'
WHERE SID = $_sid
";

	if(QueryTF($query)){
		echo "
		<h1>Succesfully Updated Student Data.</h1>
		<h2>Redirection in 3 Seconds</h2>
	<script>
	function wait3Secs(){
		location.href = './studentOperations.php';
	}
	setTimeout(wait3Secs, 3000);
	</script>
	";
	}else {
	echo "
	<h1>Something went wrong. Heading Back to Update.</h1>
	<h2>Redirection in 3 Seconds</h2>
	<script>
	function wait3Secs(){
		location.href = './updateStudent.php';
	}
	setTimeout(wait3Secs, 3000);
	</script>
	";
	}
//
?>