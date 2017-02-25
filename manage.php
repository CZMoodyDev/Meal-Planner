<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
session_start();

include_once $front_matter['www'] . '/snippets/mealplanner_fn.php';
include_once $front_matter['www'] . '/snippets/cookbook_interface.php';
?>
<button onClick="addRecipe()">Add recipe to list</button><br><br>
<button onClick="removeRecipe()">Remove recipe from list</button><br><br>


<form method="post" id="convert" action="./grocery_list.php">
Recipe List:<br>
<textarea id="rec-list" name="rec-list"></textarea><br><br>
<input type="submit" value="Create grocery list">
</form>

<script type="text/javascript">
var recipes = '<?php echo $recipe_json; ?>';
var RECIPE_COUNT = 0;
recipes = JSON.parse(recipes);
console.log(recipes);

function displayRecipe(i) {
    console.log(i);
    document.getElementById("rec-name").innerHTML = i;
    document.getElementById("rec-inst").innerHTML = recipes[i]['instructions'];
    
    var ul = document.createElement("ul");
    for (var x = 0; x < recipes[i]['ingredients'].length; x++) {
        var li = document.createElement("li");
        li.appendChild(document.createTextNode(recipes[i]['ingredients'][x]['measure'] + recipes[i]['ingredients'][x]['unit'] + ": " + recipes[i]['ingredients'][x]['iName']));
        ul.appendChild(li);
    }
    document.getElementById("rec-ingr").innerHTML = "";
    document.getElementById("rec-ingr").appendChild(ul);    
}

function addRecipe() {
    var recName = document.getElementById("rec-name").innerHTML;
    if (recName == "NULL") {
        return;
    }
    
    var recipeList = document.getElementById("rec-list").innerHTML;
    if (recipeList == "") {
        document.getElementById("rec-list").innerHTML = recipeList + recName; 
    } else {
        document.getElementById("rec-list").innerHTML = recipeList + "," + recName;        
    }
    
    RECIPE_COUNT++;

}
function removeRecipe() {
    var recName = document.getElementById("rec-name").innerHTML;
    if (recName == "NULL") {
        return;
    }
    
    var recipeList = document.getElementById("rec-list").innerHTML;
    if (RECIPE_COUNT == 1) {
        document.getElementById("rec-list").innerHTML = "";        
    } else {
        document.getElementById("rec-list").innerHTML = recipeList.replace("," + recName, ""); 
    }
    RECIPE_COUNT--;
}
</script>

