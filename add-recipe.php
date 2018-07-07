<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
include_once $front_matter['www'] . '/snippets/mealplanner_fn.php';
session_start();

$recipe_added = false;
if (isset($_POST['rec-name'])) {
    $recName = $_POST['rec-name'];
    unset($_POST['rec-name']);
    
    $preprep = $_POST['preprep'];
    unset($_POST['preprep']);
    
    $cooking = $_POST['cooking'];
    unset($_POST['cooking']);
    
    $serving = $_POST['serving'];
    unset($_POST['serving']);
    
    $ingredients = array();
    
    for ($i = 1; $i < 1 + (count($_POST) / 3); $i++) {
        $ingredient = array();
        $ingredient['iName'] = $_POST['iName-' . (string)$i];
        $ingredient['measure'] = $_POST['amount-' . (string)$i];
        $ingredient['unit'] = $_POST['unit-' . (string)$i];
        
        array_push($ingredients, $ingredient);
    }
    
    $ingredients = json_encode($ingredients);
    $preprep = $preprep . "*";
    $serving = "~" . $serving;
    
    if (check_db($recName) > 0) {
        $alert = "ALERT: RECIPE ALREADY EXISTS";
    } else {
        $recipe_added = addRecipe($recName, $ingredients, $preprep, $cooking, $serving);
        $alert = "Recipe successfully added to database!";
    }
    
}

include_once $front_matter['www'] . '/snippets/header.php';
?>
<?php if (!$recipe_added) { ?>
    <div class="container">
        <div id="recipe-builder col-md-4 col-md-offset-2">
            <form id="builder-form" action="" method="post">
                <div class="indiv-ingred">
                    <div class="form-group">
                        <label for="rec-name">Recipe Name</label>
                        <input name="rec-name" id="rec-name" type="text" class="form-control">
                    </div>
                    <div class="form-group">                
                        <label for="iName-1" id="i-lbl-1">Ingredient Name:</label>
                        <input id="iName-1" name="iName-1" type="text"  class="form-control">
                    </div>
                    <div class="form-group">                
                        <label for="unit-1" id="u-lbl-1">Units:</label>
                        <input name="unit-1" id="unit-1" type="text"  class="form-control">
                    </div>
                    <div class="form-group">                
                        <label for="amount-1" id="a-lbl-1">Amount:</label>
                        <input name="amount-1" id="amount-1" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <button id="rmv-1" type="button" class="rmv-ingred btn btn-danger" onClick="removeIngredient('1')">Remove ingredient</button>
                    </div>
                </div>
            </form>
            <button class="btn btn-primary" type="button" onclick="addIngredient()">Add ingredient</button><br>
            <label for="preprep">Prepreparation Instructions:</label>
            <textarea name="preprep" rows="4" cols="50" form="builder-form" class="form-control"></textarea>
            <label for="cooking">Cooking Instructions:</label>
            <textarea name="cooking" rows="4" cols="50" form="builder-form" class="form-control"></textarea>
            <label for="serving">Serving Instructions:</label>
            <textarea name="serving" rows="4" cols="50" form="builder-form" class="form-control"></textarea>
            <input type="submit" value="Add recipe" form="builder-form" class="form-control btn btn-primary">
        </div>
    </div>
<?php } else { ?>
    <!--ALERT HERE WHEN CSS ADDED -->
    <div id="btn-actions">
        <ul>
            <li><a href="./add-recipe.php">Add another recipe</a></li>
            <li><a href="./cookbook.php">Back to cookbook</a></li>
        </ul>
    </div>
<?php } ?>
</body>
</html>
<script src="<?php echo $front_matter['www']; ?>/static/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $front_matter['www']; ?>/static/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $front_matter['www']; ?>/static/js/cookbook_fn.js" type="text/javascript"></script>


