<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Manage Employees</h2>

<?php if (!empty($errors)): ?>
  <div class="alert error">
    <ul style="margin:0;padding-left:18px;">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="row">
  <div class="card">
    <h3>Add Employee</h3>
    <form method="post" action="index.php?page=admin_employee_create" data-validate="true" novalidate>
      <label>Name</label>
      <input type="text" name="name" data-rule="required" required>

      <label style="margin-top:12px;">Email</label>
      <input type="email" name="email" data-rule="required email" required>

      <label style="margin-top:12px;">Password</label>
      <input type="password" name="password" data-rule="required min4" required>

      <label style="margin-top:12px;">Mobile</label>
      <input type="text" name="mobile" placeholder="Optional">

      <label style="margin-top:12px;">Address</label>
      <textarea name="address" placeholder="Optional"></textarea>

      <button type="submit" style="margin-top:12px;">Create Employee</button>
    </form>
  </div>

  <div class="card">
    <h3>Assign Work Schedule</h3>
    <form method="post" action="index.php?page=admin_schedule_assign" data-validate="true" novalidate>
      <label>Employee</label>
      <select name="employee_id" data-rule="required" required>
        <option value="">--Select--</option>
        <?php foreach ($employees as $emp): ?>
          <option value="<?= (int)$emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?> (<?= htmlspecialchars($emp['email']) ?>)</option>
        <?php endforeach; ?>
      </select>

      <label style="margin-top:12px;">Working Day</label>
      <input type="text" name="working_day" placeholder="e.g., Sunday" data-rule="required" required>

      <label style="margin-top:12px;">Working Time</label>
      <input type="text" name="working_time" placeholder="e.g., 9 AM - 5 PM" data-rule="required" required>

      <label style="margin-top:12px;">Working Place</label>
      <input type="text" name="working_place" placeholder="e.g., Main Branch" data-rule="required" required>

      <button type="submit" style="margin-top:12px;">Assign</button>
    </form>
  </div>
</div>

<div class="card">
  <h3>Employees List</h3>
  <table class="table" id="employeeTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($employees as $emp): ?>
        <tr data-employee-row="<?= (int)$emp['id'] ?>">
          <td><?= (int)$emp['id'] ?></td>
          <td><?= htmlspecialchars($emp['name'] ?? '') ?></td>
          <td><?= htmlspecialchars($emp['email'] ?? '') ?></td>
          <td><?= htmlspecialchars($emp['mobile'] ?? '') ?></td>
          <td><?= htmlspecialchars($emp['status'] ?? '') ?></td>
          <td>
            <button class="btn danger" data-action="delete-employee" data-employee-id="<?= (int)$emp['id'] ?>">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p class="muted">Delete works via AJAX (no page reload).</p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
