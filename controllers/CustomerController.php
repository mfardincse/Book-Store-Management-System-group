<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Address.php';
require_once __DIR__ . '/../models/Order.php';

class CustomerController {

    public function dashboard() {
        $books = Book::all();
        include __DIR__ . '/../views/customer/dashboard.php';
    }

    public function books() {
        $books = Book::all();
        include __DIR__ . '/../views/customer/books.php';
    }

    public function address() {
        if ($_POST) {
            Address::update($_SESSION['user_id'], $_POST['address']);
        }
        $addr = Address::get($_SESSION['user_id']);
        include __DIR__ . '/../views/customer/address.php';
    }

    public function orders() {
        $orders = Order::history($_SESSION['user_id']);
        include __DIR__ . '/../views/customer/orders.php';
    }

   public function cart() {

    $cart = $_SESSION['cart'] ?? [];

    // subtotal
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'];
    }

    // random shipping cost (50–100)
    $shipping = rand(50, 100);

    // VAT 2%
    $vat = $subtotal * 0.02;

    // grand total
    $grandTotal = $subtotal + $shipping + $vat;

    include __DIR__ . '/../views/customer/cart.php';
}
public function confirmOrder() {
    global $conn;

    $address = $_POST['address'];
    $cart = $_SESSION['cart'];

    // 🔢 calculate bill
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'];
    }

    $shipping = rand(50, 100);
    $vat = $subtotal * 0.02;
    $total = $subtotal + $shipping + $vat;

    // save / update address
    $check = mysqli_query($conn,
        "SELECT * FROM addresses WHERE user_id={$_SESSION['user_id']}"
    );

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn,
            "UPDATE addresses SET address='$address'
             WHERE user_id={$_SESSION['user_id']}"
        );
    } else {
        mysqli_query($conn,
            "INSERT INTO addresses (user_id,address)
             VALUES ({$_SESSION['user_id']},'$address')"
        );
    }

    // save each book as an order row
    foreach ($cart as $item) {
        mysqli_query($conn,
            "INSERT INTO orders 
            (user_id, book_title, order_date, shipping_address,
             subtotal, shipping_cost, vat, total, status)
            VALUES (
                {$_SESSION['user_id']},
                '{$item['title']}',
                CURDATE(),
                '$address',
                $subtotal,
                $shipping,
                $vat,
                $total,
                'On the way'
            )"
        );
    }

    unset($_SESSION['cart']);

    echo "<script>
        alert('Order confirmed successfully');
        window.location='index.php?page=orders';
    </script>";
    exit;
}
public function removeFromCart() {

    if (isset($_POST['index'])) {
        unset($_SESSION['cart'][$_POST['index']]);

        // reindex array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    header("Location: index.php?page=cart");
    exit;
}
    public function addToCart() {

    $item = [
        'id' => $_POST['book_id'],
        'title' => $_POST['title'],
        'price' => $_POST['price']
    ];

    $_SESSION['cart'][] = $item;

    echo "<script>
            alert('Book added to cart');
            window.location='index.php';
          </script>";
}

public function removeOrder() {
    global $conn;

    if (isset($_POST['order_id'])) {
        $orderId = $_POST['order_id'];

        mysqli_query($conn,
            "DELETE FROM orders 
             WHERE id = $orderId 
             AND user_id = {$_SESSION['user_id']}"
        );
    }

    echo "<script>
            alert('Order removed from history');
            window.location='index.php?page=orders';
          </script>";
    exit;
}

}
