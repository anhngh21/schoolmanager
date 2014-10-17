<?php
session_start();

$account = (isset($_POST['account']) ? $_POST['account'] : '');

$password = (isset($_POST['password']) ? $_POST['password'] : '');

 $connection = mysqli_connect('sql100.byethost7.com','b7_13744102', '123123');

  mysqli_query($connection,"SET NAMES 'utf8'");

  $db = mysqli_select_db( $connection,'b7_13744102_92512'); 
/*$connection = mysqli_connect('localhost', 'root', '');

mysqli_query($connection, "SET NAMES 'utf8'");

$db = mysqli_select_db($connection, 'school_manager');*/

if (isset($_POST['Login'])) {

    $query = mysqli_query($connection, "SELECT * FROM user_login WHERE username='$account' AND password='$password' ");

    if (mysqli_num_rows($query) != 0) {

        while ($row = mysqli_fetch_array($query)) {

            if ($row['authorities'] == 1) {

                //session_register('username1');

                $_SESSION['username1'] = $account;

                header('Location: student_manager.php');
            } else {

                //session_register('username');			

                $_SESSION['username'] = $account;

                header('Location: ../coursesRegister/register_courses.php');
            }
        }
    } else
        echo "Wrong account or password!";
}
?>

<!DOCTYPE html>

<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title> Quản lý sinh viên </title>

    <style type="text/css" media="all">

        @import "../CSS/style.css" ;



    </style>


        <title> Login form</title>

        <script type="text/javascript">

            function validate() {

                if (document.forms['login']['account'].value == "" || document.forms['login']['password'].value == "") {

                    alert("Please fill out account or password field");

                    return false;

                }

            }

        </script>

    </head>

    <body>

        <form id='login' action='login.php' align='center' method='post' onsubmit='return validate()' accept-charset='UTF-8'>

            <br>
            <div class='loginTable'> 
                <div class="login-control-group">

                    <label class="login-control-label" >Account </label>

                    <div class="login-control">

                        <input type='text' name='account' id='account' />

                    </div>

                </div>
                <div class="login-control-group">

                    <label class="login-control-label" >Password </label>

                    <div class="login-control">

                        <input type='password' name='password' id='password' maxlength="50" />

                    </div>

                </div>
               
                <div class="login-control-bn">

                    <input align="center" type ="submit" name='Login' value='Login' />

                </div>
            </div>

        </form>

    </script>

</body>

</html>

