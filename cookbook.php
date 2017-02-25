<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
session_start();

function check_db($q) {
    global $conn;
    $sql_check = "SELECT name FROM recipes WHERE name='$q'";
    $result = $conn->query($sql_check);
    
    return $result->num_rows;
    
}

if (isset($_POST['arec-name'])) {
    if(check_db($_POST['arec-name']) > 0) {
        echo "RECIPE ALREADY EXISTS";
    } else {
        $sql_add = "INSERT INTO recipes(name, instructions, ingredients) ";
        $sql_add .= "VALUES('".$_POST['arec-name']."', '".$_POST['arec-instructions']."', '".$_POST['arec-ingredients']."')";
        
    if ($conn->query($sql_add) === true) {
        echo "RECIPE ADDED!!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    }
}
include_once $front_matter['www'] . '/snippets/cookbook_interface.php';
?>
<div id="add_recipe">
    <br><p><strong>Add Recipe</strong></p>
    <form method="post" id="add" action="?">
        Recipe name:<br>
        <input type="text" id="arec-name" name="arec-name" value=""><br>
    </form>
    Instructions:<br>
    <textarea id="arec-instructions" name="arec-instructions" form="add"></textarea><br>
    Ingredients:<br>
    <textarea id="arec-ingredients" name="arec-ingredients" form="add"></textarea><br>
    <input type="submit" value="Submit" form="add">
</div>

<script type="text/javascript">
var recipes = '<?php echo $recipe_json; ?>';
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
</script>

