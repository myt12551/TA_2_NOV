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
$sqlFile = 'h:\SKRIPSI SISTEM ALIFF\TANGGAL 23\TA_27OCT_BARU\laravelpos.sql';
$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbName = $env['DB_DATABASE'] ?? 'laravelpos';
$dbUser = $env['DB_USERNAME'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? '';

try {
    // First connect without database name to drop and recreate it
    $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop and recreate database
    $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
    $pdo->exec("CREATE DATABASE `$dbName`");
    $pdo->exec("USE `$dbName`");
    
    // Now execute the SQL dump
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Could not read SQL file: $sqlFile");
    }
    
    $pdo->exec($sql);
    echo "Database restored successfully!\n";
} catch(PDOException $e) {
    echo "Error restoring database: " . $e->getMessage() . "\n";
}