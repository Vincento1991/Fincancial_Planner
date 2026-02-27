<?php

// -------------------------------------------------
// Vercel Serverless Entry Point for Laravel
// -------------------------------------------------

// Create all required temp directories
$dirs = [
    '/tmp/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/testing',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Create SQLite database and run migrations if needed
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);

    // Auto-migrate: create tables directly via PDO
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS cashflow_reports (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama TEXT NOT NULL,
        bulan TEXT NOT NULL,
        tahun TEXT NOT NULL,
        created_at TEXT,
        updated_at TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cashflow_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        cashflow_report_id INTEGER NOT NULL,
        category TEXT NOT NULL,
        label TEXT NOT NULL,
        amount REAL DEFAULT 0,
        sort_order INTEGER DEFAULT 0,
        created_at TEXT,
        updated_at TEXT,
        FOREIGN KEY (cashflow_report_id) REFERENCES cashflow_reports(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
        id TEXT PRIMARY KEY,
        user_id INTEGER,
        ip_address TEXT,
        user_agent TEXT,
        payload TEXT NOT NULL,
        last_activity INTEGER NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cache (
        key TEXT PRIMARY KEY,
        value TEXT NOT NULL,
        expiration INTEGER NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cache_locks (
        key TEXT PRIMARY KEY,
        owner TEXT NOT NULL,
        expiration INTEGER NOT NULL
    )");

    $pdo = null;
}

// Forward to Laravel
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage(),
        'file'  => $e->getFile() . ':' . $e->getLine(),
        'trace' => array_slice(explode("\n", $e->getTraceAsString()), 0, 10),
    ]);
    exit(1);
}
