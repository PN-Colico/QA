<!DOCTYPE html>
<html>
<head>
	<title>University</title>
	<style>
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background-color: #f9f6fd;
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
		input[type="text"], select {
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
		<h2>University</h2>
		<form method="post">
			<input type="text" name="txtuid" placeholder="Enter University ID" required><br>
			<input type="text" name="txtuname" placeholder="Enter University Name" required><br>
			Wheelchair Access:
			<select name="wheelchair" required>
				<option value="yes">Yes</option>
				<option value="no">No</option>
			</select><br>
			Ramp Access:
			<select name="ramp" required>
				<option value="yes">Yes</option>
				<option value="no">No</option>
			</select><br>
			<input type="submit" name="btnConfirm" value="Confirm">
		</form>
	</div>
</body>
</html>

<?php
$con = mysqli_connect("localhost", "root", "", "finalerd") or die("Error in connection");
echo "connected";

if(isset($_POST['btnConfirm'])){
	$uid = $_POST['txtuid'];
	$uname = $_POST['txtuname'];
	$wheelchair = $_POST['wheelchair'];
	$ramp = $_POST['ramp'];

	$sql = "SELECT * FROM university WHERE UniversityID = '$uid'";
	$result = mysqli_query($con, $sql);
	$row = mysqli_num_rows($result);
	
	if($row == 0){
		$sql = "INSERT INTO accessibilityFeatures (UniversityID, UniversityName, Wheelchair, Ramp) VALUES ('$uid', '$uname', '$wheelchair', '$ramp')";
		if (mysqli_query($con, $sql)) {
			echo "<script language='javascript'>
						alert('New record saved.');
				  </script>";
		} else {
			echo "<script language='javascript'>
						alert('Error saving record: ".mysqli_error($con)."');
				  </script>";
		}
	}else{
		echo "<script language='javascript'>
					alert('University ID already exists.');
			  </script>";
	}
}

mysqli_close($con);
?>
