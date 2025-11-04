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

    // Connect without database first
    $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop and recreate database
    $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
    $pdo->exec("CREATE DATABASE `$dbName`");
    $pdo->exec("USE `$dbName`");
    
    // Temporarily disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    
    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/laravelpos.sql');
    if ($sql === false) {
        throw new Exception("Could not read backup file");
    }

    // Split SQL into individual statements
    $pattern = '/;\s*$/m';
    $statements = preg_split($pattern, $sql, -1, PREG_SPLIT_NO_EMPTY);

    // Execute each statement
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                echo "Warning: " . $e->getMessage() . "\n";
                echo "Query was: " . substr($statement, 0, 100) . "...\n";
                // Continue despite errors
                continue;
            }
        }
    }
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    echo "Database restored successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // Re-enable foreign key checks in case of error
    if (isset($pdo)) {
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    }
}