<?php

/**
 * Script to fix server permissions and groups (nginx:775)
 * Optimized for local dev to avoid errors.
 */

$root = dirname(__DIR__);
$dirs = [
  $root . '/runtime',
  $root . '/publico/assets',
  $root . '/publico/recursos',
  $root . '/vendor/mpdf/mpdf/tmp',
];

$targetGroup = 'nginx';
$permissions = 0775;

// Check if we are in a Linux/Unix environment
$isUnix = DIRECTORY_SEPARATOR === '/';

foreach ($dirs as $path) {
  // Basic check: if it's inside vendor and vendor doesn't exist, skip
  if (strpos($path, '/vendor/') !== false && !is_dir($root . '/vendor')) {
    continue;
  }

  if (!is_dir($path)) {
    // Try to create it, but don't fail if we can't
    if (!@mkdir($path, $permissions, true) && !is_dir($path)) {
      echo "Skipping $path (could not create or doesn't exist)\n";
      continue;
    }
  }

  echo "Processing " . str_replace($root . '/', '', $path) . "...";

  // 1. Set permissions (775)
  // Use @ to silence warnings if we don't own the folder
  if (@chmod($path, $permissions)) {
    echo " [OK:775]";
  } else {
    echo " [Skip:Permissions]";
  }

  // 2. Set Group (nginx)
  if ($isUnix && function_exists('posix_getgrnam')) {
    $groupInfo = @posix_getgrnam($targetGroup);
    if ($groupInfo) {
      // Group exists, try to apply it
      if (@chgrp($path, $targetGroup)) {
        echo " [OK:Group]";
      } else {
        // Group exists but we lack permissions (standard in local dev)
        echo " [Info:Need-Sudo-for-Group]";
      }
    } else {
      // Group doesn't exist (Mac/Local dev), skip silently
      echo " [Info:Group-Not-Found]";
    }
  }

  echo "\n";
}
