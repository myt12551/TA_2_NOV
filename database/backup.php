<?php

function parseEnvFile() {
    $envFile = __DIR__ . '/../.env';
    if (!file_exists($envFile)) {
        die(".env file not found!");
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim(str_replace('"', '', $value));
        }
    }
    
    return $env;
}

$env = parseEnvFile();
$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbName = $env['DB_DATABASE'] ?? 'laravelpos';
$dbUser = $env['DB_USERNAME'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all tables
    $tables = [];
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    $output = "-- Database backup created on " . date('Y-m-d H:i:s') . "\n\n";
    
    // For each table
    foreach ($tables as $table) {
        // Get create table statement
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $output .= "\n\n" . $row[1] . ";\n\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $fields = array_map(function($field) use ($pdo) {
                if ($field === null) return 'NULL';
                return $pdo->quote($field);
            }, $row);
            $output .= "INSERT INTO `$table` VALUES (" . implode(", ", $fields) . ");\n";
        }
    }
    
    $backupFile = __DIR__ . '/backup_' . date('Ymd_His') . '.sql';
    file_put_contents($backupFile, $output);
    echo "Backup created successfully: " . basename($backupFile) . "\n";
    
} catch(PDOException $e) {
    echo "Error creating backup: " . $e->getMessage() . "\n";
}