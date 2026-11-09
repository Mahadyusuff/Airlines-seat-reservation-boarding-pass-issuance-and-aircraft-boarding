<?php
session_start();

$host = '127.0.0.1';
$db   = 'airline';$user = 'root';
$pass = '';$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn,$user, $pass,$options);
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$pnr = trim($_GET['pnr'] ?? $_POST['pnr'] ?? '');

if (empty($pnr)) {
    die("<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'><h2>Error: No PNR reference provided.</h2><a href='main.php'>Return Home</a></div>");
}

$stmt =$pdo->prepare("SELECT * FROM bookings WHERE pnr = ?");
$stmt->execute([$pnr]);
$booking =$stmt->fetch();

if (!$booking) {
    die("<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'><h2>Booking reference not found.</h2><a href='main.php'>Return Home</a></div>");
}

$passenger_name = htmlspecialchars($booking['passenger_name'] ?? $booking['name'] ?? 'Passenger');
$flight_no      = htmlspecialchars($booking['flight_no'] ?? $booking['flight_id'] ?? 'FL-101');$seat_no        = htmlspecialchars($booking['seat_number'] ?? $booking['seat'] ?? '12A');
$boarding_status = htmlspecialchars($booking['status'] ?? 'CONFIRMED');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding Pass - <?= htmlspecialchars($pnr) ?></title>
    <script src="qrcode.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: #0f172a; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; }
        .pass-container { background: #ffffff; width: 100%; max-width: 680px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 30px rgba(0,0,0,0.35); display: flex; flex-direction: row; }
        .pass-main { flex: 2; padding: 32px; border-right: 2px dashed #e2e8f0; position: relative; }
        .pass-stub { flex: 1; padding: 32px; background: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
        .airline-brand { font-size: 13px; font-weight: 800; color: #2563eb; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 20px; }
        .flight-route { font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 16px; }
        .label { font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 4px; }
        .value { font-size: 16px; font-weight: 700; color: #1e293b; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #dcfce7; color: #15803d; }
        #qrcode { margin: 16px 0; }
        .btn-print { margin-top: 24px; background: #2563eb; color: #fff; padding: 12px 28px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; }
        .btn-print:hover { background: #1d4ed8; }
        @media (max-width: 650px) {
            .pass-container { flex-direction: column; }
            .pass-main { border-right: none; border-bottom: 2px dashed #e2e8f0; }
        }
    </style>
</head>
<body>

<div class="pass-container">
    <div class="pass-main">
        <div class="airline-brand">Skyline Express ✈</div>
        <div class="flight-route"><span>ORIGIN</span> ➔ <span>DEST</span></div>
        <div class="info-grid">
            <div>
                <div class="label">Passenger</div>
                <div class="value"><?= $passenger_name ?></div>
            </div>
            <div>
                <div class="label">Flight No.</div>
                <div class="value"><?= $flight_no ?></div>
            </div>
            <div>
                <div class="label">Seat</div>
                <div class="value" style="color: #2563eb; font-size: 20px;"><?= $seat_no ?></div>
            </div>
            <div>
                <div class="label">Boarding Status</div>
                <div class="status-badge"><?= $boarding_status ?></div>
            </div>
        </div>
    </div>
    <div class="pass-stub">
        <div class="label">PNR REFERENCE</div>
        <div class="value" style="letter-spacing: 2px; font-size: 20px; margin-bottom: 8px;"><?= htmlspecialchars($pnr) ?></div>
        <div id="qrcode"></div>
        <div class="label" style="font-size: 10px;">SCAN AT GATE SCANNER</div>
    </div>
</div>

<button class="btn-print" onclick="window.print()">Print Boarding Pass</button>

<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "PNR:<?= htmlspecialchars($pnr) ?>|SEAT:<?= $seat_no ?>\vert{}FLIGHT:<?= $flight_no ?>",
        width: 110,
        height: 110,
        colorDark : "#0f172a",
        colorLight : "#f8fafc",
        correctLevel : QRCode.CorrectLevel.M
    });
</script>

</body>
</html>
