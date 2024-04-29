<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $uname = $_POST['txtUname'];
    $pwd = $_POST['txtPwd'];

    // Connect to the database
    $con = mysqli_connect("localhost", "root", "", "finalerd");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Query to check if the username and password match for a driver
    $driver_query = "SELECT * FROM driver_account WHERE username='$uname' AND password='$pwd'";
    $driver_result = mysqli_query($con, $driver_query);

    // Query to check if the username and password match for a student
    $student_query = "SELECT * FROM student_account WHERE username='$uname' AND password='$pwd'";
    $student_result = mysqli_query($con, $student_query);

    // Check if the username and password match for a driver
    if (mysqli_num_rows($driver_result) == 1) {
        $_SESSION['username'] = $uname;
        header("location: Driver/Landing Page.php"); // Redirect to driver's landing page
        exit;
    } 
    // Check if the username and password match for a student
    elseif (mysqli_num_rows($student_result) == 1) {
        $_SESSION['username'] = $uname;
        header("location: Student/Landing Page.php"); // Redirect to student's landing page
        exit;
    } 
    // Username and password do not match for either driver or student
    else {
        echo "<script>alert('Invalid username or password');</script>";
    }

    // Close the database connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Add your CSS styles here */
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Add your CSS styles here */
        /* Form styles */
        form {
            width: 300px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            margin-top: 100px;
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
