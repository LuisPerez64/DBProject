<?php
define('__ROOT__', dirname(__FILE__));
require_once('./DBFunctions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Register A Student</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="bluebliss.css" />
</head>
<body>
<div id="mainContentArea">
	<div id="contentBox">
        <div id="title">Register A Student</div>
        
        <div id="linkGroup">
            <div class="link"><a href="home.php">Home</a></div>
            <div class="link"><a href="studentOperations.php">Student Operations</a></div>
            <div class="link"><a href="graduationList.php">Grads List</a></div>
            <div class="link"><a href="about.php">Lol</a></div>
        </div>
        
        <div id="blueBox"> 
          <div id="header"></div>
          <div class="contentTitle">Register A Student</div>
            <div class="pageContent">
              <form action="./insertStudentHelper.php" method="post">
      <!--
      Dictates a table object. Explicitly defines its body, as well as the inputs for its fields.
      -->
      <table border="1">
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
            </div>
            <div id="footer"><a href="http://www.aszx.net">Template Developed</a> by <a href="http://www.bryantsmith.com">bryant smith</a></div>
        </div>
	</div>
</div>
</body>
</html>
