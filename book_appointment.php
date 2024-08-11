<?php
include 'config.php';
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the selected category from the URL
$category = $_GET['category'];

// Fetch doctors based on the selected category
$sql = "SELECT id, name FROM doctors WHERE category = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}
$stmt->bind_param('s', $category);
$stmt->execute();
$result = $stmt->get_result();
$doctors = $result->fetch_all(MYSQLI_ASSOC);

// Initialize messages
$success_message = "";
$error_message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $patient_name = $_POST['patient_name'];
    $patient_age = $_POST['patient_age'];
    $phone_number = $_POST['phone_number'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Generate the next appointment number using queue logic
    $sql = "SELECT IFNULL(MAX(appointment_number), 0) AS last_appointment FROM appointments WHERE doctor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $appointment_number = $row['last_appointment'] + 1;

    // Insert the new appointment into the database
    $sql = "INSERT INTO appointments (appointment_number, patient_name, patient_age, phone_number, doctor_id, appointment_date, appointment_time, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('isiisssi', $appointment_number, $patient_name, $patient_age, $phone_number, $doctor_id, $appointment_date, $appointment_time, $user_id);

    if ($stmt->execute()) {
        $success_message = "Your appointment number is: <strong>" . $appointment_number . "</strong>";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
            background: url('images/appointment-background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            width: 90%;
        }

        h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #007bff;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            color: #fff;
            background-color: #007bff;
            text-align: center;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            color: #fff;
            font-size: 1em;
            font-weight: bold;
            text-align: center;
        }

        .success {
            background-color: #28a745;
        }

        .error {
            background-color: #dc3545;
        }

        .success strong {
            font-size: 1.2em;
        }

        a.btn {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book an Appointment with a <?php echo htmlspecialchars($category); ?></h2>
        <form action="book_appointment.php?category=<?php echo urlencode($category); ?>" method="POST">
            <label for="doctor_id">Doctor Name:</label>
            <select name="doctor_id" required>
                <?php 
                if (empty($doctors)) {
                    echo "<option value=''>No doctors available</option>";
                } else {
                    foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor['id']; ?>"><?php echo htmlspecialchars($doctor['name']); ?></option>
                    <?php endforeach; 
                }
                ?>
            </select>
            <br>
            <label for="patient_name">Patient Name:</label>
            <input type="text" name="patient_name" required>
            <br>
            <label for="patient_age">Patient Age:</label>
            <input type="number" name="patient_age" required>
            <br>
            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" required>
            <br>
            <label for="appointment_date">Appointment Date:</label>
            <input type="date" name="appointment_date" required>
            <br>
            <label for="appointment_time">Appointment Time:</label>
            <input type="time" name="appointment_time" required>
            <br>
            <button type="submit" class="btn">Book Appointment</button>
        </form>

        <?php if ($success_message): ?>
            <div class="message success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="message error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <a href="dashboard.php" class="btn">Go to Dashboard</a>
    </div>
</body>
</html>
