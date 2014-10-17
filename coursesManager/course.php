<?php
include_once '../header.php';
?>

<div class="padded" >
    <?php
//Kết nối cơ sở dữ liệu
    header('Content-Type: text/html; charset=UTF-8');
    session_start();
     $conn = mysqli_connect('sql100.byethost7.com', 'b7_13744102', '123123')
      or die("Could not connect to server ... \n" . mysqli_error());
      mysqli_query($conn, "SET NAMES 'utf8'");
      $db = mysqli_select_db($conn, 'b7_13744102_92512')
      or die("Could not connect to database ... \n" . mysqli_error()); 
    /*$conn = mysqli_connect('localhost', 'root', '')
            or die("Could not connect to server ... \n" . mysqli_error());
    mysqli_query($conn, "SET NAMES 'utf8'");
    $db = mysqli_select_db($conn, 'school_manager')
            or die("Could not connect to database ... \n" . mysqli_error());*/
    if (!isset($_SESSION['username1'])) {
        header("Location: ../userManager/login.php");
    }

    $query = mysqli_query($conn, "SELECT * FROM courses_info");

    echo "<table border = 1 align = center>

    	<tr>

    	    <th>Môn học</th>

   	    <th>Tín chỉ</th>

  	    <th>Mã môn học</th>

   	    <th>Bắt buộc</th>

   	    <th>Số lượng</th>

   	</tr>";



    while ($row = mysqli_fetch_array($query)) {



        echo "<tr>";



        echo "<td>" . $row['tenmh'] . "</td>";



        echo "<td>" . $row['sotinchi'] . "</td>";



        echo "<td>" . $row['mamh'] . "</td>";



        if ($row['batbuoc'] == 1)
            echo "<td>" . "Có" . "</td>";
        else
            echo "<td>" . "Không" . "</td>";



        echo "<td>" . $row['soluong'] . "</td>";



        echo "</tr>";
    }



    echo "</table>";



    $mamh = (isset($_POST['mamh']) ? $_POST['mamh'] : '');



    $tenmh = (isset($_POST['tenmh']) ? $_POST['tenmh'] : '');



    $sotinchi = (isset($_POST['sotinchi']) ? $_POST['sotinchi'] : '');



    $batbuoc = (isset($_POST['batbuoc']) ? 1 : 0);



    $soluong = (isset($_POST['soluong']) ? $_POST['soluong'] : '');





    if (isset($_POST['delete'])) {



        $query = mysqli_query($conn, "SELECT * FROM courses_info WHERE mamh='$mamh' ");



        if (mysqli_num_rows($query) != 0) {



            $sql_delc = "DELETE FROM courses_info WHERE mamh='$mamh'";

            mysqli_query($conn, $sql_delc);



            $queryr = mysqli_query($conn, "SELECT * FROM register_info WHERE mamh='$mamh'");

            if (mysqli_num_rows($queryr) != 0) {

                $sql_delr = "DELETE FROM register_info WHERE mamh ='$mamh'";

                mysqli_query($conn, $sql_delr);
            }

            header('Location: course.php');
        } else {

            if ($mamh != '')
                echo "Wrong courses id";
        }
    }



    if (isset($_POST['addedit'])) {



        $query = mysqli_query($conn, "SELECT * FROM courses_info WHERE mamh='$mamh'");



        if (mysqli_num_rows($query) == 0) {

            if ($mamh != '') {

                if ($tenmh == '' || $sotinchi == '' || $soluong == '')
                    echo "Please fill out all field";

                else {

                    $sql_addc = mysqli_query($conn, "INSERT INTO courses_info (mamh, tenmh, sotinchi, batbuoc, soluong) VALUES ('$mamh', '$tenmh', '$sotinchi', '$batbuoc', '$soluong')");

                    header('Location: course.php');
                }
            }
        } else {

            if ($tenmh != '') {

                $sql_editname = "UPDATE courses_info SET tenmh='$tenmh' WHERE mamh='$mamh'";



                mysqli_query($conn, $sql_editname);
            }



            if ($sotinchi != '') {



                $sql_editstc = "UPDATE courses_info SET   sotinchi='$sotinchi' WHERE mamh='$mamh'";



                mysqli_query($conn, $sql_editstc);
            }



            $sql_editbb = "UPDATE courses_info SET batbuoc='$batbuoc' WHERE mamh='$mamh'";

            mysqli_query($conn, $sql_editbb);



            if ($soluong != '') {



                $sql_editsl = "UPDATE courses_info SET soluong='$soluong' WHERE mamh='$mamh'";



                mysqli_query($conn, $sql_editsl);
            }

            header('Location: course.php');
        }
    }
    ?>

    
        <form id='info' action='course.php' method='post' onsubmit='return validatec()' accept-charset='UTF-8'>         
            <div id="nhaplieu">
                <div class="nhaplieu-tbLeft">
                    <div class="nhaplieu-control-group">
                        <label class="nhaplieu-control-label" >Mã môn học :</label>
                        <div class="nhaplieu-control">
                            <input type='text' name='mamh' id='mamh' />
                        </div>
                    </div>
                    <div class="nhaplieu-control-group">
                        <label class="nhaplieu-control-label" >Môn học</label>
                        <div class="nhaplieu-control">
                            <input type='text' name='tenmh' id='tenmh' />
                        </div>
                    </div>
                    <div class="nhaplieu-control-group">
                        <label class="nhaplieu-control-label" >Tín chỉ :</label>
                        <div class="nhaplieu-control">
                            <input type='number' name='sotinchi' id='sotinchi' />
                        </div>
                    </div>
                    <input type='submit' name='addedit' value='Thêm/Sửa'/>
                    
                </div>

                <div class="nhaplieu-tbRight">
                    <div class="nhaplieu-control-group">
                        <label class="nhaplieu-control-label" >Bắt buộc :</label>
                        <div class="nhaplieu-control">
                            <input type="checkbox" name="batbuoc" id="batbuoc" checked/>
                        </div>
                    </div>
                    <div class="nhaplieu-control-group">
                        <label class="nhaplieu-control-label" >Số lượng:</label>
                        <div class="nhaplieu-control">
                            <input type='number' name='soluong' id='soluong' />
                        </div>
                    </div>  
                    <div class="nhaplieu-control-group">
                        <label class="nhaplieu-control-label" ></label>
                        <div class="nhaplieu-control">
                            <p>  &nbsp &nbsp &nbsp</p>
                        </div>
                    </div>
                            </form>

                    <input type='submit' name='delete' value='Xóa'/><br>
                    <form action="logout.php" method="post">
                        <input type="submit" name="logout" value="Sign Out">
                    </form>
                </div>
            </div> 
</div>	
<br>
<div id="nothing"style="padding: 15px;">
<form action="../userManager/student_manager.php" method="post">
 <input  type="submit" name="sinhvien" value="Sinh viên">
</form>
</div>
