<?php
require_once 'headerasrs.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passenger = trim($_POST['passenger_name'] ?? '');
    $flight_id = $_POST['flight_id'] ?? null;
    $seat_no   = trim($_POST['seat_number'] ?? '');

    if (empty($passenger) || empty($flight_id) || empty($seat_no)) {
        header("Location: bookings.php?error=missing_fields");
        exit;
    }

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT id, is_booked FROM seats WHERE flight_id = ? AND seat_number = ? FOR UPDATE");
        $stmt->execute([$flight_id, $seat_no]);
        $seat = $stmt->fetch();

        if (!$seat || $seat['is_booked']) {
            $conn->rollBack();
            header("Location: bookings.php?error=seat_unavailable");
            exit;
        }

        $pnr = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));

        $updateSeat = $conn->prepare("UPDATE seats SET is_booked = 1 WHERE id = ?");
        $updateSeat->execute([$seat['id']]);

        $createBooking = $conn->prepare("INSERT INTO bookings (pnr, flight_id, seat_id, passenger_name, status) VALUES (?, ?, ?, ?, 'CONFIRMED')");
        $createBooking->execute([$pnr, $flight_id, $seat['id'], $passenger]);

        $conn->commit();

        header("Location: pnrinfo.php?pnr=" . urlencode($pnr));
        exit;
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        header("Location: bookings.php?error=system_error");
        exit;
    }
}
?>
