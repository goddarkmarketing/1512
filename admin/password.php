<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$error = '';
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current'] ?? '';
    $new = $_POST['new'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!tt_verify_login($_SESSION['tt_admin_user'] ?? TT_ADMIN_USER, $current)) {
        $error = 'Current password is incorrect';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match';
    } else {
        $cred = [
            'username' => $_SESSION['tt_admin_user'] ?? TT_ADMIN_USER,
            'password_hash' => password_hash($new, PASSWORD_DEFAULT),
            'updated' => date('c'),
        ];
        file_put_contents(
            TT_CREDENTIALS_FILE,
            json_encode($cred, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            LOCK_EX
        );
        $ok = 'Password changed successfully';
    }
}

tt_admin_header('Change password', 'password');
if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif;
if ($ok): ?><div class="alert alert-success"><?= htmlspecialchars($ok, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post" class="card" style="max-width:480px">
  <div class="field"><label>Current password</label><input type="password" name="current" required autocomplete="current-password"/></div>
  <div class="field"><label>New password</label><input type="password" name="new" required minlength="8" autocomplete="new-password"/></div>
  <div class="field"><label>Confirm new password</label><input type="password" name="confirm" required minlength="8" autocomplete="new-password"/></div>
  <div class="form-actions"><button type="submit" class="btn btn-primary">Change password</button></div>
</form>

<?php tt_admin_footer(); ?>
