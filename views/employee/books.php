<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Book List</h2>

<div class="card">
  <p class="muted">Click the button to mark a book as Available / Unavailable (AJAX).</p>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Price</th>
        <th>Available</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($books as $b): ?>
        <?php $available = (int)($b['available'] ?? 0); ?>
        <tr>
          <td><?= (int)($b['id'] ?? 0) ?></td>
          <td><?= htmlspecialchars($b['title'] ?? '') ?></td>
          <td><?= htmlspecialchars($b['author'] ?? '') ?></td>
          <td>৳<?= htmlspecialchars($b['price'] ?? '') ?></td>
          <td><?= $available === 1 ? 'Yes' : 'No' ?></td>
          <td>
            <button class="btn" data-action="toggle-availability" data-book-id="<?= (int)($b['id'] ?? 0) ?>" data-next="<?= $available === 1 ? 0 : 1 ?>">
              Mark <?= $available === 1 ? 'Unavailable' : 'Available' ?>
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
