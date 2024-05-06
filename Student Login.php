<html>
<head>
	<title>Student Login</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #1c1c1c;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}

		h1 {
			text-align: center;
			color: #fff;
		}

		form {
			width: 300px;
			background-color: #ffffff;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			margin: 0 auto;
		}

		input[type="text"],
		input[type="password"] {
			width: 100%;
			padding: 10px;
			margin-bottom: 10px;
			border: 1px solid #dddddd;
			border-radius: 3px;
		}

		input[type="submit"] {
			width: 100%;
			padding: 10px;
			background-color: #333333;
			color: #ffffff;
			border: none;
			border-radius: 3px;
			cursor: pointer;
		}

		a {
			text-decoration: none;
			color: #333333;
			margin-left: 5px;
		}

		.register-link {
			margin-top: 10px;
			text-align: center;
		}

		.register-link a {
			color: #333333;
			font-size: 14px;
		}
	</style>
</head>
<body>
	<h1>Account Login</h1>
	<form method="post">
		<input type="text" name="txtUname" placeholder="Username" required>
		<input type="password" name="txtPwd" placeholder="Password" required> 
		<input type="submit" name="btnLogin" value="Login">
		<div class="register-link">
			<a href="Drivers Registration.php">Register Here as Driver</a> <a href="Student Registration.php">Register Here as Student</a>
		</div>
	</form>
</body>
</html>
<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "finalerd");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['btnLogin'])) {
    $uname = mysqli_real_escape_string($con, $_POST['txtUname']);
    $pwd = mysqli_real_escape_string($con, $_POST['txtPwd']);  // Secure password handling

    $sql = "SELECT * FROM student_account WHERE username=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $uname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_num_rows($result);

    if ($count == 0) {
        echo "<script language='javascript'>
                alert('Username does not exist.');
              </script>";
    } else {
        $row = mysqli_fetch_assoc($result);  // Fetch associative array

        if ($pwd == $row['password']) {  // This should ideally use password_verify() if passwords are hashed
            $_SESSION['username'] = $row['username'];
            $_SESSION['student_id'] = $row['student_id'];  // Store student_id in session
            header("location: Student Edit.php");
            exit();  // Make sure to call exit after headers to avoid further script execution
        } else {
            echo "<script language='javascript'>
                    alert('Incorrect password');
                  </script>";
        }
    }
    mysqli_stmt_close($stmt);
}
?>

