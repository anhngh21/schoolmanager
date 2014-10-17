<?php
session_start();
$account  = (isset($_POST['account'])  ? $_POST['account']    : '');
$password = (isset($_POST['password']) ? $_POST['password']   : '');
$connection = mysqli_connect('sql100.byethost7.com','b7_13744102', '123123');
mysqli_query($connection,"SET NAMES 'utf8'");
$db = mysqli_select_db( $connection,'b7_13744102_92512');
if (isset($_POST['Login'])) {
	$query = mysqli_query($connection,"SELECT * FROM user_login WHERE username='$account' AND password='$password' ");
	if (mysqli_num_rows($query)!=0) {
                while ($row = mysqli_fetch_array($query)) {
		    if ($row['authorities'] == 1) {
			//session_register('username1');
			    $_SESSION['username1']=$account;
			    header('Location: student_manager.php');
		    }
		    else {			
			//session_register('username');			
	                $_SESSION['username']=$account;
			header('Location: ../coursesRegister/register_courses.php');
		   }
                }
	}
	else 	
		echo "Wrong account or password!";
		
}
?>
<!DOCTYPE html>
<html>
<head>
<title> Login form</title>
<script type="text/javascript">
function validate() {
	if (document.forms['login']['account'].value == ""||document.forms['login']['password'].value == "") {
        	alert("Please fill out account or password field");
		return false;
    }
}
</script>
</head>
<body>
<form id='login' action='login.php' align='center' method='post' onsubmit='return validate()' accept-charset='UTF-8'>
<br>
<div align="center" style="margin-top: 5px; font-size:30px;">Login</div>
<b>&nbsp Account:</b> <input type='text' name='account' id='account' /><br>
<b>Password:</b> <input type='password' name='password' id='password' maxlength="50" /><br>
<input type='submit' name='Login' value='Login' style="font-size:15px; margin-top:2px; margin-bottom:2px;font-weight:bold;color:blue;"/>
</form>
</script>
</body>
</html>
			