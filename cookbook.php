<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
include_once $front_matter['www'] . '/snippets/mealplanner_fn.php';
session_start();

$recipes = getAllRecipes($conn);
$recipe_json = json_encode($recipes);

include_once $front_matter['www'] . '/snippets/header.php';
?>

<div id="cookbook" class="container">
        <?php if (!empty($recipes)) { ?>
            <?php
            foreach ($recipes as $n => $data) {
                echo createSection($n, $recipes);
            }
            ?>
            <div id="recipe-list-actions">
                <a class="btn btn-primary" href="./add-recipe.php">Add recipe</a>
            </div>
        <?php } else {
            echo '<p>No recipes found.</p>';
        }?>
</div>
<script type="text/javascript">
var recipes = '<?php echo str_replace("\\r\\n", "<br>", $recipe_json); ?>';
recipes = JSON.parse(recipes);
</script>
<script src="<?php echo $front_matter['www']; ?>/static/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $front_matter['www']; ?>/static/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $front_matter['www']; ?>/static/js/cookbook_fn.js" type="text/javascript"></script>
<?php include_once $front_matter['www'] . '/snippets/footer.php'; ?>

