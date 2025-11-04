<?php

// Get database configuration from .env
function getEnvValue($key) {
    $path = __DIR__ . '/.env';
    if (!file_exists($path)) {
        throw new Exception('.env file not found');
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, $key . '=') === 0) {
            return trim(explode('=', $line)[1]);
        }
    }
    return null;
}

try {
    // Database configuration
    $dbHost = getEnvValue('DB_HOST') ?: '127.0.0.1';
    $dbName = getEnvValue('DB_DATABASE') ?: 'laravelpos';
    $dbUser = getEnvValue('DB_USERNAME') ?: 'root';
    $dbPass = getEnvValue('DB_PASSWORD') ?: '';

    // Connect to database
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get all tables
    $tables = [];
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $output = "-- Database backup created on " . date('Y-m-d H:i:s') . "\n\n";

    // Process each table
    foreach ($tables as $table) {
        // Get create table statement
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $output .= "\n\n" . $row[1] . ";\n\n";

        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $values = array_map(function ($value) use ($pdo) {
                if ($value === null) return 'NULL';
                return $pdo->quote($value);
            }, $row);
            $output .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
        }
    }

    // Save to file
    $backupFile = __DIR__ . '/laravelpos.sql';
    if (file_put_contents($backupFile, $output)) {
        echo "Database backup created successfully at: " . $backupFile . "\n";
    } else {
        echo "Error writing backup file\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}