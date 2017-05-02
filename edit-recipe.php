<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
include_once $front_matter['www'] . '/snippets/mealplanner_fn.php';
session_start();

$recipe = array();
$alert = "";
$ingredient_count = 0;

if (isset($_GET['id'])) {
    $recipe = selectRecipeById($_GET['id']);
} else {
    $alert = "No id in parameters";
}

if (isset($_POST['submit-btn'])) {
    unset($_POST['submit-btn']);
    
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
    
    editRecipe($recName, $ingredients, $preprep, $cooking, $serving);
    $alert = "Recipe modified";
    $recipe = array();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Frozen Meal Planner</title>
</head>
<body>
<?php if (!empty($recipe)) { 
    $rName = $recipe['name'];
    $rIngred = json_decode($recipe['ingredients'], true);
    $ingredient_count = count($rIngred);
    $rInstruct_T = explode("*", $recipe['instructions']);
    $preprep = $rInstruct_T[0];
    $rInstruct_T = explode("~", $rInstruct_T[1]);
    $cook = $rInstruct_T[0];
    $serve = $rInstruct_T[1];
?>
    <div id="recipe-builder">
        <h3>Editing <?php echo $rName; ?></h3>
        <form id="builder-form" action="" method="post">
        <input type="hidden" name="rec-name" value="<?php echo $rName; ?>">
<?php 
    foreach ($rIngred as $i => $data) {
        $x = $i + 1;
        echo '<label for="iName-'.$x.'" id="i-lbl-'.$x.'">Ingredient Name:</label>';
        echo '<input id="iName-'.$x.'" name="iName-'.$x.'" type="text" value="'.$data['iName'].'">';
        echo '<label for="unit-'.$x.'" id="u-lbl-'.$x.'">Units:</label>';
        echo '<input name="unit-'.$x.'" id="unit-'.$x.'" type="text" value="'.$data['unit'].'">';
        echo '<label for="amount-'.$x.'" id="a-lbl-'.$x.'">Amount:</label>';
        echo '<input name="amount-'.$x.'" id="amount-'.$x.'" type="text" value="'.$data['measure'].'">';
        echo '<button id="rmv-'.$x.'" type="button" class="rmv-ingred" onClick="removeIngredient(\''.$x.'\')">Remove ingredient</button><br id="spc-'.$x.'">';
    }
?>
        </form>
        <button type="button" onclick="addIngredient()">Add ingredient</button><br>
        <label for="preprep">Prepreparation Instructions:</label>
        <textarea name="preprep" rows="4" cols="50" form="builder-form"><?php echo $preprep; ?></textarea><br>
        <label for="cooking">Cooking Instructions:</label>
        <textarea name="cooking" rows="4" cols="50" form="builder-form"><?php echo $cook; ?></textarea><br>
        <label for="serving">Serving Instructions:</label>
        <textarea name="serving" rows="4" cols="50" form="builder-form"><?php echo $serve; ?></textarea><br>
        <input type="submit" value="Save" name="submit-btn" form="builder-form">
    </div>
<?php } else { 
    if ($alert == "") {
        $alert = "No recipe found in database.";
    }
    echo $alert; //TODO: Alert when CSS Added
?>
    <div id="nav-btns">
        <ul>
            <li><a href="./cookbook.php">Back to cookbook</a></li>
        </ul>
    </div>
<?php } ?>
</body>
</html>
<script type="text/javascript">
var INGREDIENT_COUNT = <?php echo $ingredient_count ?>;
    
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