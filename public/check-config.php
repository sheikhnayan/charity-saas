<?php
echo "Current PHP Settings:\n";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post Max Size: " . ini_get('post_max_size') . "\n";
echo "Max File Uploads: " . ini_get('max_file_uploads') . "\n";
echo "Max Input Time: " . ini_get('max_input_time') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";

// Set runtime limits
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '12M');
ini_set('max_file_uploads', '20');
ini_set('max_input_time', '300');
ini_set('max_execution_time', '300');
ini_set('memory_limit', '256M');

echo "\nAfter runtime changes:\n";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post Max Size: " . ini_get('post_max_size') . "\n";
echo "Max File Uploads: " . ini_get('max_file_uploads') . "\n";
?>
