<?php
include('../config/database.php');

if(isset($_POST['add_product'])){

    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO products
    (product_name, category, price, quantity)
    VALUES
    ('$name','$category','$price','$quantity')";

    mysqli_query($conn, $query);

    echo "Product Added!";
}
?>

<form method="POST">

    <input type="text" name="product_name" placeholder="Product Name">

    <input type="text" name="category" placeholder="Category">

    <input type="number" step="0.01" name="price" placeholder="Price">

    <input type="number" name="quantity" placeholder="Quantity">

    <button name="add_product">Add Product</button>

</form>

<?php

//Stockin - Stockout

$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

    echo $row['product_name'] . " - ";
    echo $row['quantity'] . "<br>";
}
?>

<?php

$product_id = 1;
$sold_qty = 5;

$check = mysqli_query($conn,
"SELECT quantity FROM products WHERE product_id=$product_id");

$row = mysqli_fetch_assoc($check);

if($row['quantity'] >= $sold_qty){

    mysqli_query($conn,
    "UPDATE products
     SET quantity = quantity - $sold_qty
     WHERE product_id=$product_id");

    echo "Stock Updated";

} else {
    echo "Not enough stock";
}

?>