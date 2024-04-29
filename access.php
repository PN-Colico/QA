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

		.error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Accessibility Features</h2>
        <form method="post" onsubmit="return confirm('Are you sure you want to submit?')">
            <input type="text" name="txtlp" id="txtlp" placeholder="Enter License Plate" required>
            <span class="error-message" id="lp-error"></span><br>
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
            <input type="submit" name="btnConfirm" value="Confirm" onclick="return validateForm()">
        </form>
    </div>

    <script>
        function validateForm() {
            var lp = document.getElementById("txtlp").value;
            var regex = /^(?:[A-Z]{3}-\d{4}|(?:\d{3}-[A-Z]{3}|[A-Z]-\d{3}-[A-Z]{2}))$/;
            if (!regex.test(lp)) {
                document.getElementById("lp-error").innerText = "Invalid license plate format. Please use the correct format (e.g., ABC-1234 or 123-ABC or A-123-AB)";
                return false; // Prevent form submission
            }
            return true; // Proceed with form submission
        }
    </script>
</body>
</html>

<?php
session_start();

// Your database connection code...
$con = new mysqli("localhost", "root", "", "finalerd");
if ($con->connect_error) {
    die("<script>alert('Connection failed: " . $con->connect_error . "');</script>");
} else {
    echo "<script>alert('Connected successfully');</script>";
}

if (isset($_POST['btnConfirm'])) {
    $lp = strtoupper($_POST['txtlp']); // Convert to uppercase for consistency

    // Validate license plate format
    if (!preg_match('/^(?:[A-Z]{3}-\d{4}|(?:\d{3}-[A-Z]{3}|[A-Z]-\d{3}-[A-Z]{2}))$/', $lp)) {
        echo "<script>alert('Invalid license plate format. Please enter a valid license plate.');</script>";
    } else {
        $wl = $_POST['wl'];
        $ramp = $_POST['ramp'];
        $sid = $_POST['txtsid'];

        // Check for existing license plate
        $stmt = $con->prepare("SELECT * FROM accessibilityfeatures WHERE LicensePlate = ?");
        $stmt->bind_param("s", $lp);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->num_rows;

        // Check for existing student ID
        $stmt1 = $con->prepare("SELECT * FROM student_account WHERE student_id = ?");
        $stmt1->bind_param("s", $sid);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $count = $result1->num_rows;

        if ($row == 0) {
            if ($count > 0) {
                $stmt = $con->prepare("INSERT INTO accessibilityfeatures (LicensePlate, WheelChairLift, Ramp, StudentID) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $lp, $wl, $ramp, $sid);
                if ($stmt->execute()) {
                    $_SESSION['accessibility_data'] = array(
                        'LicensePlate' => $lp,
                        'WheelChairLift' => $wl,
                        'Ramp' => $ramp,
                        'StudentID' => $sid
                    );
                    echo "<script>alert('Enjoy The Ride!');</script>";
                } else {
                    echo "<script>alert('Error inserting record: " . $con->error . "');</script>";
                }
                $stmt->close();
            } else {
                echo "<script>alert('Student ID not found, please enter the correct ID.');</script>";
            }
        } else {
            echo "<script>alert('License Plate already exists.');</script>";
        }
    }
}
$con->close();
?>


