<?php
session_start();
include('../config/database.php');

// Protect Page
if(!isset($_SESSION['username'])){
    header("Location: ../auth/login.php");
    exit();
}

// Counts
$total_products = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM products")
);

$low_stock = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM products WHERE quantity <= 5")
);

$total_users = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM users")
);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Inventory Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>

        body{
            background-color: #f4f6f9;
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
            transition: 0.3s;
        }

        .sidebar a:hover{
            background: #0d6efd;
        }

        .content{
            margin-left: 250px;
            padding: 30px;
        }

        .card-box{
            border: none;
            border-radius: 15px;
            color: white;
            padding: 25px;
        }

        .bg-blue{
            background: #0d6efd;
        }

        .bg-green{
            background: #198754;
        }

        .bg-red{
            background: #dc3545;
        }

        .card-box i{
            font-size: 40px;
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
        Dashboard
    </a>

    <a href="available_items.php">
        Available Items
    </a>

    <a href="transaction.php">
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
            <h2>Dashboard</h2>
            <p>Welcome, <?php echo $_SESSION['username']; ?> 👋</p>
        </div>

    </div>

    <!-- Statistic Cards -->
    <div class="row">

        <div class="col-md-4 mb-4">

            <div class="card-box bg-blue shadow">

                <div class="d-flex justify-content-between">

                    <div>
                        <h5>Total Products</h5>
                        <h2><?php echo $total_products; ?></h2>
                    </div>

                    <i class="bi bi-box"></i>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-4">

            <div class="card-box bg-red shadow">

                <div class="d-flex justify-content-between">

                    <div>
                        <h5>Low Stock</h5>
                        <h2><?php echo $low_stock; ?></h2>
                    </div>

                    <i class="bi bi-exclamation-triangle"></i>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-4">

            <div class="card-box bg-green shadow">

                <div class="d-flex justify-content-between">

                    <div>
                        <h5>Total Users</h5>
                        <h2><?php echo $total_users; ?></h2>
                    </div>

                    <i class="bi bi-people"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- Recent Products -->
    <div class="table-container shadow">

        <h4 class="mb-4">Recent Products</h4>

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

            <?php

            $products = mysqli_query($conn,
            "SELECT * FROM products ORDER BY product_id DESC LIMIT 5");

            while($row = mysqli_fetch_assoc($products)):

            ?>

                <tr>

                    <td><?php echo $row['product_id']; ?></td>

                    <td><?php echo $row['product_name']; ?></td>

                    <td><?php echo $row['category']; ?></td>

                    <td>₱<?php echo number_format($row['price'], 2); ?></td>

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

</body>
</html>