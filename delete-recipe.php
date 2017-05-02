<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
include_once $front_matter['www'] . '/snippets/mealplanner_fn.php';
session_start();

if (isset($_GET['id'])) {
    deleteRecipe($_GET['id']);
    echo "Recipe deleted";
} else {
    echo "No recipe to delete.";
}
?>
<html>
<head>
    <title>Frozen Meal Planner</title>
</head>
<body>
<div id="nav-btns">
    <ul>
        <li><a href="./cookbook.php">Back to cookbook</a></li>
    </ul>
</div>
</body>
</html>