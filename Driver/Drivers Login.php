<html>
<head>
	<title>Driver's Login</title>
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
	
	if(isset($_POST['btnLogin'])){
		$uname = $_POST['txtUname'];
		$pwd = $_POST['txtPwd'];
		
		$sql = "SELECT * FROM account WHERE username='$uname'";
		$result = mysqli_query($con, $sql);
		$count = mysqli_num_rows($result);
		
		if($count == 0)
			echo "<script language='javascript'>
					alert('Username does not exist.');
				</script>";
		else if($count == 1){
			$row = mysqli_fetch_array($result);  
			
			if($pwd == $row[1]){
				$_SESSION['username'] = $row['username'];
				header("location: Landing Page.php");
			}
			else
				echo "<script language='javascript'>
						alert('Incorrect password');
					</script>";
		}
	}
?>
