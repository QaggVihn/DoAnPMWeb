<?php
$textToHash = 'admin123'; 
echo password_hash($textToHash, PASSWORD_DEFAULT);
?>