<?php 
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';

//Session Data
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
?>
<div id="menu">
    <ul>
        <li><a href="./manage.php">Create List</a></li>
        <li><a href="./cookbook.php">Edit Cookbook</a></li>
    </ul>
</div>