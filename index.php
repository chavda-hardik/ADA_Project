<?php
//! 1. Database connection
$host = 'localhost';
$db   = 'shorting'; 
$user = 'root';     
$pass = '';         

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM product";
$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row; 
    }
}
$conn->close();

//! 2. ADA SORTING ALGORITHMS


//* BUBBLE SORT O(n^2)
function bubbleSort($array, $key, $direction) {
    $n = count($array);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            $condition = $direction === 'asc' ? ($array[$j][$key] > $array[$j+1][$key]) : ($array[$j][$key] < $array[$j+1][$key]);
            if ($condition) {
                $temp = $array[$j];
                $array[$j] = $array[$j+1];
                $array[$j+1] = $temp;
            }
        }
    }
    return $array;
}

//* SELECTION SORT O(n^2)
function selectionSort($array, $key, $direction) {
    $n = count($array);
    for ($i = 0; $i < $n - 1; $i++) {
        $min_idx = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            $condition = $direction === 'asc' ? ($array[$j][$key] < $array[$min_idx][$key]) : ($array[$j][$key] > $array[$min_idx][$key]);
            if ($condition) {
                $min_idx = $j;
            }
        }
        $temp = $array[$min_idx];
        $array[$min_idx] = $array[$i];
        $array[$i] = $temp;
    }
    return $array;
}

//* INSERTION SORT O(n^2)
function insertionSort($array, $key, $direction) {
    $n = count($array);
    for ($i = 1; $i < $n; $i++) {
        $keyItem = $array[$i];
        $j = $i - 1;
        while ($j >= 0) {
            $condition = $direction === 'asc' ? ($array[$j][$key] > $keyItem[$key]) : ($array[$j][$key] < $keyItem[$key]);
            if ($condition) {
                $array[$j + 1] = $array[$j];
                $j = $j - 1;
            } else {
                break;
            }
        }
        $array[$j + 1] = $keyItem;
    }
    return $array;
}

//* QUICK SORT O(n log n)
function quickSort($array, $key, $direction) {
    if (count($array) <= 1) return $array;
    $pivot = $array[0];
    $left = [];
    $right = [];
    for ($i = 1; $i < count($array); $i++) {
        $condition = $direction === 'asc' ? ($array[$i][$key] < $pivot[$key]) : ($array[$i][$key] > $pivot[$key]);
        if ($condition) {
            $left[] = $array[$i];
        } else {
            $right[] = $array[$i];
        }
    }
    return array_merge(quickSort($left, $key, $direction), [$pivot], quickSort($right, $key, $direction));
}

//* MERGE SORT O(n log n)
function mergeSort($array, $key, $direction) {
    if (count($array) <= 1) return $array;
    $mid = intdiv(count($array), 2);
    $left = mergeSort(array_slice($array, 0, $mid), $key, $direction);
    $right = mergeSort(array_slice($array, $mid), $key, $direction);

    $result = [];
    $i = 0; $j = 0;
    while ($i < count($left) && $j < count($right)) {
        $condition = $direction === 'asc' ? ($left[$i][$key] <= $right[$j][$key]) : ($left[$i][$key] >= $right[$j][$key]);
        if ($condition) {
            $result[] = $left[$i++];
        } else {
            $result[] = $right[$j++];
        }
    }
    while ($i < count($left)) $result[] = $left[$i++];
    while ($j < count($right)) $result[] = $right[$j++];
    return $result;
}

//* CUSTOM HYBRID SORT (Merge + Insertion)
// Time Complexity: O(n log n) overall, but extremely fast constant factors.
function customHybridSort($array, $key, $direction) {
    // THE SECRET SAUCE: If the array is 16 items or smaller, 
    // Insertion Sort is actually faster than Merge Sort due to low overhead!
    if (count($array) <= 16) {
        return insertionSort($array, $key, $direction); 
    }

    // If it's larger than 16, split it like a Merge Sort
    $mid = intdiv(count($array), 2);
    $left = customHybridSort(array_slice($array, 0, $mid), $key, $direction);
    $right = customHybridSort(array_slice($array, $mid), $key, $direction);

    // Merge the sorted halves back together
    $result = [];
    $i = 0; $j = 0;
    while ($i < count($left) && $j < count($right)) {
        $condition = $direction === 'asc' ? ($left[$i][$key] <= $right[$j][$key]) : ($left[$i][$key] >= $right[$j][$key]);
        if ($condition) {
            $result[] = $left[$i++];
        } else {
            $result[] = $right[$j++];
        }
    }
    while ($i < count($left)) $result[] = $left[$i++];
    while ($j < count($right)) $result[] = $right[$j++];
    return $result;
}

//! PROCESS USER REQUEST & TIMER

$execution_time = 0; 
$selected_sort = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'none';
$selected_algo = isset($_GET['algo']) ? $_GET['algo'] : 'none';

if ($selected_sort !== 'none' && $selected_algo !== 'none') {
    
   // Safely map the selected sort to the exact database column name and direction
    if ($selected_sort === 'price_asc') { $key = 'price'; $direction = 'asc'; }
    elseif ($selected_sort === 'price_desc') { $key = 'price'; $direction = 'desc'; }
    // elseif ($selected_sort === 'rating_asc') { $key = 'rating'; $direction = 'asc'; }
    elseif ($selected_sort === 'rating_desc') { $key = 'rating'; $direction = 'desc'; }
    elseif ($selected_sort === 'delivery_asc') { $key = 'delivery_days'; $direction = 'asc'; } // This fixes the bug!
   
   
    $start_time = microtime(true); // START CLOCK

    switch ($selected_algo) {
        case 'bubble': $products = bubbleSort($products, $key, $direction); break;
        case 'selection': $products = selectionSort($products, $key, $direction); break;
        case 'insertion': $products = insertionSort($products, $key, $direction); break;
        case 'quick': $products = quickSort($products, $key, $direction); break;
        case 'merge': $products = mergeSort($products, $key, $direction); break;
        case 'custom': $products = customHybridSort($products, $key, $direction); break; // NEW!
    }
    
    $end_time = microtime(true); // STOP CLOCK
    $execution_time = ($end_time - $start_time) * 1000; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Sort Engine</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">
    <h2>ADA E-Commerce Sort Engine</h2>
    
    <?php if ($selected_sort !== 'none' && $selected_algo !== 'none'): ?>
        <div class="timer">
            <strong>⏱️ Algorithm Performance:</strong> Sorted <?= count($products) ?> items in <strong><?= number_format($execution_time, 4) ?> ms</strong> using <strong><?= strtoupper($selected_algo) ?> SORT</strong>.
        </div>
    <?php endif; ?>

    <div class="controls">
        <form method="GET" action="index.php">
            <div class="form-group">
                <label for="algo">1. Algorithm: </label>
                <select name="algo" id="algo" required>
                    <option value="" disabled selected>Select Algorithm...</option>
                    <option value="bubble" <?= $selected_algo == 'bubble' ? 'selected' : '' ?>>Bubble Sort - O(n²)</option>
                    <option value="selection" <?= $selected_algo == 'selection' ? 'selected' : '' ?>>Selection Sort - O(n²)</option>
                    <option value="insertion" <?= $selected_algo == 'insertion' ? 'selected' : '' ?>>Insertion Sort - O(n²)</option>
                    <option value="quick" <?= $selected_algo == 'quick' ? 'selected' : '' ?>>Quick Sort - O(n log n)</option>
                    <option value="merge" <?= $selected_algo == 'merge' ? 'selected' : '' ?>>Merge Sort - O(n log n)</option>
                    <option value="custom" <?= $selected_algo == 'custom' ? 'selected' : '' ?>>Custom Hybrid Sort - ⚡ Ultra Fast</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sort_by">2. Metric: </label>
                <select name="sort_by" id="sort_by" required>
                    <option value="" disabled selected>Select Metric...</option>
                    <option value="price_asc" <?= $selected_sort == 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= $selected_sort == 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                    <!-- <option value="rating_asc" <?= $selected_sort == 'rating_asc' ? 'selected' : '' ?>>Rating: Lowest First</option> -->
                    <option value="rating_desc" <?= $selected_sort == 'rating_desc' ? 'selected' : '' ?>>Rating: Highest First</option>
                    <option value="delivery_asc" <?= $selected_sort == 'delivery_asc' ? 'selected' : '' ?>>Delivery: Fastest First</option>
                </select>
            </div>

            <button type="submit">Execute Algorithm</button>
            <a href="index.php" class="btn-reset">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price ($)</th>
                    <th>Rating (★)</th>
                    <th>Delivery (Days)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?? 'N/A' ?></td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= number_format($product['rating'], 1) ?></td>
                        <td><?= $product['delivery_days'] ?> days</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>


