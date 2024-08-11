<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

// Fetch the appointment data
$sql = "SELECT * FROM appointments WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    echo "No appointment found or you don't have permission to edit this appointment.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_name = $_POST['patient_name'];
    $patient_age = $_POST['patient_age'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $phone_number = $_POST['phone_number'];

    // Update the appointment while maintaining the queue order
    $sql = "UPDATE appointments SET 
            patient_name = ?, 
            patient_age = ?, 
            doctor_id = ?, 
            appointment_date = ?, 
            appointment_time = ?, 
            phone_number = ? 
            WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siisssii', $patient_name, $patient_age, $doctor_id, $appointment_date, $appointment_time, $phone_number, $id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header('Location: view_queue.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
            background: url('images/edit-appointment-background.jpg') no-repeat center center fixed;
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

        .back-btn {
            background-color: #6c757d;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Appointment</h2>
        <form action="edit_appointment.php?id=<?php echo $id; ?>" method="POST">
            <label for="patient_name">Patient Name:</label>
            <input type="text" name="patient_name" value="<?php echo htmlspecialchars($appointment['patient_name']); ?>" required>
            <br>
            <label for="patient_age">Patient Age:</label>
            <input type="number" name="patient_age" value="<?php echo $appointment['patient_age']; ?>" required>
            <br>
            <label for="doctor_id">Doctor Name:</label>
            <select name="doctor_id" required>
                <?php
                $sql = "SELECT id, name FROM doctors";
                $doctors = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                foreach ($doctors as $doctor): ?>
                    <option value="<?php echo $doctor['id']; ?>" <?php if ($doctor['id'] == $appointment['doctor_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($doctor['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="appointment_date">Appointment Date:</label>
            <input type="date" name="appointment_date" value="<?php echo $appointment['appointment_date']; ?>" required>
            <br>
            <label for="appointment_time">Appointment Time:</label>
            <input type="time" name="appointment_time" value="<?php echo $appointment['appointment_time']; ?>" required>
            <br>
            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($appointment['phone_number']); ?>" required>
            <br>
            <button type="submit" class="btn">Update Appointment</button>
        </form>

        <!-- Back button -->
        <a href="view_queue.php" class="btn back-btn">Back to Queue</a>
    </div>
</body>
</html>
