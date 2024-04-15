<!DOCTYPE html>
<html>
<head>
	<title>Accessibility Features</title>
	<style>
		body {
			font-family: 'Roboto', Arial, sans-serif;
			background-color: #f2f2f2;
			margin: 0;
			padding: 0;
		}

		.container {
			max-width: 400px;
			margin: 50px auto;
			background-color: #ffffff;
			border-radius: 10px;
			padding: 30px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
		}

		h2 {
			text-align: center;
			margin-top: 0;
			color: #663399;
			font-size: 24px;
			font-weight: bold;
			margin-bottom: 30px;
		}

		form {
			text-align: center;
		}

		input[type="text"],
		select {
			width: 100%;
			padding: 10px;
			margin-bottom: 20px;
			border-radius: 5px;
			border: 1px solid #ccc;
			font-size: 16px;
		}

		input[type="submit"] {
			background-color: #663399;
			color: #ffffff;
			border: none;
			padding: 12px 20px;
			cursor: pointer;
			border-radius: 5px;
			font-size: 16px;
			font-weight: bold;
			transition: background-color 0.3s;
		}

		input[type="submit"]:hover {
			background-color: #562f94;
		}
	</style>
</head>
<body>
	<div class="container">
		<h2>Accessibility Features</h2>
		<form method="post">
			<input type="text" name="txtlp" placeholder="Enter License Plate" required><br>
			Wheelchair Lift:
			<select name="wl" required>
				<option value="yes">Yes</option>
				<option value="no">No</option>
			</select><br>
			Ramp:
			<select name="ramp" required>
				<option value="yes">Yes</option>
				<option value="no">No</option>
			</select><br>
			<input type="text" name="txtsid" placeholder="Enter Student ID" required><br>
			<input type="submit" name="btnConfirm" value="Confirm">
		</form>
	</div>
</body>
</html>



<?php
	$con = mysqli_connect("localhost", "root", "", "finalerd") or die("Error in connection");
	echo "connected";
	
	if(isset($_POST['btnConfirm'])){
		$lp = $_POST['txtlp'];
		$wl = $_POST['wl'];
		$ramp = $_POST['ramp'];
		$sid = $_POST['txtsid'];
		
		$sql = "SELECT * FROM accessibilityfeatures WHERE LicensePlate = '$lp'";
		$result = mysqli_query($con, $sql);
		$row = mysqli_num_rows($result);
		
		$sql1 = "SELECT * FROM student1 WHERE StudentID = '$sid'";
		$result1 = mysqli_query($con, $sql1);
		$count = mysqli_num_rows($result1);
		
		if($row == 0){
			if($count > 0){
				$sql = "INSERT INTO accessibilityfeatures (LicensePlate, WheelChairLift, Ramp, StudentID) VALUES ('$lp', '$wl', '$ramp', '$sid')";
				mysqli_query($con, $sql);
				echo "<script language='javascript'>
							alert('New record saved.');
					  </script>";
			}else{
				echo "<script language='javascript'>
							alert('Student ID not found, please enter the correct ID.');
					  </script>";
			}
		}else{
			echo "<script language='javascript'>
						alert('License Plate already exists.');
				  </script>";
		}
	}
?>
