<?php

/**
 * Manual script to generate a secure random JWT key in a hidden file
 */

$keyFilePath = __DIR__ . '/../.jwt-key';

// Generate a secure random string (32 bytes = 64 hex chars)
$newKey = bin2hex(random_bytes(32));

if (file_exists($keyFilePath)) {
  echo "The file .jwt-key already exists. To avoid breaking existing tokens, it will not be overwritten.\n";
  echo "If you really want to change it, delete the file manually first.\n";
  exit(0);
}

if (file_put_contents($keyFilePath, $newKey)) {
  echo "JWT key successfully generated in .jwt-key\n";
  echo "Make sure this file is ignored by Git.\n";
} else {
  echo "Error: Could not create .jwt-key file. Check permissions.\n";
}
