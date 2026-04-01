<?php 

require DB . "db_user.php"; 

$ka = new User;
$ka->getUserContent('email');

$ku = new User;
$ku->getUserById(3);

$kka = new User;
$kka->getUserByEmail('admin@admin.fr');

$ki = new User;
// $ki->addUser([
//     'email'    => 'pasadmin@admin.fr',
//     'password' => 'kukikoool',
//     'pseudo'   => 'kuki'
// ]);
?>
<pre>
    Variable ka : <?= var_dump($ka) ?>
    Variable ku : <?= var_dump($ku) ?>
    Variable kka : <?= var_dump($kka) ?>
    Variable ki : <?= var_dump($ki) ?>
    
</pre>