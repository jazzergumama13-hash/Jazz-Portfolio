<?php
session_start();
include('../config/database.php');

// Protect Page
if(!isset($_SESSION['username'])){
    header("Location: ../auth/login.php");
    exit();
}

// Inventory Data
$products = mysqli_query($conn,
"SELECT * FROM products ORDER BY product_name ASC");

// Transaction Data
$transactions = mysqli_query($conn,
"SELECT transactions.*, products.product_name
FROM transactions
JOIN products ON transactions.product_id = products.product_id
ORDER BY transaction_date DESC");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Reports</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background: #f4f6f9;
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
            padding: 15px;
            text-decoration: none;
        }

        .sidebar a:hover{
            background: #0d6efd;
        }

        .content{
            margin-left: 250px;
            padding: 30px;
        }

        .print-btn{
            margin-bottom: 20px;
        }

        /* PRINT STYLES */
        @media print {

            .sidebar,
            .print-btn,
            .no-print {
                display: none !important;
            }

            .content{
                margin-left: 0;
                padding: 0;
            }

            body{
                background: white;
            }

        }

    </style>

</head>

<body>

<!-- Sidebar -->
<div class="sidebar">

    <h3>Inventory System</h3>

    <a href="dashboard.php">Dashboard</a>
    <a href="available_items.php">Available Items</a>
    <a href="transaction.php">Transactions</a>
    <a href="reports.php">Reports</a>
    <a href="../auth/logout.php">Logout</a>

</div>

<!-- Content -->
<div class="content">

    <h2>Reports</h2>
    <p>Printable Inventory & Transaction Reports</p>

    <button onclick="window.print()"
            class="btn btn-primary print-btn">

        Print Report

    </button>

    <!-- INVENTORY REPORT -->
    <div class="card p-3 mb-4">

        <h4>Inventory Report</h4>

        <table class="table table-bordered">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>

            </thead>

            <tbody>

            <?php while($row = mysqli_fetch_assoc($products)): ?>

                <tr>

                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td>₱<?php echo number_format($row['price'],2); ?></td>
                    <td><?php echo $row['quantity']; ?></td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

    <!-- TRANSACTION REPORT -->
    <div class="card p-3">

        <h4>Transaction Report</h4>

        <table class="table table-bordered">

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
                        <?php if($row['type']=="IN"): ?>
                            <span class="badge bg-success">IN</span>
                        <?php else: ?>
                            <span class="badge bg-danger">OUT</span>
                        <?php endif; ?>
                    </td>

                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['transaction_date']; ?></td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>