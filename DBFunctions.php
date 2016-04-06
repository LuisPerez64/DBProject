<?php
 
// Helpful for error logging. Got this function from Stack OVerflow.
// Allows direct output to the console for debugging reasons.
function debug_to_console( $data ) {
    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
    echo $output;
}
//Handles INSERT, UPDATE, DELETE, DROP, EXISTS which result in T/F Returns
function QueryTF($query) {
	//Set up a connection to the given DB
	$connect = mysqli_init();
	//Test that the connection was actually instantiated.
	if( !$connect)
		die("MySQLi Initialization failed.");
	// Establish actual connection handle to DB.
	if (!mysqli_real_connect($connect, "localhost", "root", ""))
		die("Connect Error: " . mysqli_connect_error());
	// Select the DB to be used.
	$db = mysqli_select_db ($connect, "ProjectsDB") or 
		die ('Could not select database'); 	
	$result = mysqli_query($connect, $query) or 
		die ('Query failed: '.mysql_error());

		// Close off the connection to the DB.
	mysqli_close($connect);	
	// IS either true or False	
	return $result;	
}

//Handles SELECT, SHOW, ... Only using Select for now, but expansion
function QueryDB($query, $type = 1) {
	//Set up a connection to the given DB
	$connect = mysqli_init();
	//Test that the connection was actually instantiated.
	if( !$connect)
		die("MySQLi Initialization failed.");
	// Establish actual connection handle to DB.
	if (!mysqli_real_connect($connect, "localhost", "root", ""))
		die("Connect Error: " . mysqli_connect_error());
	// Select the DB to be used.
	$db = mysqli_select_db ($connect, "ProjectsDB") or 
		die ('Could not select database'); 	
	$result = mysqli_query($connect, $query) or 
		die ('Query failed: '.mysql_error());
	
	// Close out the database.
	mysqli_close($connect);
	
	// Edge Cases... Should just write a new one but -_-
	// Was unable to find something at all. Query Failed.
	if(!$result){
		return 0;
	} else if ($type == 2){ // Counting Rows from Select, still done in caller.
		return mysqli_num_rows($result); // DOne to validate if PS exists. Only for one Query
	} else if ($type == 3) { // getGrades Query. This is going to look really ugly after it all...
		return $result; // Main one that is used through this all. 
	}

	//Rarely get down here, but still used in some functions.
	//Do with the results whatever needs to be done on the caller side.
	$result2 = mysqli_fetch_array($result);
	
	// Handles cases where it does not return an array, ie a bool, which would be False
	if(!$result2){ // If !False then return the object.
		return $result;
	} // Else return that bool.
	return $result2;
}

//Add a student into the given DB. Does not add if duplicate SID
function addStudent($_name,$_sid,$_iid,$_major, $_career, $_degreeHeld){
	$_query = "SELECT name FROM students WHERE SID = '$_sid'";

	$query = "INSERT INTO students (SID, name, IID, major, degreeHeld, career)
    	VALUES('$_sid', '$_name', '$_iid', '$_major', '$_degreeHeld', '$_career')";
   // Test to see if the SID is already in the DB, it is the main Key no duplicates.
   if(!QueryDB($_query, 2))
	   return QueryTF($query);
	else
		return 0;	
}	

//Helper function for the insertion of a student. Relays an Instructor
// Based on the amount of students the instructor currently has, and 
// also relays a new SID# 
function getAnElt($choice){
	if( $choice == 1){
		$query = "SELECT IID, COUNT(DISTINCT SID) as totalStudents
    FROM students
    GROUP BY IID
    ORDER BY totalStudents ASC
    LIMIT 1;
	";
	} else if ($choice == 2){
		$query = "SELECT SID
	FROM students
	ORDER BY SID DESC
	LIMIT 1;
	";
	}
	$result = QueryDB($query)[0];
	if ($choice == 2)
		$result++;
	return $result;
}

// External call to print grade data of the user to the caller.
function gradeData($sid) {
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

// External function to test graduation, and display that to the user.
function eligibleToGraduate($sid){ // Directly relayed to the user. Done here to not muddy up HTML Code more -_-
	$ret = canIGraduate($sid);
	if($ret['canI']){ // Student Can Graudate
		$reason = "Student is eligible to graduate.";
	}else {
		switch($ret['reason']){
			case 0:
				$missingCredits = 30 - $ret['totalCredits'];
				$reason = "Have not amassed needed total credits. Missing: $missingCredits credits";
				break;
			case 1:
				$reason = 'Missing Core Courses. May need to speak with an Advisor';	
				break;
			case 2:
				$gpa = $ret['GPA'];
				$reason = "GPA is Below the one needed to Graduate. Current: $gpa";
				break;
			case 3:
				$lessThanBCount = $ret['lessThanBCount'];
				$reason = "Too many courses with a below B Avg. Currently: $lessThanBCount";
				break;
			case 4:
				$reason = "Student needs to take course(s) Listed:<br>";
				foreach($ret['conditions'] as $value) { // Append the list of reasons.
							$reason .= $value."<br>";
				}	 	
				break;
			default:
				$reason = "Unknown reason. The universe doesn't want this for you.";
				break;
		}
		$reason = "<b>Student is unable to Graduate</b><br>".$reason;
	}

	echo $reason."<br>";
	return $ret; // Just in case something else is needed. 
}

// Main Function,  determines if a student is able to graduate. If not relays
// The reason, if based on conditions, relays all the conditions that are not met.
function canIGraduate($sid){
	$sidArray = studentGPA($sid);
	$returnArray = array(
		'canI' => False,
		'reason' => -1,
		'conditions' => array(),
		'totalCredits' => $sidArray['totalCredits'],
		'GPA' =>$sidArray['GPA'],
		'lessThanBCount' => $sidArray['lessThanBCount'],
		'letterGrade' => $sidArray['letterGrade']);
	$conditionsMet = checkConditions($sid);	
	$reason = 0;

	if($sidArray['totalCredits'] >= 30){ 
		$reason++;
		if($sidArray['coreTaken'] >=4){ // If this and above met, 12 Credits / 18 Credits satisfied.
			$reason++;
			if($sidArray['GPA'] >= 3.0){
				$reason++;
				if($sidArray['lessThanBCount'] <= 2){
					$reason++; 
					if($conditionsMet['met'])
						$returnArray['canI'] = True;
				}
			}		
		}					
	}
	$returnArray['reason'] = $reason;
	$returnArray['conditions'] = $conditionsMet['classesMissing'];
	return $returnArray;
}

// Used for the graduation check. More or less just returns if conditions set are met.
function checkConditions($sid){
// Validate the condition exists.
	$returnArray = array(
		'met' => True,
		'classesMissing' => array());

	$query = 
	"SELECT * 
	FROM students, conditions
	WHERE students.SID = conditions.SID AND students.SID = $sid;  
	";
	$conditionsExist = QueryDB($query, 3);
	if(mysqli_num_rows($conditionsExist)) { // The conditions exist in the DB
		// Check that the conditions have been met.

		while($row = mysqli_fetch_array($conditionsExist)){
			$checkIfTaken = $row['CID'];
			$query = 
			"SELECT * FROM students, enrollment
			WHERE enrollment.SID = students.SID AND 
				  students.SID = $sid AND enrollment.CID = $checkIfTaken
			";
			$taken = QueryDB($query, 3);
			if(!mysqli_num_rows($taken)){ // Student did not yet take the course
				$query = 
				"SELECT name
				FROM courses
				WHERE CID = $checkIfTaken;
				";
				$taken = QueryDB($query, 3);
				$name = mysqli_fetch_array($taken)['name'];

				$returnArray['classesMissing'][]= $name; // Push the name to the returned Array
				$returnArray['met'] = False; // They have not met the conditions 	
			}
		}
	} 

	return $returnArray;
}

// Gets all of the grades that a student has gotten through their career.
function getGrades($sid){
$query = "SELECT enrollment.grade as 'Grade', courses.groupID as 'GID', courses.credits as 'Credits'
 FROM students, enrollment, courses
  WHERE students.SID = enrollment.SID AND courses.CID = enrollment.CID
  AND students.SID = $sid
  Order BY students.name, courses.groupID";
  // Grade, and GroupID returned
  return QueryDB($query, 3);
}

// Made external to make sure that the functionality is available anywhere.
function letterToNum($letterGrade){
	$gradeMap = array('A'  => 4.0, 'A-' => 3.7,
					  'B+' => 3.3, 'B'  => 3.0, 'B-' => 2.7,
					  'C+' => 2.3, 'C'  => 2.0, 'C-' => 1.7,
					  'D+' => 1.3, 'D'  => 1.0,
					  'F' => 0, 'NA' => 0);

	return $gradeMap[$letterGrade];				  	
}

// Relays the GPA that the student has amassed for their career.
// Also relays most of the information that is needed to validate grad eligibility.
function studentGPA($sid){
	$gradeMap = array('A'  => 4.0, 'A-' => 3.7,
					  'B+' => 3.3, 'B'  => 3.0, 'B-' => 2.7,
					  'C+' => 2.3, 'C'  => 2.0, 'C-' => 1.7,
					  'D+' => 1.3, 'D'  => 1.0,
					  'F' => 0);
	$return = array('GPA' => 0, 
		'lessThanBCount' => 0, 
		'totalCredits' => 0, 
		'letterGrade' => '', 
		'coreTaken' => 0);

// Assumes the SID exists. Done elsewhere.
	$QP = 0;
	$q = getGrades($sid);
	while($filterMe = mysqli_fetch_array($q)) {
		$letterGrade = $filterMe['Grade'];
		$group = $filterMe['GID'];
		$credits = $filterMe['Credits'];
		$grade = letterToNum($letterGrade);
		if($letterGrade == 'NA') // Do not count courses they haven't finished.
			continue;
			debug_to_console($letterGrade);
		if ($grade < 3.0)
			$return['lessThanBCount']++;
		if ($group != 0){
			$return['totalCredits'] += $credits;
			$QP += $credits * $grade;
		}

		//$filterMe['Grade']."  ".$filterMe['GID']."<br>";
	}
	if($return['totalCredits'] != 0)
		$return['GPA'] = ($QP/$return['totalCredits']);

	//Assumes preserved Order. A, A- , B+ ...
	foreach (array_reverse($gradeMap) as $key => $value) {
		if ($return['GPA'] > $value)
			$return['letterGrade'] = $key; 
	}

	$return['coreTaken'] = takenCore($sid);
//	echo $return['GPA']."<br>";
//	echo $return['letterGrade']."  ".$return['totalCredits']."<br>";
	return $return;
}

// Simple function to return the amount of core courses(GID(1,2,3,4)) that student has taken.
function takenCore($sid){
	$query1 = 
	"SELECT COUNT(DISTINCT courses.groupID) as coreTaken
     FROM students, enrollment, courses
     WHERE courses.CID = enrollment.CID AND enrollment.SID = students.SID AND 
     students.SID = $sid AND (courses.groupID <> 0 AND courses.groupID <> 1) ";
    $taken = QueryDB($query1)[0];
    //They have to have taken Algorithms
    $query2 = 
    "SELECT *
     FROM students, courses, enrollment
     WHERE students.SID = enrollment.SID AND courses.CID = enrollment.CID AND
     courses.name = 'Algorithms' AND students.SID = $sid 
    "; 
    $taken += QueryDB($query2, 2); // Just need it to exist.
    return $taken;
}

//Active class addition. Does not allow insertion if student has not met prereqs.
function addClass($sid, $cid, $grade, $semID, $yearID, $secID, $noInsert = False){
$query =
"SELECT CID
 FROM (SELECT prerequisite.PCID AS pRID
 FROM courses, prerequisite
 WHERE prerequisite.CID = courses.CID AND
	courses.CID = '$cid') AS SQ1, courses
 WHERE courses.CID = pRID";

 $result = QueryDB($query, 3);

 $numRows = mysqli_num_rows($result); // Check if the prereq exists.
 $preReqMet = True;
 if( $numRows ){ // Not sure of the need for the grade in the prereq to register.
 	$pRID = mysqli_fetch_array($result)['CID'];

  	$query =
 	"SELECT grade 
 	 FROM enrollment, students
 	 WHERE enrollment.SID = students.SID AND students.SID = $sid AND enrollment.CID = $pRID"; 

 	$result = QueryDB($query, 3);
 	$numRows = mysqli_num_rows($result);
 	if($numRows){
 		echo ""; // Do nothing for now. Leaving just in case something need
 		// be done.
 		// Don't know if the only prereq is finding out if the course has
 		// been taken before, and not adherence to the grade reveived.
 	} else // NumRows is 0, so they have not taken this course yet.
 		$preReqMet = False;
 }

// No Insert is for the displaying of courses student can take. Used by classes available.
 if(!$preReqMet or $noInsert)
 	return $preReqMet; // Do not attempt to insert the course, just return false.
 
 // Not checking for if the course exists already, but not too sure on what else. 
 //echo $sid."  ".$cid."<br>";
 $query = 
 "SELECT grade 
 FROM enrollment, students
 WHERE students.SID = $sid AND students.SID = enrollment.SID AND enrollment.CID = $cid";

 $taken = QueryDB($query, 2); // Should I insert or update.
 
 if($taken){
 	$query = 
 	"UPDATE enrollment
 	SET grade='$grade', semesterID = '$semID', yearID = '$yearID'
 	WHERE CID = '$cid' AND SID = '$sid'
 	";
 } else
 	$query =
 	"INSERT INTO enrollment
  	(CID, SecID, semesterID, SID, yearID, grade)
  	VALUES
  	('$cid', '$secID', '$semID', '$sid', '$yearID', '$grade')";	

echo $query;
  $result = QueryTF($query);

  return $result;  
}

//Running into some simple conflicts. Will make better with time, all functions above work as needed.
function classesAvailable($sid){ // Displays courses that the student can take for a given semester
	// In HTML. Display see available classes for Year, Semester
	$year = date('Y'); $month = date('n'); 
	$sem='F'; // Display courses for fall semester of the given year.
	// Assumes last month to sign up for fall courses is october.
	if($month >= 10) {
	 // This would work, but there are no courses listed for fall yet 	
		$year++; // Semester spring of next year.
		$sem = 'S'; 
	}
//No courses for fall of this year yet...
	$sem = 'S';
	if($sem == 'S'){
		$semester = 'Spring';
	}else
		$semester = 'Fall';

	// Attain courses student can take in the semester of choice.
	$query = 
	"SELECT DISTINCT courses.CID as CID, courses.name as name, credits,
	 groupID, section.secID as secID, IID 
	FROM courses, section, enrollment 
	WHERE courses.CID = enrollment.CID AND section.CID = courses.CID AND 
		enrollment.SID = '$sid' AND section.semesterID = '$sem' AND section.yearID = '$year' AND 
	courses.CID NOT IN
	(SELECT enrollment.CID as CID
 	FROM enrollment, students
 	WHERE enrollment.SID = students.SID AND enrollment.grade = 'A' AND students.SID = '$sid')
	GROUP BY CID";

	$ret = QueryDB($query, 3);
	$out = "
	<h2>Courses available for this student to take in $semester $year</h2>
	<p>Note: If grade was not an A student may retake a course.</p>
	<form action ='./enrollInCourseHelper.php' method='post'>
	<table style='width:100%'>
	<tr>
		<th>Enroll</th>
		<th>Course Name</th>
		<th>Course ID#</th>
		<th>Section</th>
		<th>Credits</th>
		<th>Group ID</th>
		<th>Professor</th>
	</tr>
	<input type='hidden' name='base' value='$sid'";

//TODO:(Done) Only display courses that the student did not acquire an A in.
	while ($row = mysqli_fetch_array($ret)){
		$q = $row['name']; $w = $row['CID']; $e = $row['secID'];
		$r = $row['credits']; $t = $row['groupID']; $y = $row['IID'];
		$query = "SELECT name FROM instructors WHERE IID = $y";
        $y = QueryDB($query)[0];
        $row['SID'] = $sid; $row['SEM'] = $sem; $row['Year'] = $year;
        $row['name'] = "'$row[name]'";
		$send = base64_encode(serialize($row));

		$out .= "
		<tr>
			<td><input type='checkbox' name='enroll[]' value=$send/>
			<td>$q</td>
			<td>$w</td>
			<td>$e</td>
			<td>$r</td>
			<td>$t</td>
			<td>$y</td>
		</tr>";
	}
	$out .= 
	"	<tr>
			<td colspan='3' align='left'>
				<input type ='submit' value='Enroll In Checked Courses.'/>
			</td>
		</tr>	
	</table>
	</form> ";
	echo $out;
}

?>


<!-- 
Non directly Query Based Functions. The ones that use the Queries above.
-->
