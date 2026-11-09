<?php
require_once 'headerasrs.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['uname'] ?? '');
    $email = trim($_POST['uemail'] ?? '');
    $password = trim($_POST['upassword'] ?? '');
    $phone = trim($_POST['uphone'] ?? '');
    $city = trim($_POST['ucity'] ?? '');

    if (!empty($name) && !empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id FROM user WHERE uemail = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'Email already registered.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $insert = $conn->prepare("INSERT INTO user (uname, uemail, upassword, uphone, ucity) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$name, $email, $hashedPassword, $phone, $city]);
            $success = 'Account created successfully. <a href="loginasrs.php" style="color: #15803d; font-weight: 700;">Click here to login</a>';
        }
    } else {
        $error = 'Please fill out all required fields.';
    }
}
?>

<div style="max-width: 440px; margin: 40px auto; padding: 32px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="margin-bottom: 20px; color: #0f172a;">Create an Account</h2>
    
    <?php if ($error): ?>
        <div style="padding: 10px; background: #fee2e2; color: #b91c1c; border-radius: 6px; font-size: 14px; margin-bottom: 16px;"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div style="padding: 10px; background: #dcfce7; color: #15803d; border-radius: 6px; font-size: 14px; margin-bottom: 16px;"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" style="display: flex; flex-direction: column; gap: 14px;">
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px;">Full Name</label>
            <input type="text" name="uname" required style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px;">Email</label>
            <input type="email" name="uemail" required style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px;">Password</label>
            <input type="password" name="upassword" required style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px;">Phone Number</label>
            <input type="text" name="uphone" style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px;">City</label>
            <input type="text" name="ucity" style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>
        <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 8px;">Register</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
