<?php 
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';

//Session Data
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

$_SESSION['ok'] = "yes"; //DEBUG
?>
<div id="menu">
    <ul>
        <li><a href="./manage.php">Make List</a></li>
        <li><a href="./cookbook.php">Build Cookbook</a></li>
    </ul>
</div>