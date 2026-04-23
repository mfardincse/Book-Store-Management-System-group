<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>Edit Book</h2>

<?php if (!empty($errors)): ?>
  <div class="alert error">
    <ul style="margin:0;padding-left:18px;">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card" style="max-width:720px;">
  <form method="post" data-validate="true" novalidate>
    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($book['title'] ?? '') ?>" data-rule="required" required>

    <label style="margin-top:12px;">Author</label>
    <input type="text" name="author" value="<?= htmlspecialchars($book['author'] ?? '') ?>">

    <label style="margin-top:12px;">Price (BDT)</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($book['price'] ?? '') ?>" data-rule="required" required>

    <button type="submit" style="margin-top:12px;">Save Changes</button>
    <a class="btn secondary" href="index.php?page=admin_books" style="margin-left:8px;">Back</a>
  </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
