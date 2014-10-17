<!DOCTYPE html>
<head>
 <title> Quản lý sinh viên </title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css" media="all">
        @import "../CSS/style.css" ;
    </style>
</head>
<body>
        <?php
		session_start();
		/*$conn = mysqli_connect('localhost', 'root', '') or die("Can't connect DB"); //kết nối database
        mysqli_select_db($conn, 'school_manager');
        mysqli_query($conn, 'set names utf8');*/
        $conn = mysqli_connect('sql100.byethost7.com', 'b7_13744102', '123123') or die("Can't connect DB"); //kết nối database
        mysqli_select_db($conn, 'b7_13744102_92512');
        mysqli_query($conn, 'set names utf8');
        ?><div id="wraper">
	
        <?php
		if(!isset($_SESSION['username'])) //kiểm tra đăng nhập
			header('Location: ../userManager/login.php'); //quay lại trang login
		else{
			$username=$_SESSION['username']; //gán session username
		}
        $sql_stu = "select * from `student_info` where mssv='".$username."'"; //câu lệnh lấy thông tin sinh viên $username
        $result_stu = mysqli_query($conn, $sql_stu);//thực hiện lệnh sql lấy thông tin sinh viên
        if (mysqli_num_rows($result_stu) != 0) { //kiểm tra nếu có sinh viên $username trong database
            while ($row = mysqli_fetch_array($result_stu)) { //duyệt dữ liệu thông tin sinh viên
                $mssv = $row['mssv']; //gán thông tin sinh viên vào biến
                $hoten = $row['hoten'];
                $ngsinh = $row['ngsinh'];
                $sdt = $row['sdt'];
                $email = $row['email'];
                $ngnhaphoc = $row['ngnhaphoc'];
                $khoa = $row['khoa'];
            }
        }
		else { //nếu sinh viên không đăng ký bất kì môn học nào
                    echo "<tr><td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td></tr>";
                }
		?>
		<table border=1 style="width:100%">
			<tr>
				<th>Mã số sinh viên</th>
				<th>Họ tên</th>
				<th>Ngày sinh</th>
				<th>Số điện thoại</th>
				<th>Email</th>
				<th>Ngày nhập học</th>
				<th>Khoa</th>
			</tr>
		<?php
			echo '<tr><td>'.$mssv.'</td>'; //xuất biến thông tin sinh viên vào bảng
			echo '<td>'.$hoten.'</td>';
			echo '<td>'.$ngsinh.'</td>';
			echo '<td>'.$sdt.'</td>';
			echo '<td>'.$email.'</td>';
			echo '<td>'.$ngnhaphoc.'</td>';
			echo '<td>'.$khoa.'</td> </tr>'; 
		?>
		</table>
			
             
		<form action="#" method="post" >
            <div align="center" style="margin-top: 10px; font-size:30px; ">
                <b>MÔN HỌC ĐÃ ĐĂNG KÝ</b>
            </div>
            <table border=1 style="width:100%; margin-top:5px;" >
                <tr>
                    <th>Mã môn học</th>	
                    <th>Tên môn học</th>
                    <th>Số tín chỉ</th>
                    <th>Bắt buộc</th>
                </tr>
                <?php
                $sql_cou = "select * from `courses_info`"; // lấy thông tin môn học
                $sql_reg = "select * from `register_info` where mssv='".$username."'"; //lấy dữ liệu đăng ký môn học của sinh viên với mssv=$username
                $result_cou = mysqli_query($conn, $sql_cou);
                $result_reg = mysqli_query($conn, $sql_reg);
				$a = array();//khai báo mảng lưu mamh
                $i = 1;
				if (mysqli_num_rows($result_reg)!=0){ //kiểm tra nếu sinh viên $username có đăng ký môn học
					while ($row_reg = mysqli_fetch_array($result_reg)) {//duyệt dữ liệu đăng ký môn học và lưu dữ liệu 'mamh' vào mảng a
						$a[$i] = $row_reg['mamh'];
						$i = $i + 1;
					}
					if (mysqli_num_rows($result_cou) != 0) { //kiểm tra nếu có môn học
						while ($row_cou = mysqli_fetch_array($result_cou)) { //duyệt dữ liệu thông tin môn học
							if (in_array($row_cou['mamh'], $a)) { //nếu có môn học sinh viên đăng ký thì hiển thị
								echo "<tr><td>" . $row_cou['mamh'] . "</td>";
								echo "<td>" . $row_cou['tenmh'] . "</td>";
								echo "<td>" . $row_cou['sotinchi'] . "</td>";
								if ($row_cou['batbuoc'] == 1)
									echo "<td>Có</td></tr>";
								else
									echo"<td>Không</td></tr>";
							}
						}
					}
                }
                else { //nếu sinh viên không đăng ký bất kì môn học nào
                    echo "<tr><td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td>";
					echo "<td>Không có dữ liệu.</td></tr>";
                }
                ?>
            </table>
            <div align="center" style="margin-top: 50px; font-size:30px;">
                <b>ĐĂNG KÝ MÔN HỌC</b>
            </div>
            <table border=1 style="width:100%; margin-top:5px;">
                <tr>
                    <th>Lựa chọn</th>
                    <th>Mã môn học</th>	
                    <th>Tên môn học</th>
                    <th>Số tín chỉ</th>
                    <th>Bắt buộc</th>
                </tr>
			<?php
			$result_cou = mysqli_query($conn, $sql_cou);
			$result_reg = mysqli_query($conn, $sql_reg);
			if (mysqli_num_rows($result_cou) != 0) { //kiểm tra nếu có môn học
				while ($row_cou = mysqli_fetch_array($result_cou)) { //duyệt dữ liệu thông tin môn học
					$temp_mh = $row_cou['mamh'];
					if (mysqli_num_rows($result_reg) != 0) {//nếu có dữ liệu từ bảng đăng ký môn học trả về
						if (in_array($temp_mh, $a)) {//tìm dữ liệu mamh nếu có trong mảng a thì hiển thị checked
							echo "<tr><td><div align='center'><input type='checkbox' name='list_mh[]' value='" . $row_cou['mamh'] . "' checked></div></td>";
						} else { //ngược lại thì không check
							echo "<tr><td><div align='center'><input type='checkbox' name='list_mh[]' value='" . $row_cou['mamh'] . "'></div></td>";
						}
					} else {
						echo "<tr><td><div align='center'><input type='checkbox' name='list_mh[]' value='" . $row_cou['mamh'] . "'></div></td>";
					}
					echo "<td>" . $row_cou['mamh'] . "</td>";
					echo "<td>" . $row_cou['tenmh'] . "</td>";
					echo "<td>" . $row_cou['sotinchi'] . "</td>";
					if ($row_cou['batbuoc'] == 1)
						echo "<td>Có</td></tr>";
					else
						echo"<td>Không</td></tr>";
				}
			}
			?>
            </table>
                    <div align="center">
                <br>
                <input type="submit" value="Đăng ký" name="accept" style="font-size:15px;margin-top:2px;margin-bottom:2px;font-weight:bold;"/>
			</div>
            <div align="center">
            <br>
				<input type="submit" value="Hủy" name="cancel" style=""/>
            </div>
			<?php
			if (isset($_POST['accept']) && isset($username)) {//kiểm tra nút submit 'accept' của form
				//$_SESSION['dangky'] = 'dangky';
				//$_SESSION['huy'] = 'huy';
				$result_cou = mysqli_query($conn, $sql_cou);
				if (mysqli_num_rows($result_cou) != 0) { //kiểm tra nếu có môn học
					while ($row_cou = mysqli_fetch_array($result_cou)) {//duyệt dữ liệu thông tin môn học
						if (in_array($row_cou['mamh'], $_POST['list_mh'])) { //kiểm tra nếu sinh viên đăng ký được checked trong danh sách đăng ký
							if (in_array($row_cou['mamh'], $a)) { //nếu trong bảng đăng ký môn học của sinh viên đã được đăng ký thì bỏ qua
								continue;
							} else { // ngược lại thêm dữ liệu đăng ký môn học của sinh viên
								//$_SESSION['dangky']=1;
								//unset('huy');
								mysqli_query($conn, "insert into `register_info` values(NULL,'".$username."','" . $row_cou["mamh"] . "');");
							}
						} else { //ngược lại sinh viên hủy môn học (bỏ checked trong danh sách đăng ký)
							if (in_array($row_cou['mamh'], $a)) { //nếu trong database có dữ liệu đã đăng ký của sinh viên thì tiến hành xóa sinh viên
								//$_SESSION['huy']=1;
								//unset('dangky');
								mysqli_query($conn, "delete from `register_info` where mssv='".$username."' and mamh='" . $row_cou['mamh'] . "';");
							}
						}
					}
				}
				header('Location: register_courses.php');
				//if(isset($_SESSION['huy'])==1) {
				//echo "Đã hủy môn học thành công! <br>";
				//$_SESSION['huy']=0;
				//}
				//if(isset($_SESSION['dangky'])==1) {
				//echo "Đã đăng ký môn học thành công!<br>";
				//$_SESSION['dangky']=0;
				//}
			}
			?>
		</form></div>	
    <div align="center">
    <br>
                <form action="logout.php" method="post" >
                <input type="submit" value="Sign Out" name="logout" style=""/>
    </div>       
		</form>
</body>
</html>
<?php
    include_once '../footer.php';
?>	
