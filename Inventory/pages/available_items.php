<?php
session_start();

include('../config/database.php');

// Protect Page
if(!isset($_SESSION['username'])){
    header("Location: ../auth/login.php");
    exit();
}

// Add Item
if(isset($_POST['add_item'])){

    $product_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO products
    (product_name, category, price, quantity)
    VALUES
    ('$product_name', '$category', '$price', '$quantity')";

    if(mysqli_query($conn, $query)){
        $success = "Item Added Successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch Products
$products = mysqli_query($conn,
"SELECT * FROM products ORDER BY product_id DESC");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Available Items</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>

        body{
            background-color: #f4f6f9;
        }

        /* Sidebar */

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
            transition: 0.3s;
        }

        .sidebar a:hover{
            background: #0d6efd;
        }

        /* Main Content */

        .content{
            margin-left: 250px;
            padding: 30px;
        }

        .card{
            border: none;
            border-radius: 15px;
        }

        .table-container{
            background: white;
            border-radius: 15px;
            padding: 20px;
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

    <a href="reports.php">
        <i class="bi bi-bar-chart"></i>
        Reports
    </a>

    <a href="../auth/logout.php">
        <i class="bi bi-box-arrow-right"></i>
        Logout
    </a>

</div>

<!-- Main Content -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Available Items</h2>
            <p>Manage your inventory products</p>
        </div>

    </div>

    <div class="row">

        <!-- Add Item Form -->
        <div class="col-md-4">

            <div class="card shadow p-4">

                <h4 class="mb-4">Add Item</h4>

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

                        <label>Product Name</label>

                        <input type="text"
                               name="product_name"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label>Category</label>

                        <input type="text"
                               name="category"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label>Price</label>

                        <input type="number"
                               step="0.01"
                               name="price"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label>Quantity</label>

                        <input type="number"
                               name="quantity"
                               class="form-control"
                               required>

                    </div>

                    <button type="submit"
                            name="add_item"
                            class="btn btn-primary w-100">

                        Add Item

                    </button>

                </form>

            </div>

        </div>

        <!-- Product Table -->
        <div class="col-md-8">

            <div class="table-container shadow">

                <h4 class="mb-4">Inventory Items</h4>

                <table class="table table-hover">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php while($row = mysqli_fetch_assoc($products)): ?>

                        <tr>

                            <td><?php echo $row['product_id']; ?></td>

                            <td><?php echo $row['product_name']; ?></td>

                            <td><?php echo $row['category']; ?></td>

                            <td>
                                ₱<?php echo number_format($row['price'], 2); ?>
                            </td>

                            <td>

                                <?php
                                if($row['quantity'] <= 5){

                                    echo "<span class='badge bg-danger'>";
                                    echo $row['quantity'] . " Low";
                                    echo "</span>";

                                } else {

                                    echo "<span class='badge bg-success'>";
                                    echo $row['quantity'];
                                    echo "</span>";

                                }
                                ?>

                            </td>

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