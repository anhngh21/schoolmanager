<?php
include_once '../header.php';
?>

<div class="padded" >

    <?php
//kết nối cơ sở dữ liệu

    header('Content-Type: text/html; charset=UTF-8');

    session_start();

    $connection = mysqli_connect('sql100.byethost7.com', 'b7_13744102', '123123')

      or die("Could not connect to server ... \n" . mysqli_error());

      mysqli_query($connection,"SET NAMES 'utf8'");

      $db = mysqli_select_db($connection,'b7_13744102_92512' )

      or die("Could not connect to database ... \n" . mysqli_error()); 
    /* $connection = mysqli_connect('localhost', 'root', '')

            or die("Could not connect to server ... \n" . mysqli_error());

    mysqli_query($connection, "SET NAMES 'utf8'");

    $db = mysqli_select_db($connection, 'school_manager')

            or die("Could not connect to database ... \n" . mysqli_error());*/

    if (!isset($_SESSION['username1'])) {

        header("Location: login.php");
        ;
    }

    $query = mysqli_query($connection, "SELECT * FROM student_info");

    echo "<table border = 1 align = center><tr><th>Mã số sinh viên</th><th>Họ tên</th><th>Ngày sinh</th><th>Ngày nhập học</th><th>Số điệnt thoại </th><th>Email</th> <th>Khoa</th></tr>";

    while ($row = mysqli_fetch_array($query)) {

        echo "<tr>";

        echo "<td>" . $row['mssv'] . "</td>";

        echo "<td>" . $row['hoten'] . "</td>";

        echo "<td>" . $row['ngsinh'] . "</td>";

        echo "<td>" . $row['ngnhaphoc'] . "</td>";

        echo "<td>" . $row['sdt'] . "</td>";

        echo "<td>" . $row['email'] . "</td>";

        echo "<td>" . $row['khoa'] . "</td>";

        echo "</tr>";
    }

    echo "</table>";

    $StudentID = (isset($_POST['studentid']) ? $_POST['studentid'] : '');

    $Name = (isset($_POST['name']) ? $_POST['name'] : '');

    $Birthday = (isset($_POST['bday']) ? $_POST['bday'] : '');

    $Doa = (isset($_POST['doa']) ? $_POST['doa'] : '');

    $telnum = (isset($_POST['telnum']) ? $_POST['telnum'] : '');

    $email = (isset($_POST['email']) ? $_POST['email'] : '');

    $password = (isset($_POST['password']) ? $_POST['password'] : '');

    $Dep = (isset($_POST['dep']) ? $_POST['dep'] : '');



    if (isset($_POST['delete'])) {

        $query = mysqli_query($connection, "SELECT * FROM student_info WHERE mssv='$StudentID' ");

        if (mysqli_num_rows($query) != 0) {

            $sql_delstu = "DELETE FROM student_info WHERE mssv='$StudentID'";

            mysqli_query($connection, $sql_delstu);

            $sql_deluser = "DELETE FROM user_login WHERE username='$StudentID'";

            mysqli_query($connection, $sql_deluser);

            header('Location: student_manager.php');
        } else {

            if ($StudentID != '')
                echo "Wrong student id";
        }
    }



    if (isset($_POST['addedit'])) {

        $query = mysqli_query($connection, "SELECT * FROM student_info WHERE mssv='$StudentID' ");

        if (mysqli_num_rows($query) == 0) {

            if ($StudentID != '') {

                if ($Name == '' || $Birthday == '' || $Doa == '' || $telnum == '' || $email == '' || $password == '' || $Dep == '')
                    echo "Please fill out all field";

                else if (!validateDate($Birthday) || !validateDate($Doa)) {

                    echo "Please correct Birthday or Day of admission field: yyyy-mm-dd";
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    echo "Please correct Email field";

                else {

                    $sql_addstu = mysqli_query($connection, "INSERT INTO student_info (hoten, ngsinh, mssv, ngnhaphoc, sdt, email, khoa) VALUES ('$Name', '$Birthday', '$StudentID', '$Doa', '$telnum', '$email', '$Dep')");

                    $userid = mysqli_query($connection, "SELECT mauser FROM user_login ORDER BY mauser DESC LIMIT 1;");

                    $maxuserid = mysqli_fetch_row($userid);

                    $useridtoadd = $maxuserid[0] + 1;

                    $sql_adduser = mysqli_query($connection, "INSERT INTO user_login (mauser, username, password, authorities) VALUES ('$useridtoadd','$StudentID', '$password', 0)");

                    header('Location: student_manager.php');
                }
            }
        } else {

            if ((!validateDate($Birthday) && $Birthday != '') || (!validateDate($Doa) && $Doa != '')) {

                if (!validateDate($Birthday) && $Birthday != '')
                    echo "Please correct Birthday: yyyy-mm-dd<br>";

                if (!validateDate($Doa) && $Doa != '')
                    echo "Please correct Day of admission field: yyyy-mm-dd<br>";
            }

            else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email != '')
                echo "Please correct Email field";

            else {

                if ($Name != '') {

                    $sql_editname = "UPDATE student_info SET hoten='$Name' WHERE mssv='$StudentID'";

                    mysqli_query($connection, $sql_editname);
                }

                if ($Birthday != '') {

                    $sql_editbd = "UPDATE student_info SET ngsinh='$Birthday' WHERE mssv='$StudentID'";

                    mysqli_query($connection, $sql_editbd);
                }

                if ($Doa != '') {

                    $sql_editdoa = "UPDATE student_info SET ngnhaphoc='$Doa' WHERE mssv='$StudentID'";

                    mysqli_query($connection, $sql_editdoa);
                }

                if ($telnum != '') {

                    $sql_edittel = "UPDATE student_info SET sdt='$telnum' WHERE mssv='$StudentID'";

                    mysqli_query($connection, $sql_edittel);
                }

                if ($email != '') {

                    $sql_editemail = "UPDATE student_info SET email='$email' WHERE mssv='$StudentID'";

                    mysqli_query($connection, $sql_editemail);
                }

                if ($khoa != '') {

                    $sql_editkhoa = "UPDATE student_info SET khoa='$khoa' WHERE mssv='$StudentID'";

                    mysqli_query($connection, $sql_editkhoa);
                }

                header('Location: student_manager.php');
            }
        }
    }

    function validateDate($date) {

        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {

            if (checkdate($matches[2], $matches[3], $matches[1])) {

                return true;
            }
        }

        return false;
    }
    ?>


    <form id='info' action='student_manager.php' method='post' onsubmit='return validate()' accept-charset='UTF-8'>
        <div id="nhaplieu">
            <div class="nhaplieu-tbLeft">
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Mã số sinh viên:</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='studentid' id='studentid' />
                    </div>
                </div>
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Họ Tên :</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='name' id='name' />
                    </div>
                </div>
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Ngày sinh:</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='bday' id='bday' />
                    </div>
                </div>
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Ngày nhập học:</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='doa' id='doa' />
                    </div>
                </div> 

                <input type='submit' name='addedit' value='Thêm/Sửa'/><br>

            </div>

            <div class="nhaplieu-tbRight">
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Số điện thoại:</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='telnum' id='telnum' />
                    </div>
                </div>
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Email:</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='email' id='email' />
                    </div>
                </div>
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Mật khẩu:</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='password' id='password' />
                    </div>
                </div>
                <div class="nhaplieu-control-group">
                    <label class="nhaplieu-control-label" >Khoa:</label>
                    <div class="nhaplieu-control">
                        <input type='text' name='dep' id='dep' />
                    </div>
                </div>  
                </form>
                <input type='submit' name='delete' value='Xóa'/>
                <form action="logout.php" method="post">
                    <input type="submit" name="logout" value="Sign out">
                </form>

            </div>

        </div>
        <form action="../coursesManager/course.php" method="post">
            <input type="submit" name="monhoc" value="Môn học">
        </form>
</div>

<?php
include_once '../footer.php';
?>	
