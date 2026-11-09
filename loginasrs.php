<?php
require_once 'headerasrs.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['uemail'] ?? '');
    $password = trim($_POST['upassword'] ?? '');

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, uname, upassword FROM user WHERE uemail = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && (password_verify($password, $user['upassword']) || $password === $user['upassword'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['uname'];
            header("Location: main.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>

<div style="max-width: 400px; margin: 60px auto; padding: 32px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="margin-bottom: 20px; color: #0f172a;">Sign In</h2>
    
    <?php if ($error): ?>
        <div style="padding: 10px; background: #fee2e2; color: #b91c1c; border-radius: 6px; font-size: 14px; margin-bottom: 16px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" style="display: flex; flex-direction: column; gap: 14px;">
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px;">Email</label>
            <input type="email" name="uemail" required style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px;">Password</label>
            <input type="password" name="upassword" required style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>
        <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 8px;">Log In</button>
    </form>
    <p style="font-size: 13px; color: #64748b; margin-top: 16px; text-align: center;">Need an account? <a href="register.php" style="color: #2563eb;">Register here</a></p>
</div>

<?php require_once 'footer.php'; ?>
