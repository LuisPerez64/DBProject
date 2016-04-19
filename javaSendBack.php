<?php
require_once './DBFunctions.php';
function _begin() {
	if(!array_key_exists('SID#', $_POST)){ // Assume that they key always exists from Java Side
	return False; // Do something here, but shouldn't have to do much in the realm of what is actually happening.
	}
$return = "";
// Test if the person exists in the DB
$sid = $_POST['SID#'];
$query = "Select * FROM students where SID = $sid";
$result = QueryDB($query, 3);
if(!mysqli_num_rows($result)){ // This person does not exists, handle that accordingly
	$return = "Person does not exists in the DB";
} else {
	$q = mysqli_fetch_array($result); // Get the data from the queries
	$name = $q['name']; $SID = $q['SID']; $IID = $q['IID']; $major = $q['major'];
								$degreeHeld = $q['degreeheld']; $career = $q['career'];
	//The Data that will be sent back to the caller.
	$query = "SELECT name from instructors where IID='$IID'";
	$advisor = mysqli_fetch_array(QueryDB($query,3))['name'];
	$ret = eligibleTograduate($sid);
	$return .= "{
		<br>'grad': $ret[canThey],
		<br>'Name': $name,
		<br>'StudentID': $SID,
		<br>'GPA': $ret[GPA],
		<br>'Advisor': $advisor,
		<br>'Major': $major,
		<br>'career': $career,
		<br>'degreeHeld': $degreeHeld,
		<br>'courseList':["; // Append the course list here.
	$return .= _gradeData($sid)."<br>]}";
	} // Maybe integrate some JS to get things rolling if need be.
return $return;
}/*
Every Grade Element is returned in the format
{CourseName: ..,Grade: .. , ... },
*/
// Take the data that is here, and format it to a JSON object, like above. This can be done after
// FOr now just work on getting the base data back from the above form.
function _gradeData($sid) {
	$query =
	"SELECT courses.name as cName,  enrollment.SecID as secID,
			enrollment.grade as grade, enrollment.yearID as yearID,
			enrollment.semesterID as semID
	FROM  students, enrollment, courses
	WHERE enrollment.SID = students.SID AND enrollment.CID = courses.CID AND students.SID = $sid
	ORDER BY yearID, semID,courses.CID";
	$ret = QueryDB($query, 3);
	$out = "";
	while ($row = mysqli_fetch_array($ret)){
		$q = $row['cName']; $w = $row['grade']; $e = $row['yearID'];
		$r = $row['semID']; $t = $row['secID'];
		$out.="<br>";
		$out .= "{courseName: $q , grade: $w, yearTaken: $e, semester: $r, section: $t},";
	}
	$out = substr($out, 0, -1); // Remove that last ,
	return $out;
}
// Send this file back to the server on the call.
$return = _begin();

if($return)
echo "<form method='POST' id='retForm'>
				<input type=hidden value=$return>
		</form> 
	<script>
	document.getElementById('retForm').submit();
	</script>";
?>