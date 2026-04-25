<?php
// Database connection credentials
$host = 'localhost';
$db = 'shorting'; // Based on your screenshot
$user = 'root';     // Default XAMPP user
$pass = '';         // Default XAMPP password is empty

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Arrays of sample data to mix and match
$categories = ['Electronics', 'Clothing', 'Home & Kitchen', 'Books', 'Sports'];
$adjectives = ['Premium', 'Wireless', 'Ergonomic', 'Smart', 'Portable', 'Vintage'];
$nouns = ['Headphones', 'Jacket', 'Coffee Maker', 'Novel', 'Dumbbells', 'Watch'];

echo "Generating mock data...<br>";

for ($i = 0; $i < 1000; $i++) {
    // Generate random product details
    $category = $categories[array_rand($categories)];
    $product_name = $adjectives[array_rand($adjectives)] . " " . $nouns[array_rand($nouns)];
    
    // Generate random price between 10.00 and 999.99
    $price = mt_rand(1000, 99999) / 100; 
    
    // Generate random rating between 1.0 and 5.0
    $rating = mt_rand(10, 50) / 10; 
    
    // Generate random delivery days between 1 and 14
    $delivery_days = mt_rand(1, 14); 

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO product (product_name, category, price, rating, delivery_days) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddi", $product_name, $category, $price, $rating, $delivery_days);
    $stmt->execute();
}

echo "Successfully inserted 1000 random products into the database!";

$conn->close();
?>