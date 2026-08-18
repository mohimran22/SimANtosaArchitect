<?php

echo '<pre>';
echo 'PHP VERSION       : ' . PHP_VERSION . PHP_EOL;
echo 'PHP SAPI          : ' . PHP_SAPI . PHP_EOL;
echo 'Loaded php.ini    : ' . (php_ini_loaded_file() ?: 'NONE') . PHP_EOL;
echo 'max_input_vars    : ' . ini_get('max_input_vars') . PHP_EOL;
echo 'max_multipart     : ' . ini_get('max_multipart_body_parts') . PHP_EOL;
echo 'post_max_size     : ' . ini_get('post_max_size') . PHP_EOL;
echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;
echo 'max_file_uploads : ' . ini_get('max_file_uploads') . PHP_EOL;
echo 'memory_limit      : ' . ini_get('memory_limit') . PHP_EOL;
echo 'max_execution_time: ' . ini_get('max_execution_time') . PHP_EOL;
echo '</pre>';