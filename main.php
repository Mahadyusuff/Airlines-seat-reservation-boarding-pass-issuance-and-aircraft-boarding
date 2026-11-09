<?php
require_once 'headerasrs.php';

$stmt = $conn->query("SELECT * FROM flights ORDER BY departure_time ASC");
$flights = $stmt->fetchAll();
?>

<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <h2 style="color: #0f172a; margin-bottom: 20px;">Available Flights</h2>
    
    <div style="display: grid; gap: 16px;">
        <?php foreach ($flights as $f): ?>
            <div style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: #2563eb; text-transform: uppercase;">Flight <?= htmlspecialchars($f['flight_no']) ?></span>
                    <h3 style="color: #1e293b; margin: 4px 0 8px;"><?= htmlspecialchars($f['origin']) ?> ➔ <?= htmlspecialchars($f['destination']) ?></h3>
                    <p style="color: #64748b; font-size: 14px;">Departure: <?= htmlspecialchars($f['departure_time']) ?> | Arrival: <?= htmlspecialchars($f['arrival_time']) ?></p>
                </div>
                <a href="bookings.php?flight_id=<?= urlencode($f['id']) ?>" style="background: #2563eb; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px;">Select Flight</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 40px; background: #f8fafc; padding: 24px; border-radius: 10px; border: 1px dashed #cbd5e1;">
        <h3 style="color: #1e293b; margin-bottom: 12px;">Retrieve Existing Boarding Pass</h3>
        <form action="pnrinfo.php" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="pnr" placeholder="Enter 6-character PNR" required style="padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; flex: 1; text-transform: uppercase;">
            <button type="submit" style="background: #0f172a; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">View Pass</button>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>
