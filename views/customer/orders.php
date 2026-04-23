<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">
<h2 style="text-align:center;">Order History</h2>

<?php while ($o = mysqli_fetch_assoc($orders)) { ?>
    <div class="card" style="position:relative;">

        <!-- ❌ Remove Order -->
        <form method="post" action="index.php?page=remove_order"
              style="position:absolute; top:10px; right:10px;">
            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
            <button type="submit" style="background:red;">x</button>
        </form>

        <h3><?= htmlspecialchars($o['book_title']) ?></h3>

        <p><strong>Order Date:</strong> <?= $o['order_date'] ?></p>

        <p><strong>Shipping Address:</strong><br>
           <?= nl2br(htmlspecialchars($o['shipping_address'])) ?>
        </p>

        <hr>

        <p>Subtotal: ৳<?= $o['subtotal'] ?></p>
        <p>Shipping Cost: ৳<?= $o['shipping_cost'] ?></p>
        <p>VAT (2%): ৳<?= number_format($o['vat'],2) ?></p>

        <h4>Total Bill: ৳<?= number_format($o['total'],2) ?></h4>

        <p>
            <strong>Status:</strong>
            <span style="color:green;font-weight:bold;">
                <?= $o['status'] ?>
            </span>
        </p>

    </div>
<?php } ?>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
