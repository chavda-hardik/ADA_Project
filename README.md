# ADA E-Commerce Sort Engine

## 📖 Entire Description About the Project

**ADA E-Commerce Sort Engine** is a demonstration project built to showcase and compare the performance of various sorting algorithms in a real-world scenario: an e-commerce product listing. "ADA" stands for Analysis and Design of Algorithms. 

This project tackles the classic computer science problem of sorting large datasets by letting users sort thousands of mock e-commerce products (by price, rating, or delivery days) using different backend algorithms. It provides a visual representation of how fundamental algorithms perform in terms of execution time, demonstrating their real-world time complexity and efficiency.

### 🌟 Key Features
*   **Multiple Sorting Algorithms Implementation**: Includes classic algorithms like Bubble Sort, Selection Sort, Insertion Sort, Quick Sort, Merge Sort, and a highly optimized **Custom Hybrid Sort** (a mix of Merge and Insertion Sort).
*   **Algorithm Performance Tracking**: Accurately measures and displays the execution time (in milliseconds) of the selected algorithm.
*   **Dynamic Data Generation**: Includes a script (`generate_data.php`) that can instantly populate your database with thousands of mock products for robust testing.
*   **Interactive UI**: A clean, easy-to-use HTML/CSS interface allowing users to select the sorting criteria and the algorithm simultaneously.

## 🛠️ Technologies Used
*   **Backend**: PHP 
*   **Database**: MySQL
*   **Frontend**: HTML5, CSS3

---

## 🚀 How to Run This Project

Follow these steps to set up and run the project safely on your local machine.

### 1. Prerequisites
You will need a local server environment capable of running PHP and MySQL. 
*   Download and install **[XAMPP](https://www.apachefriends.org/index.html)**, **WAMP**, or **MAMP**.

### 2. Move Project Files
1.  Copy the entire project folder (`ADA`).
2.  Navigate to your local server directory:
    *   For **XAMPP**: `C:\xampp\htdocs\`
    *   For **WAMP**: `C:\wamp\www\`
3.  Paste the `ADA` folder there. The path should look like `C:\xampp\htdocs\ADA`.

### 3. Database Setup
1.  Start the **Apache** and **MySQL** modules from your XAMPP/WAMP Control Panel.
2.  Open your browser and navigate to `http://localhost/phpmyadmin/`.
3.  Create a new database named **`shorting`** (make sure it matches exactly, or update it in the code).
4.  Run the following SQL query in the SQL tab to create the required table:

```sql
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `delivery_days` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 4. Database Configuration (Optional)
By default, the application is set up for XAMPP's default credentials (username: `root`, password: `[empty]`). If you have a custom MySQL password, you need to update it in both:
*   `index.php` (Line 4)
*   `generate_data.php` (Line 4)
```php
$host = 'localhost';
$db   = 'shorting'; 
$user = 'root';     // Change if needed
$pass = '';         // Change if needed
```

### 5. Generate Mock Data
Before testing the sorting engine, you need data.
1. Open your browser and visit: `http://localhost/ADA/generate_data.php`
2. This will automatically generate and insert 1,000 random products into your database.
3. You will see a success message: *"Successfully inserted 1000 random products into the database!"*

---

## 💻 How to Use This Code

Once your setup is complete, you can interact with the main application.

1.  **Access the System**: Visit `http://localhost/ADA/index.php` in your web browser.
2.  **Select Sorting Criteria**: Choose how you want to sort the mock e-commerce items:
    *   Price (Low to High)
    *   Price (High to Low)
    *   Rating (High to Low)
    *   Delivery Days (Fastest First)
3.  **Choose the Algorithm**: Select the backend sorting engine:
    *   Bubble Sort $O(n^2)$
    *   Selection Sort $O(n^2)$
    *   Insertion Sort $O(n^2)$
    *   Quick Sort $O(n \log n)$
    *   Merge Sort $O(n \log n)$
    *   Custom Hybrid Sort $O(n \log n)$
4.  **Execute**: Click the Submit/Sort button.
5.  **Review the Output**: 
    *   The page will reload and display the products sorted according to your criteria.
    *   Pay attention to the **Timer Banner** at the top, which will display exactly how many milliseconds the chosen algorithm took to sort the array. 

### Experimentation
Try sorting the array with a slow algorithm like **Bubble Sort**, and observe the execution time. Then, change the dropdown to **Quick Sort** or the **Custom Hybrid Sort** and perform the exact same sort. You will notice a dramatic improvement in milliseconds, proving the theory behind algorithmic time complexity!

---

## 📁 Code Structure Synopsis

*   **`index.php`**: The "brain" of the project. It handles the database connection, contains all the sorting algorithm functions, tracks execution time using `microtime()`, and renders the HTML output loop.
*   **`generate_data.php`**: A utility script that randomly assigns product names, categories, and metrics, generating huge volumes of test data quickly.
*   **`style.css`**: Contains all the styling rules for the application to make it look like a modern dashboard and product grid.
