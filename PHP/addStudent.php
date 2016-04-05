<?php
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/PHP/DBFunctions.php');
?>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="bluebliss.css" />
<!--
Adds a student to the given Database. Base Format
Takes in the students basic Information, the SID is internally
generated.
-->
<!--First Try at this HTML PHP Thing. Lets see. -->
<html>
	<head>
		<title>Add A Student</title>
	</head>
	<body>
		<h1>Add Student</h1>
		<p>Adds a Student into the Given Database. Please add in specified fields.</p>
		<p>Note, SID and IID pregenrated. Change at own Risks</p>
		<!--Just signals which PHP Point to call when submit is hit -->
		<form action="./PHP/addStudent.php" method="post">
			<!--
			Dictates a table object. Explicitly defines its body, as well as the inputs for its fields.
			-->
			<table border="10">
				<tbody>
					<tr>
						<td>Name</td>
						<td align="left">
							<input type="text" name="_name" size="20" maxlength="20"/>
						</td>
					</tr>
					<?php 
					$__IID = getAnElt(1);
					$__SID = getAnElt(2);

					echo 
					"
					<tr>
						<td>Student ID#</td>
						<td align='left'>
							<input type='text' name='_sid' size='20' maxlength='20' 
							value = '$__SID'/>
						</td>
					</tr>
					<tr>
						<td>Advisor</td>
						<td>
							<input type='text' name='_iid' size='5' maxlength='5' 
							value = '$__IID'/>
						</td>
					</tr>
					";
					?>
					<tr>
						<td>Major</td>
						<td>
							<input type="text" name="_major" size="5" maxlength="5"/>
						</td>
					</tr>
					<tr>
						<td>Degree Held</td>
						<td>
							<input type="text" name="_degreeHeld" size="20" maxlength="20"/>
						</td>
					</tr>
					<tr>
						<td>Scholastic Career</td>
						<td>
							<input type="text" name="_career" size="20" maxlength="20"/>
						</td>
					</tr>
					<tr>
						<td colspan="5" align="center">
							<input type="submit" value="Insert Student"/>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</body>
</html>