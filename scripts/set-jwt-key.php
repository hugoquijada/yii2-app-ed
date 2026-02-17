<?php

/**
 * Post-install script to generate a random JWT key in config/params.php
 */

$paramsPath = __DIR__ . '/../config/params.php';

if (!file_exists($paramsPath)) {
  echo "Warning: config/params.php not found.\n";
  exit(0);
}

$content = file_get_contents($paramsPath);

// Generate a secure random string (32 bytes = 44 base64 chars or 64 hex chars)
// Using bin2hex for a simpler character set, or base64 for more density.
// The user had 'Aañsdas@x2?33x-$A3?_' which is a mix.
// Let's go with a 64 character hex string for simplicity and security.
$newKey = bin2hex(random_bytes(32));

// Look for 'jwt.key' => 'something'
$pattern = "/(['\"]jwt\.key['\"]\s*=>\s*)(['\"]).*?(['\"])/";

if (preg_match($pattern, $content)) {
  // Check if it's already set to something that doesn't look like a placeholder
  // If the user wants it to be generated EVERY time, we just replace it.
  // If the user said "after install", usually it's for new setups.
  // But since they asked "how to make it so that when installing... it puts a random string", 
  // I'll assume they want to ensure a secure key is there.

  $newContent = preg_replace_callback($pattern, function ($m) use ($newKey) {
    return $m[1] . $m[2] . $newKey . $m[3];
  }, $content);

  if ($content !== $newContent) {
    file_put_contents($paramsPath, $newContent);
    echo "JWT key successfully generated in config/params.php\n";
  } else {
    echo "JWT key was already updated or pattern not perfectly matched.\n";
  }
} else {
  echo "Error: 'jwt.key' entry not found in config/params.php. Please add it manually first.\n";
}
