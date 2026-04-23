<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Employee Dashboard</h2>

<div class="row">
  <div class="card">
    <h3>Quick Links</h3>
    <a class="btn" href="index.php?page=employee_books">View Book List</a>
    <a class="btn secondary" href="index.php?page=employee_schedule" style="margin-left:8px;">My Work Schedule</a>
  </div>
  <div class="card">
    <h3>Books Summary</h3>
    <p><strong><?= count($books) ?></strong> total books (available + unavailable).</p>
  </div>
</div>

<div class="card">
  <h3>My Latest Schedule</h3>
  <?php if (empty($schedules)): ?>
    <p class="muted">No schedules assigned yet. Contact admin.</p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th>Day</th>
          <th>Time</th>
          <th>Place</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_slice($schedules, 0, 5) as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['working_day'] ?? '') ?></td>
            <td><?= htmlspecialchars($s['working_time'] ?? '') ?></td>
            <td><?= htmlspecialchars($s['working_place'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
