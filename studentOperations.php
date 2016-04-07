<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Student Operations</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="bluebliss.css" />
</head>
<body>
<div id="mainContentArea">
	<div id="contentBox">
        <div id="title">Student Operations</div>
        
        <div id="linkGroup">
            <div class="link"><a href="home.php">Home</a></div>
            <div class="link"><a href="studentOperations.php">Student Operations</a></div>
            <div class="link"><a href="graduationList.php">Grads List</a></div>
            <div class="link"><a href="about.php">Lol</a></div>
        </div>
        
        <div id="blueBox"> 
          <div id="header"></div>
          <div class="contentTitle">Student Operations</div>
            <div class="pageContent">
              <p>Insert a student into the DB. Self Explanatory point, need base info to insert into DB.</p>
              <div class="links"><a href="insertStudent.php">Enroll a New Student</a></div>
              <br>
              <p>Log changes to the student if they are already in the database. Can change Name, Degree held, student intensive points.</p>
              <div class="links"><a href="updateStudent.php">Update A Students Information</a></div>
              <br>
              <p>View the progress towards a degree that a student has made. Displays eligibility to graduate as well.</p>
              <div class="links"><a href="currentProgress.php">View A Students Progress</a></div>
              <br>
              <p>The key to enrolling a person in a course. Validates that the person has met certain core 
                requirements. If they have not met them, deny them entry into the course. Unles they have a 
                permission number.</p>
              <div class="links"><a href="enrollInCourse.php">Register Student In A Course</a></div>
              <br>
              <p>Once the semester is over. Input the grades that the student attained for the courses taken</p>
              <div class="links"><a href="insertGrades.php">Input Semester Grades</a></div>
              <br>
              <!-- The ABout Us Tab is probably going to be removed. IDK what to place there-->
            </div>
            <div id="footer"><a href="http://www.aszx.net">Template Developed</a> by <a href="http://www.bryantsmith.com">bryant smith</a></div>
        </div>
	</div>
</div>
</body>
</html>
