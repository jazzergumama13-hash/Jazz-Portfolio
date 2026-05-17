<?php
session_start();
include('../config/database.php');

// Protect Page
if(!isset($_SESSION['username'])){
    header("Location: ../auth/login.php");
    exit();
}

// Handle Transaction
if(isset($_POST['save_transaction'])){

    $product_id = $_POST['product_id'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];

    // Get current stock
    $check = mysqli_query($conn,
    "SELECT * FROM products WHERE product_id='$product_id'");

    $product = mysqli_fetch_assoc($check);

    $current_qty = $product['quantity'];

    // STOCK IN
    if($type == "IN"){

        $new_qty = $current_qty + $quantity;

        mysqli_query($conn,
        "UPDATE products
        SET quantity='$new_qty'
        WHERE product_id='$product_id'");

    }

    // STOCK OUT
    if($type == "OUT"){

        if($quantity > $current_qty){

            $error = "Not enough stock!";

        } else {

            $new_qty = $current_qty - $quantity;

            mysqli_query($conn,
            "UPDATE products
            SET quantity='$new_qty'
            WHERE product_id='$product_id'");

        }
    }

    // Save Transaction
    if(!isset($error)){

        mysqli_query($conn,
        "INSERT INTO transactions
        (product_id, type, quantity)
        VALUES
        ('$product_id', '$type', '$quantity')");

        $success = "Transaction Saved!";
    }
}

// Fetch Products
$products = mysqli_query($conn,
"SELECT * FROM products ORDER BY product_name ASC");

// Fetch Transactions
$transactions = mysqli_query($conn,
"SELECT transactions.*, products.product_name
FROM transactions
JOIN products
ON transactions.product_id = products.product_id
ORDER BY transaction_id DESC");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Transactions</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>

        body{
            background: #f4f6f9;
        }

        .sidebar{
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #212529;
            padding-top: 20px;
        }

        .sidebar h3{
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a{
            color: white;
            display: block;
            padding: 15px 20px;
            text-decoration: none;
        }

        .sidebar a:hover{
            background: #0d6efd;
        }

        .content{
            margin-left: 250px;
            padding: 30px;
        }

        .card{
            border-radius: 15px;
            border: none;
        }

    </style>

</head>

<body>

<!-- Sidebar -->
<div class="sidebar">

    <h3>Inventory System</h3>

    <a href="dashboard.php">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <a href="available_items.php">
        <i class="bi bi-box-seam"></i>
        Available Items
    </a>

    <a href="transaction.php">
        <i class="bi bi-arrow-left-right"></i>
        Transactions
    </a>

    <a href="../auth/logout.php">
        <i class="bi bi-box-arrow-right"></i>
        Logout
    </a>

</div>

<!-- Main Content -->
<div class="content">

    <h2 class="mb-4">Transactions</h2>

    <div class="row">

        <!-- Transaction Form -->
        <div class="col-md-4">

            <div class="card shadow p-4">

                <h4>Add Transaction</h4>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">

                        <label>Product</label>

                        <select name="product_id"
                                class="form-select"
                                required>

                            <option value="">Select Product</option>

                            <?php while($row = mysqli_fetch_assoc($products)): ?>

                                <option value="<?php echo $row['product_id']; ?>">

                                    <?php echo $row['product_name']; ?>
                                    (Stock: <?php echo $row['quantity']; ?>)

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Transaction Type</label>

                        <select name="type"
                                class="form-select"
                                required>

                            <option value="IN">Stock In</option>
                            <option value="OUT">Stock Out</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Quantity</label>

                        <input type="number"
                               name="quantity"
                               class="form-control"
                               required>

                    </div>

                    <button type="submit"
                            name="save_transaction"
                            class="btn btn-primary w-100">

                        Save Transaction

                    </button>

                </form>

            </div>

        </div>

        <!-- Transaction Table -->
        <div class="col-md-8">

            <div class="card shadow p-4">

                <h4>Transaction History</h4>

                <table class="table table-hover">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Date</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php while($row = mysqli_fetch_assoc($transactions)): ?>

                        <tr>

                            <td><?php echo $row['transaction_id']; ?></td>

                            <td><?php echo $row['product_name']; ?></td>

                            <td>

                                <?php
                                if($row['type'] == "IN"){
                                    echo "<span class='badge bg-success'>";
                                    echo "STOCK IN";
                                    echo "</span>";
                                } else {
                                    echo "<span class='badge bg-danger'>";
                                    echo "STOCK OUT";
                                    echo "</span>";
                                }
                                ?>

                            </td>

                            <td><?php echo $row['quantity']; ?></td>

                            <td><?php echo $row['transaction_date']; ?></td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>