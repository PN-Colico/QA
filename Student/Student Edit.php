<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("location: Student Login.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "finalerd");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

// Retrieve user information from the student_account table
$sql = "SELECT * FROM student_account WHERE username='$username'";
$result = mysqli_query($con, $sql);

// Check if user exists
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
    exit;
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Edit</title>
    <style>
        /* Add your CSS styles here */
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333333;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-header {
            font-weight: bold;
            margin-bottom: 10px;
            color: #1e88e5;
        }

        .info-item {
            margin-bottom: 5px;
        }

        /* Logout button */
        .logout-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #1e88e5;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #0f5abc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Edit</h1>
        <!-- User information section -->
        <div class="section">
            <div class="section-header">User Information</div>
            <div class="info-item"><strong>Username:</strong> <?php echo isset($row['username']) ? $row['username'] : ''; ?></div>
            <div class="info-item"><strong>Student ID:</strong> <?php echo isset($row['student_id']) ? $row['student_id'] : ''; ?></div>
            <div class="info-item"><strong>First Name:</strong> <?php echo isset($row['first_name']) ? $row['first_name'] : ''; ?></div>
            <div class="info-item"><strong>Last Name:</strong> <?php echo isset($row['last_name']) ? $row['last_name'] : ''; ?></div>
            <div class="info-item"><strong>Phone Number:</strong> <?php echo isset($row['number']) ? $row['number'] : ''; ?></div>
            <div class="info-item"><strong>Email:</strong> <?php echo isset($row['email']) ? $row['email'] : ''; ?></div>
        </div>

        <!-- Edit form -->
        <div class="section">
            <div class="section-header">Edit Information</div>
            <form method="post" action="update_student.php">
                <input type="text" name="txtFirstName" placeholder="First Name" value="<?php echo isset($row['first_name']) ? $row['first_name'] : ''; ?>" required>
                <input type="text" name="txtLastName" placeholder="Last Name" value="<?php echo isset($row['last_name']) ? $row['last_name'] : ''; ?>" required>
                <input type="text" name="txtNumber" placeholder="Phone Number" value="<?php echo isset($row['number']) ? $row['number'] : ''; ?>" required>
                <input type="email" name="txtEmail" placeholder="Email" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" required>
                <input type="submit" name="btnUpdate" value="Update">
            </form>
        </div>

        <!-- Logout button -->
        <a href="Student Login.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
