#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Trigger Wayfinder when changes are made to controller or route files.
 */

// Read configuration from environment variables
$watchCmd = getenv('WATCH_CMD') ?: 'php artisan wayfinder:generate';
$interval = getenv('WATCH_INTERVAL') ?: 1; // Polling interval in seconds

// Initial scan
message("Initializing...");
message("Using watch command: {$watchCmd}");
$timestamps = scan();

// Watch loop
message("Watching for controller and route file changes...");
while (true) {
    $changes = false;

    // Clear PHP's file stat cache to ensure fresh results
    clearstatcache(true);

    // Get current state of directory
    $files = scan();

    // Check for new or modified files
    foreach ($files as $path => $mtime) {
        if (!isset($timestamps[$path])) {
            message("New file: {$path}");
            $changes = true;
        } elseif ($timestamps[$path] !== $mtime) {
            message("File changed: {$path}");
            $changes = true;
        }
    }

    // Check for deleted files
    foreach ($timestamps as $path => $mtime) {
        if (!isset($files[$path])) {
            message("File deleted: {$path}");
            $changes = true;
        }
    }

    // Update our list of file timestamps
    $timestamps = $files;

    // Update Wayfinder if changes have been detected
    if ($changes) {
        runWayfinder();
    }

    // Memory cleanup
    unset($files);
    gc_collect_cycles();

    // Wait before next check
    sleep($interval);
}

/**
 * Read controller and route file modification timestamps from the file system.
 *
 * @param array<string> $directory
 * @return array<string, int>
 */
function scan(array $directory = ['app/Http', 'routes']): array
{
    $files = [];

    foreach ($directory as $dir) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
        );

        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }
            $files[$file->getPathname()] = $file->getMTime();
        }

        // Free the iterator;
        unset($iterator);
    }

    return $files;
}

/**
 * Run the configured watch command with exec().
 */
function runWayfinder(): void
{
    global $watchCmd;

    exec($watchCmd . ' 2>&1', $output, $returnCode);
    message('Watch command completed');

    if ($returnCode !== 0) {
        message("Task failed with code {$returnCode}");
        foreach ($output as $line) {
            message($line);
        }
    } else {
        foreach ($output as $line) {
            message($line);
        }
    }

    // free output
    unset($output);
}

/**
 * Log messages to STDOUT with a timestamp.
 */
function message(string $message): void
{
    fwrite(STDOUT, "  " . date('Y-m-d H:i:s') . " MONITOR: {$message}\n");
    fflush(STDOUT);
}
