<?php
// Gateway to the DB. Returns the result of the query that is done. 
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

	// IS either true or False	
	return $result;	
}

//Handles SELECT, SHOW, ... Only using Select
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
	
	// Edge Cases... Should just write a new one but -_-
	// Was unable to find something at all. Query Failed.
	if(!$result){
		return 0;
	} else if ($type == 2){ // Counting Rows from Select, still done in caller.
		return mysqli_num_rows($result); // DOne to validate if PS exists. Only for one Query
	} else if ($type == 3) { // getGrades Query. This is going to look really ugly after it all...
		return $result;
	}

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

//Works to get the first Elt of most any query that is done... Inefficient but reusablity of 
// code is going to kill me here...
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

function canIGraduate($sid){
	$sidArray = studentGPA($sid);
	$returnArray = array(
		'canI' => False,
		'reason' => -1);
	$reason = 0;
	if($sidArray['totalCredits'] >= 30){ 
		$reason++;
		if($sidArray['coreTaken'] >=4){ // If this and above met, 12 Credits / 18 Credits satisfied.
			$reason++;
			if($sidArray['GPA'] >= 3.0){
				$reason++;
				if($sidArray['lessThanBCount'] <= 2) // Add conditions met search
					$returnArray['canI'] = True;
			}		
		}					
	}
	$returnArray['reason'] = $reason;
	return $returnArray;
}
function getGrades($sid){
$query = "SELECT enrollment.grade as 'Grade', courses.groupID as 'GID', courses.credits as 'Credits'
 FROM students, enrollment, courses
  WHERE students.SID = enrollment.SID AND courses.CID = enrollment.CID
  AND students.SID = $sid
  Order BY students.name, courses.groupID";
  // Grade, and GroupID returned
  return QueryDB($query, 3);
}
//Pivotal Function. Determines a lot of the graduation points.
function studentGPA($sid){
	$gradeMap = array('A'  => 4.0, 'A-' => 3.7,
					  'B+' => 3.3, 'B'  => 3.0, 'B-' => 2.7,
					  'C+' => 2.3, 'C'  => 2.0, 'C-' => 1.7,
					  'D+' => 1.3, 'D'  => 1.0,
					  'F' => 0);


	$return = array('GPA' => 0, 'lessThanBCount' => 0, 'totalCredits' => 0, 'letterGrade' => '', 'coreTaken' => 0);

// Assumes the SID exists. Done elsewhere.
	$QP = 0;
	$q = getGrades($sid);
	while($filterMe = mysqli_fetch_array($q)) {
		$letterGrade = $filterMe['Grade'];
		$group = $filterMe['GID'];
		$credits = $filterMe['Credits'];
		$grade = $gradeMap[$letterGrade];
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

	//Assumes preserved Order.
	foreach (array_reverse($gradeMap) as $key => $value) {
		if ($return['GPA'] > $value)
			$return['letterGrade'] = $key; 
	}
	$return['coreTaken'] = takenCore($sid);
	echo $return['GPA']."<br>";
	echo $return['letterGrade']."  ".$return['totalCredits']."<br>";
	return $return;
}

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

function addClass($sid, $cid, $grade, $semID, $yearID, $secID){
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
 		echo ""; // Do nothing for now.
 		// Don't know if the only prereq is finding out if the course has
 		// been taken before, and not adherence to the grade reveived.
 	} else // NumRows is 0, so they have not taken this course yet.
 		$preReqMet = False;
 }

 if(!$preReqMet)
 	return False; // Do not attempt to insert the course, just return false.
 
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
 	SET grade=$grade, semesterID = $semID, yearID = $yearID
 	WHERE CID = $cid AND SID = $sid
 	";
 } else
 	$query =
 	"INSERT INTO enrollment
  	(CID, SecID, semesterID, SID, yearID, grade)
  	VALUES
  	('$cid', '$secID', '$semID', '$sid', '$yearID', '$grade')";	

  $resutl = QueryTF($query);

  return $result;  
}
?>

<!-- 
Non directly Query Based Functions. The ones that use the Queries above.
-->
