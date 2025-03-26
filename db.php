Here's an example of PHP code that confirms a database connection using MySQLi (MySQL Improved) extension:

```php
<?php
$servername = "localhost"; // Change this to your database server name
$username = "glas_user"; // Change this to your database username
$password = "w1mnIGlmijHCw1s5"; // Change this to your database password
$dbname = "glas_db"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

// Close connection
$conn->close();
?>
```

Replace "localhost", "username", "password", and "database_name" with your actual database server details. This code attempts to establish a connection to the specified database and prints a success message if the connection is successful. Otherwise, it prints an error message indicating the reason for the connection failure.
