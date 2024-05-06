<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("location: Student Login.php");
    exit;
}

if (!isset($_SESSION['student_id'])) {
    // Redirect to login page, or handle as appropriate
    header('Location: Student Login.php');
    exit();
}

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "finalerd");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

$username = $_SESSION['username'];

// Function to retrieve user information

// Function to retrieve user information
function getUserInfo($con, $username) {
    $stmt = mysqli_prepare($con, "SELECT * FROM student_account WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    } else {
        echo "User not found.";
        exit;
    }
}

$row = getUserInfo($con, $username);

// Handle update
if(isset($_POST['update'])) {
    $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
    $number = mysqli_real_escape_string($con, $_POST['number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $updateSql = "UPDATE student_account SET 
                    first_name = '$firstName', 
                    last_name = '$lastName', 
                    number = '$number', 
                    email = '$email'
                  WHERE username = '$username'";

    if(mysqli_query($con, $updateSql)) {
        echo "<script>alert('Update successful.');</script>";
        header('Refresh:0');
    } else {
        echo "<script>alert('Update failed: " . mysqli_error($con) . "');</script>";
    }
}

// Handle delete
if(isset($_POST['delete'])) {
    $deleteSql = $con->prepare("DELETE FROM student_account WHERE username=?");
    $deleteSql->bind_param("s", $username);
    if($deleteSql->execute()) {
        session_destroy(); // Destroy session and log user out
        echo "<script>alert('Account deleted.'); window.location.href = 'Student Login.php';</script>";
    } else {
        echo "<script>alert('Deletion failed.');</script>";
    }
    $deleteSql->close();
}

// Close the database connection
mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student's Dashboard</title>
    <style>
        /* General styles */
       /* General styles */
body {
    font-family: Arial, sans-serif;
    background-color: #1c1c1c;
    color: #ffffff;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    width: 40%; 
    background-color: #333333;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); 
    padding: 30px; 
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h1 {
    text-align: center;
    color: #f2f2f2;
    margin-bottom: 20px; 
}

.section {
    width: 100%;
    margin-bottom: 20px;
}

.section-header {
    font-weight: bold;
    margin-bottom: 10px;
    color: #1e88e5;
    text-align: center; 
}

.info-item {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #424242;
    border-radius: 5px;
    width: 100%; 
}

/* Input styles */
input[type="text"], input[type="email"], input[type="submit"] {
    width: 100%;
    padding: 12px; 
    margin-bottom: 10px; 
    border: none;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #0a66c2;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

input[type="submit"]:hover, .button:hover {
    background-color: #084298; /* Darker shade on hover for better interaction feedback */
}

/* CRUD buttons */
.crud-btns {
    display: flex;
    justify-content: space-around;
    width: 100%; 
    flex-wrap: wrap; /* This will allow buttons to wrap to the next line if they don't fit */
    gap: 10px; /* This adds space between buttons */
}

.button {
    padding: 10px 20px;
    background-color: #0a66c2;
    color: #ffffff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s; /* Consistency in transitions */
}

.button, .logout-btn {
    flex: 1 1 auto; /* This allows buttons to grow and shrink as needed */
    margin: 5px; /* This adds margin around the buttons */
    padding: 10px 20px; /* Adjust padding as needed */
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s;
    cursor: pointer;
    border: none;
    border-radius: 3px;
    color: #ffffff;
    background-color: #0a66c2;
}

.logout-btn {
    background-color: #d9534f; /* Red background for logout to distinguish it */
}

.logout-btn:hover {
    background-color: #c9302c; /* Darker shade on hover for logout button */
}

.button:hover {
    background-color: #084298; /* Darker shade on hover for other buttons */
}

/* Ensure buttons do not stick to the bottom of the container */
.container {
    padding-bottom: 30px; /* Adjust as necessary */
}

    </style>

</head>
<body>
<div class="container">
    <h1>Student's Dashboard</h1>
    <div class="section">
        <div class="section-header">User Information</div>
        <?php if (isset($row)): ?>
        <form method="post">
        <input type="text" name="student_id" value="<?php echo $row['student_id']; ?>" required>
            <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>" required>
            <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>" required>
            <input type="text" name="number" value="<?php echo $row['number']; ?>" required>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
            <button type="submit" name="update" onclick="return confirm('Are you sure you want to update your account?');">Update Information</button>
            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
        </form>
        <?php else: ?>
                <p>User data not available.</p>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['accessibility_data'])): ?>
        <div class="section">
            <div class="section-header">Accessibility Features Information</div>
            <p>License Plate: <?php echo $_SESSION['accessibility_data']['LicensePlate']; ?></p>
            <p>Wheelchair Lift: <?php echo $_SESSION['accessibility_data']['WheelChairLift']; ?></p>
            <p>Ramp: <?php echo $_SESSION['accessibility_data']['Ramp']; ?></p>
            <p>Student ID: <?php echo $_SESSION['accessibility_data']['StudentID']; ?></p>
        </div>
        <?php unset($_SESSION['accessibility_data']); endif; ?>
        <button type="button" class="button" onclick="window.location='Student Login.php';">LogOut</button>
        <button type="button" class="button" onclick="window.location='access.php';">Request Service</button>
    </div>
</div>
</body>
</html>

