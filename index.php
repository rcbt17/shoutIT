<?php

include 'lib/Init.php';

$user = new UserServices($databaseCon);

if(!$user->isUserOnline()){
    header('Location: login.php');
    exit;
}

?>