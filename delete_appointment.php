<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

// Fetch the appointment number of the appointment being deleted
$sql = "SELECT appointment_number FROM appointments WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    echo "No appointment found or you don't have permission to delete this appointment.";
    exit;
}

$appointment_number = $appointment['appointment_number'];

// Delete the appointment if the user is the owner
$sql = "DELETE FROM appointments WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id, $_SESSION['user_id']);

if ($stmt->execute()) {
    // After deleting the appointment, update the appointment numbers for the remaining appointments in the queue
    $sql = "UPDATE appointments SET appointment_number = appointment_number - 1 WHERE appointment_number > ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $appointment_number);
    $stmt->execute();
    
    header('Location: view_queue.php');
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>
