<?php 
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';

//Session Data
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

include_once $front_matter['www'] . '/snippets/header.php';
?>
<div id="menu" class="container">
    <div class="col-md-12 text-center">
        <ul class="list-inline list-unstyled">
            <li><a class="btn btn-primary" href="./manage.php">Create List</a></li>
            <li><a class="btn btn-primary"  href="./cookbook.php">Edit Cookbook</a></li>
        </ul>
    </div>
</div>
<?php include_once $front_matter['www'] . '/snippets/footer.php'; ?>