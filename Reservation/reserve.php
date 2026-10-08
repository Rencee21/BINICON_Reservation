<?php
include('db.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ticket_types = json_decode($_POST['ticket_types']); // Decode the JSON array for ticket types
    $seat_nos = json_decode($_POST['seat_nos']); // Decode the JSON array for seat numbers

    // Loop through each seat reservation
    foreach ($seat_nos as $key => $seat_no) {
        $ticket_type = $ticket_types[$key];

        // Check if seat number already taken for the same ticket type
        $stmt_seat = $conn->prepare("SELECT * FROM reservations WHERE ticket_type = ? AND seat_no = ?");
        $stmt_seat->bind_param("si", $ticket_type, $seat_no);
        $stmt_seat->execute();
        $result_seat = $stmt_seat->get_result();

        if ($result_seat->num_rows > 0) {
            echo "<script>alert('Seat $seat_no already taken for $ticket_type ticket type! Please choose a different one.'); window.location.href = 'index.php';</script>";
            exit;
        }

        // Insert reservation if no conflicts
        $stmt = $conn->prepare("INSERT INTO reservations (name, ticket_type, seat_no) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $ticket_type, $seat_no);
        $stmt->execute();
    }

    echo "<script>alert('Reservation successful!'); window.location.href = 'index.php';</script>";
}
?>
