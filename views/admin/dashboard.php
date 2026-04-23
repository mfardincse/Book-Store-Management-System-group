<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Admin Dashboard</h2>

<div class="row">
  <div class="card">
    <h3>Total Users</h3>
    <p><strong><?= (int)($counts['total'] ?? 0) ?></strong></p>
    <p class="muted">Admins: <?= (int)($counts['admin'] ?? 0) ?> | Employees: <?= (int)($counts['employee'] ?? 0) ?> | Customers: <?= (int)($counts['customer'] ?? 0) ?></p>
  </div>
  <div class="card">
    <h3>Quick Actions</h3>
    <a class="btn" href="index.php?page=admin_employees">Manage Employees</a>
    <a class="btn secondary" href="index.php?page=admin_books" style="margin-left:8px;">Manage Books</a>
  </div>
</div>

<div class="card">
  <h3>Recent System Activities</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Time</th>
        <th>User</th>
        <th>Role</th>
        <th>Activity</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $l): ?>
        <tr>
          <td><?= htmlspecialchars($l['created_at'] ?? '') ?></td>
          <td><?= htmlspecialchars($l['name'] ?? 'System') ?></td>
          <td><?= htmlspecialchars($l['role'] ?? '-') ?></td>
          <td><?= htmlspecialchars($l['activity'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
