<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">

<h2>Your Cart</h2>

<?php if (empty($cart)) { ?>
    <p>Your cart is empty</p>
<?php } else { ?>

    <?php foreach ($cart as $index => $item) { ?>
        <div class="card" style="display:flex; justify-content:space-between; align-items:center;">

            <div>
                <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                Price: ৳<?= $item['price'] ?>
            </div>

            <!-- ❌ Remove Button -->
            <form method="post" action="index.php?page=remove_from_cart">
                <input type="hidden" name="index" value="<?= $index ?>">
                <button type="submit" style="background:red;">x</button>
            </form>

        </div>
    <?php } ?>

    <div class="card">
        <p>Subtotal: ৳<?= $subtotal ?></p>
        <p>Shipping Cost: ৳<?= $shipping ?></p>
        <p>VAT (2%): ৳<?= number_format($vat,2) ?></p>
        <hr>
        <h3>Total Bill: ৳<?= number_format($grandTotal,2) ?></h3>
    </div>

    <a href="index.php?page=address">
        <button>Order Now</button>
    </a>

<?php } ?>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
