<?php
require_once './DBFunctions.php';
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

// Course list gets populated from function below
$return .= "{
	'Name': $name,
	'StudentID': $SID,
	'GPA': $GPA,
	'Advisor': $advisor,
	'Major': $major,
	'career': $career,
	'degreeHeld': $degreeHeld,
	'courseList':{

	}
}";
} // Maybe integrate some JS to get things rolling if need be.


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
	$out = "
	<table style='width:100%'>
	<tr>
		<th>Course Name</th>
		<th>Grade</th>
		<th>Year Taken</th>
		<th>Semester</th>
		<th>Section</th>
	</tr>";
	while ($row = mysqli_fetch_array($ret)){
		$q = $row['cName']; $w = $row['grade']; $e = $row['yearID'];
		$r = $row['semID']; $t = $row['secID'];
		$out .= "
		<tr>
			<td>$q</td>
			<td>$w</td>
			<td>$e</td>
			<td>$r</td>
			<td>$t</td>
		</tr>";
	}
	$out .= "</table>";
	echo $out;
}
// Send this file back to the server on the call.
echo $return;
?>