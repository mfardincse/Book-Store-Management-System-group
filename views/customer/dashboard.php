<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">

    <h2 style="text-align:center;">Available Books</h2>

    <input type="text" id="searchBox" placeholder="Search book by name...">

    <div id="bookContainer" class="book-grid">
        <?php while ($b = mysqli_fetch_assoc($books)) { ?>
            <div class="card book-card">
                <img src="/bookstore_1/assets/book.png" class="book-img">

                <h3><?= htmlspecialchars($b['title']) ?></h3>
                <p><?= htmlspecialchars($b['author']) ?></p>
                <p><strong>৳<?= $b['price'] ?></strong></p>

                <form method="post" action="index.php?page=add_to_cart">
                    <input type="hidden" name="book_id" value="<?= $b['id'] ?>">
                    <input type="hidden" name="title" value="<?= htmlspecialchars($b['title']) ?>">
                    <input type="hidden" name="price" value="<?= $b['price'] ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php } ?>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
