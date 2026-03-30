<?php 

require MODEL . "db/db_user.php"; 

$ku = new User;
$ku->testConnect();