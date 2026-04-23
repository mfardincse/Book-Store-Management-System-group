<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <div class="card">
        <h3>Shipping Address</h3>

        <form method="post" action="index.php?page=confirm_order">

            <textarea name="address" required
                placeholder="Enter your full shipping address"><?= 
                htmlspecialchars($addr['address']) ?></textarea>

            <br><br>

            <button type="submit">Confirm Order</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
