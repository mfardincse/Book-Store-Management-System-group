<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>My Work Schedule</h2>

<div class="card">
  <?php if (empty($schedules)): ?>
    <p class="muted">No schedules assigned yet. Contact admin.</p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th>Day</th>
          <th>Time</th>
          <th>Place</th>
          <th>Assigned At</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($schedules as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['working_day'] ?? '') ?></td>
            <td><?= htmlspecialchars($s['working_time'] ?? '') ?></td>
            <td><?= htmlspecialchars($s['working_place'] ?? '') ?></td>
            <td><?= htmlspecialchars($s['created_at'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
