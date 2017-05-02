<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
include_once $front_matter['www'] . '/snippets/mealplanner_fn.php';
session_start();

$recipes = getAllRecipes($conn);
$recipe_json = json_encode($recipes);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Frozen Meal Planner</title>
</head>
<body>
<div id="cookbook"> <!-- TODO: EQUAL HEIGHT -->
    <div id="recipe-list">
    <?php if (!empty($recipes)) { ?>
        <ul><?php
        foreach ($recipes as $n => $data) {
            echo '<li><button onClick="displayRecipe(\''.$n.'\')">'.$n.'</button></li>';
        }
        ?></ul>
        <div id="recipe-list-actions">
            <a href="./add-recipe.php" class="button">Add recipe</a>
        </div>
    <?php } else {
        echo '<p>No recipes found.</p>';
    }?>
    </div>
    <div id="recipe-info">
        <p id="recipe-info-canvas">Click on a recipe to view or edit it.</p>
    </div>
</div>

<script type="text/javascript">
var recipes = '<?php echo str_replace("\\r\\n", "<br>", $recipe_json); ?>';
recipes = JSON.parse(recipes);

function displayRecipe(i) {
    var elem = document.getElementById("recipe-info-canvas");
    elem.remove();
    var parent_elem = document.getElementById("recipe-info");
    
    var canvas = document.createElement("table");
    canvas.id = "recipe-info-canvas";
    parent_elem.appendChild(canvas);
    
    var header_row = document.createElement("tr");
    header_row.id = "recipe-name-header";
    var name_header = document.createElement("th");
    name_header.id = "recipe-name";
    canvas.appendChild(header_row);
    header_row.appendChild(name_header);
    name_header.appendChild(document.createTextNode(i));
    
    var info_row = document.createElement("tr");
    info_row.id = "recipe-info-row";
    var info_data = document.createElement("td");
    info_data.id = "recipe-data-holder";
    canvas.appendChild(info_row);
    info_row.appendChild(info_data);
    
    var ingredients_h = document.createElement("h3");
    info_data.appendChild(ingredients_h);
    ingredients_h.appendChild(document.createTextNode("Ingredients"));

    var ingredient_list = document.createElement("ul");
    
    for (var x = 0; x < recipes[i]['ingredients'].length; x++) {
        var li = document.createElement("li");
        li.appendChild(document.createTextNode(recipes[i]['ingredients'][x]['measure'] + recipes[i]['ingredients'][x]['unit'] + ": " + recipes[i]['ingredients'][x]['iName']));
        ingredient_list.appendChild(li);
    }
    info_data.appendChild(ingredient_list);
    
    var instructions_h = document.createElement("h3");
    info_data.appendChild(instructions_h);
    instructions_h.appendChild(document.createTextNode("Instructions"));
    var instruction_formatted = recipes[i]['instructions'].replace(/\*/g, "<br><strong>");
    instruction_formatted = instruction_formatted.replace(/~/g, "</strong><br><i>");
    info_data.innerHTML += instruction_formatted + "</i>";
    
    var indiv_recipe_act = document.createElement("div");
    
    var edit_btn = document.createElement("a");
    edit_btn.href = "./edit-recipe.php?id=" + recipes[i]['rid'];
    edit_btn.class = "button";
    
    var delete_btn = document.createElement("button");
    delete_btn.onclick = function() { deleteAlert(i); }
    delete_btn.class = "button";
    
    canvas.appendChild(indiv_recipe_act);
    indiv_recipe_act.appendChild(edit_btn);
    edit_btn.appendChild(document.createTextNode("Edit recipe"));
    indiv_recipe_act.appendChild(delete_btn);
    delete_btn.appendChild(document.createTextNode("Delete recipe"));
}

function deleteAlert(i) {
    var r = confirm("Delete " + i + " from database?");
    if (r) {
        location.href = "./delete-recipe.php?id=" + recipes[i]['rid'];
    }
}

</script>
</body>
</html>

