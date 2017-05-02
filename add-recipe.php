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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Frozen Meal Planner</title>
</head>
<body>
<?php if (!$recipe_added) { ?>
    <div id="recipe-builder">
        <form id="builder-form" action="" method="post">
            <label for="rec-name">Recipe Name:</label>
            <input name="rec-name" id="rec-name" type="text"><br>
            <label for="iName-1" id="i-lbl-1">Ingredient Name:</label>
            <input id="iName-1" name="iName-1" type="text">
            <label for="unit-1" id="u-lbl-1">Units:</label>
            <input name="unit-1" id="unit-1" type="text">
            <label for="amount-1" id="a-lbl-1">Amount:</label>
            <input name="amount-1" id="amount-1" type="text">
            <button id="rmv-1" type="button" class="rmv-ingred" onClick="removeIngredient('1')">Remove ingredient</button><br id="spc-1">
        </form>
        <button type="button" onclick="addIngredient()">Add ingredient</button><br>
        <label for="preprep">Prepreparation Instructions:</label>
        <textarea name="preprep" rows="4" cols="50" form="builder-form"></textarea><br>
        <label for="cooking">Cooking Instructions:</label>
        <textarea name="cooking" rows="4" cols="50" form="builder-form"></textarea><br>
        <label for="serving">Serving Instructions:</label>
        <textarea name="serving" rows="4" cols="50" form="builder-form"></textarea><br>
        <input type="submit" value="Add recipe" form="builder-form">
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
<script type="text/javascript">
var INGREDIENT_COUNT = 1;
    
function addIngredient() {
    INGREDIENT_COUNT++;  
    var builder_form = document.getElementById("builder-form");
    
    var iName_lbl = document.createElement("label");
    iName_lbl.appendChild(document.createTextNode("Ingredient Name:"));
    iName_lbl.for = "iName-" + INGREDIENT_COUNT;
    iName_lbl.id = "i-lbl-" + INGREDIENT_COUNT;
    var iName_input = document.createElement("input");
    iName_input.name = "iName-" + INGREDIENT_COUNT;
    iName_input.id = "iName-" + INGREDIENT_COUNT;
    iName_input.type = "text";
    
    var unit_lbl = document.createElement("label");
    unit_lbl.appendChild(document.createTextNode("Units:"));
    unit_lbl.for = "unit-" + INGREDIENT_COUNT;
    unit_lbl.id = "u-lbl-" + INGREDIENT_COUNT;
    var unit_input = document.createElement("input");
    unit_input.name = "unit-" + INGREDIENT_COUNT;
    unit_input.id = "unit-" + INGREDIENT_COUNT;
    unit_input.type = "text";
    
    var amount_lbl = document.createElement("label");
    amount_lbl.appendChild(document.createTextNode("Amount:"));
    amount_lbl.for = "amount-" + INGREDIENT_COUNT;
    amount_lbl.id = "a-lbl-" + INGREDIENT_COUNT;
    var amount_input = document.createElement("input");
    amount_input.name = "amount-" + INGREDIENT_COUNT;
    amount_input.id = "amount-" + INGREDIENT_COUNT;
    amount_input.type = "text";
    
    var remove_button = document.createElement("button");
    remove_button.type = "button";
    remove_button.class = "rmv-ingred";
    remove_button.id = "rmv-" + INGREDIENT_COUNT;
    remove_button.appendChild(document.createTextNode("Remove ingredient"));
    remove_button.onclick = function () { removeIngredient("" + INGREDIENT_COUNT) }
    
    builder_form.appendChild(iName_lbl);
    builder_form.appendChild(iName_input);
    builder_form.appendChild(unit_lbl);
    builder_form.appendChild(unit_input);
    builder_form.appendChild(amount_lbl);
    builder_form.appendChild(amount_input);
    builder_form.appendChild(remove_button);
    
    var spacer = document.createElement("br");
    spacer.id = "spc-" + INGREDIENT_COUNT;
    builder_form.appendChild(spacer);
}

function removeIngredient(s) {   
    if (INGREDIENT_COUNT > 0) {
        var elements = ["amount-" + s, "unit-" + s, "iName-" + s, "i-lbl-" + s, "u-lbl-" + s, "a-lbl-" + s, "rmv-" + s, "spc-" + s];
        
        for (var i = 0; i < elements.length; i++) {
            var temp_node = document.getElementById(elements[i]);
            temp_node.remove();
        }
        
        INGREDIENT_COUNT--;        
    }
}
    
</script>

