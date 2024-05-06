<?php
session_start();
$con = new mysqli("localhost", "root", "", "finalerd");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>

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
        select, input[type="text"], input[type="submit"], input[type="button"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"], input[type="button"] {
            background-color: #663399;
            color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #562f94;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Accessibility Features</h2>
    <form method="post">
        <span>Student ID (automatically filled):</span>
        <input type="text" name="student_id" id="studentId" value="<?php echo htmlspecialchars($_SESSION['student_id']); ?>" readonly><br>
        <span>License Plate Search:</span>
        <input type="text" name="license_plate_search"><br>
        <span>Filter by Wheelchair Lift:</span>
        <select name="filter_wl" id="wlFilter">
            <option value="all">All</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br>
        <span>Filter by Ramp:</span>
        <select name="filter_ramp" id="rampFilter">
            <option value="all">All</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br>
        <span>Condition Matching:</span>
        <select name="condition_type" id="conditionType">
            <option value="AND">All Conditions (AND)</option>
            <option value="OR">Any Condition (OR)</option>
        </select><br>
        <input type="submit" name="search" value="Search Buses">
        <input type="submit" name="save" value="Save Selection">
    </form>
    <div id="licensePlateResults">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
            $conditions = [];
            if ($_POST['filter_wl'] !== "all") {
                $conditions[] = "wheelchair = '" . mysqli_real_escape_string($con, $_POST['filter_wl']) . "'";
            }
            if ($_POST['filter_ramp'] !== "all") {
                $conditions[] = "ramp = '" . mysqli_real_escape_string($con, $_POST['filter_ramp']) . "'";
            }
            if (!empty($_POST['license_plate_search'])) {
                $license_plate_search = mysqli_real_escape_string($con, $_POST['license_plate_search']);
                $conditions[] = "license_plate LIKE '%$license_plate_search%'";
            }

            $query = "SELECT license_plate, wheelchair, ramp FROM account";
            if (!empty($conditions)) {
                $condition_type = isset($_POST['condition_type']) ? $_POST['condition_type'] : 'AND';
                $query .= " WHERE " . implode(" " . $condition_type . " ", $conditions);
            }

            $result = $con->query($query);
            if ($result->num_rows > 0) {
                echo "<select name='chosen_license_plate'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['license_plate'] . "'>" . $row['license_plate'] . " - " . $row['wheelchair'] . " wheelchair, " . $row['ramp'] . " ramp</option>";
                }
                echo "</select><br>";
            } else {
                echo "<p>No buses found.</p>";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save']) && !empty($_POST['chosen_license_plate'])) {
            if (empty($_POST['student_id'])) {
                echo "<p>Please provide a Student ID to save.</p>";
            } else {
                $selected_plate = $_POST['chosen_license_plate'];
                $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
                list($license_plate, $wheelchair, $ramp) = explode(' - ', $selected_plate);
                $wheelchair = explode(' wheelchair', $wheelchair)[0];
                $ramp = explode(' ramp', $ramp)[0];
                $save_query = "INSERT INTO userreserve (license_plate, wheelchair, ramp, student_id) VALUES ('$license_plate', '$wheelchair', '$ramp', '$student_id')";
                if ($con->query($save_query) === TRUE) {
                    echo "<p>License plate saved successfully!</p>";
                } else {
                    echo "<p>Error saving license plate: " . $con->error . "</p>";
                }
            }
        }
        $con->close();
        ?>
    </div>
</div>
</body>
</html>











