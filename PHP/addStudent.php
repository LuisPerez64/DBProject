<?php
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/DBFunctions.php');
?>

<?php
// Get the data that was relayed. Place it where it needs to be.
$_name = $_POST['_name'];
$_sid = $_POST['_sid'];
$_iid = $_POST['_iid'];
$_major = $_POST['_major'];
$_degreeHeld = $_POST['_degreeHeld'];
$_career = $_POST['_career'];

$didIAdd = addStudent($_name,$_sid,$_iid,$_major, $_career, $_degreeHeld);

$title = "<title>";
$body = "<body>";

if(!$didIAdd){
	$title = $title."Was Unable to Add Student. Unique SID Needed.";
	$body =$body."\n<h1>Was Unable to Add Student. Unique SID Needed.</h1>".
	"<h2>Please Click Button Below to Return to addition page</h2>";
	$locationToRedirect = dirname(__ROOT__)."/addStudent.php";
}else {
	$title = $title."Student Added Successfully.";
	$body = $body."\n<h1>Student Added Successfully.</h1>".
	"<h2>Click Button To go Back to Home page</h2>";
	$locationToRedirect = __ROOT__."/addStudent.php";
}
$title = $title."</title>";
$body = $body."\n</body>";


echo 
"
	<html>
		<head>	
		$title
		</head>
		$body
		<button id='RedirectWhere' class = 'float-left submit-button'>
		CLICK ME!!
		</button>
		<script type='text/javascript'>
		document.getElementByID('RedirectWhere').onclick = function () {
			location.href = $locationToRedirect;
		};
		</script>
	</html>
"
?>


