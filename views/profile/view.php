<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>My Profile</h2>

<div class="card" style="max-width:760px;">
  <table class="table">
    <tbody>
      <tr><th>Name</th><td><?= htmlspecialchars($user['name'] ?? '') ?></td></tr>
      <tr><th>Email</th><td><?= htmlspecialchars($user['email'] ?? '') ?></td></tr>
      <tr><th>Mobile</th><td><?= htmlspecialchars($user['mobile'] ?? '') ?></td></tr>
      <tr><th>Address</th><td><?= nl2br(htmlspecialchars($user['address'] ?? '')) ?></td></tr>
      <tr><th>Role</th><td><?= htmlspecialchars($user['role'] ?? '') ?></td></tr>
      <tr><th>Status</th><td><?= htmlspecialchars($user['status'] ?? '') ?></td></tr>
      <tr><th>Created</th><td><?= htmlspecialchars($user['created_at'] ?? '') ?></td></tr>
    </tbody>
  </table>

  <a class="btn" href="index.php?page=profile_edit">Edit Profile</a>
  <a class="btn secondary" href="index.php?page=profile_password" style="margin-left:8px;">Change Password</a>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
