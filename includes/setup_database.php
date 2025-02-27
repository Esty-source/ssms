<?php
require_once 'config.php';

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read the SQL schema
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    
    // Split the SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach($statements as $statement) {
        if($statement) {
            try {
                $conn->exec($statement);
            } catch(PDOException $e) {
                echo "Error executing statement: " . $e->getMessage() . "\n";
                echo "Statement: " . $statement . "\n\n";
            }
        }
    }
    
    echo "Database setup completed successfully!";
} catch(Exception $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
