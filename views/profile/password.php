<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Change Password</h2>

<?php if (!empty($success)): ?>
  <div class="alert success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert error">
    <ul style="margin:0;padding-left:18px;">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card" style="max-width:640px;">
  <form method="post" data-validate="true" novalidate>
    <label>Current Password</label>
    <input type="password" name="current_password" data-rule="required" required>

    <label style="margin-top:12px;">New Password</label>
    <input type="password" name="new_password" data-rule="required min4" required>

    <label style="margin-top:12px;">Confirm New Password</label>
    <input type="password" name="confirm_password" data-rule="required min4" required>

    <button type="submit" style="margin-top:14px;">Update Password</button>
    <a class="btn secondary" href="index.php?page=profile" style="margin-left:8px;">Back</a>
  </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
