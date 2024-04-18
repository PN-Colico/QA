<!DOCTYPE html>
<html>
<head>
    <title>Driver Registration</title>
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

        .login-link {
            margin-top: 10px;
            text-align: center;
        }

        .login-link a {
            color: #333333;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Driver Registration</h1>
    <form method="post" action="Drivers Registration.php">
        <input type="text" name="txtUname" placeholder="Username" required>
        <input type="password" name="txtPwd" placeholder="Password" required>
        <input type="text" name="txtFirstName" placeholder="First Name" required>
        <input type="text" name="txtMiddleName" placeholder="Middle Name">
        <input type="text" name="txtLastName" placeholder="Last Name" required>
        <input type="text" name="txtUniversity" placeholder="University Name" required>
        <input type="text" name="txtLicense" placeholder="Driver's License" required>
        <input type="text" name="txtLicensePlate" placeholder="License Plate" required>
        <input type="number" name="txtYearsOfService" placeholder="Years of Service" required>
        <input type="submit" name="btnRegister" value="Register">
        <div class="login-link">
            <a href="Drivers Login.php">Already have an account? Login here</a>
        </div>
    </form>
</body>
</html>
<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "finalerd");

if(isset($_POST['btnRegister'])){
    $uname = $_POST['txtUname'];
    $pwd = $_POST['txtPwd'];
    $firstName = $_POST['txtFirstName'];
    $middleName = $_POST['txtMiddleName'];
    $lastName = $_POST['txtLastName'];
    $university = $_POST['txtUniversity'];
    $license = $_POST['txtLicense'];
    $licensePlate = $_POST['txtLicensePlate'];
    $yearsOfService = $_POST['txtYearsOfService'];

    // Check if username already exists
    $check_query = "SELECT * FROM account WHERE username='$uname'";
    $check_result = mysqli_query($con, $check_query);
    $count = mysqli_num_rows($check_result);

    if($count > 0) {
        echo "<script language='javascript'>
                alert('Username already exists. Please choose another one.');
                window.location.href = 'Drivers Registration.php';
            </script>";
    } else {
        // Insert new user into the database
        $insert_query = "INSERT INTO account (username, password, first_name, middle_name, last_name, university_name, driver_license, license_plate, years_of_service) 
                         VALUES ('$uname', '$pwd', '$firstName', '$middleName', '$lastName', '$university', '$license', '$licensePlate', '$yearsOfService')";
        if(mysqli_query($con, $insert_query)) {
            echo "<script language='javascript'>
                    alert('Registration successful. You can now login.');
                    window.location.href = 'Drivers Login.php';
                </script>";
        } else {
            echo "<script language='javascript'>
                    alert('Error in registration. Please try again.');
                    window.location.href = 'Drivers Registration.php';
                </script>";
        }
    }
}
?>


