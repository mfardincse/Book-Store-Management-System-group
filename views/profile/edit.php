<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Edit Profile</h2>

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

<div class="card" style="max-width:760px;">
  <form method="post" data-validate="true" novalidate>
    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" data-rule="required" required>

    <label style="margin-top:12px;">Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" data-rule="required email" required>

    <label style="margin-top:12px;">Mobile</label>
    <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile'] ?? '') ?>">

    <label style="margin-top:12px;">Address</label>
    <textarea name="address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>

    <button type="submit" style="margin-top:14px;">Save</button>
    <a class="btn secondary" href="index.php?page=profile" style="margin-left:8px;">Back</a>
  </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
