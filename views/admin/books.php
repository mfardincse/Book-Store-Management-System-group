<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Manage Books</h2>

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
    <h3>Add Book</h3>
    <form method="post" action="index.php?page=admin_book_create" data-validate="true" novalidate>
      <label>Title</label>
      <input type="text" name="title" data-rule="required" required>

      <label style="margin-top:12px;">Author</label>
      <input type="text" name="author">

      <label style="margin-top:12px;">Price (BDT)</label>
      <input type="number" step="0.01" name="price" data-rule="required" required>

      <button type="submit" style="margin-top:12px;">Add Book</button>
    </form>
  </div>

  <div class="card">
    <h3>Tips</h3>
    <p class="muted">Employees can toggle availability. Customers only see available books.</p>
  </div>
</div>

<div class="card">
  <h3>Books List</h3>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Price</th>
        <th>Available</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($books as $b): ?>
        <tr>
          <td><?= (int)($b['id'] ?? 0) ?></td>
          <td><?= htmlspecialchars($b['title'] ?? '') ?></td>
          <td><?= htmlspecialchars($b['author'] ?? '') ?></td>
          <td>৳<?= htmlspecialchars($b['price'] ?? '') ?></td>
          <td><?= ((int)($b['available'] ?? 0) === 1) ? 'Yes' : 'No' ?></td>
          <td>
            <a class="btn secondary" href="index.php?page=admin_book_edit&id=<?= (int)($b['id'] ?? 0) ?>">Edit</a>
            <form method="post" action="index.php?page=admin_book_delete" style="display:inline;">
              <input type="hidden" name="id" value="<?= (int)($b['id'] ?? 0) ?>">
              <button type="submit" class="btn danger" onclick="return confirm('Delete this book?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
