<?php
$password = 'CAllofduty123@%';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "New Hashed Password: " . $hashed_password;
?>
