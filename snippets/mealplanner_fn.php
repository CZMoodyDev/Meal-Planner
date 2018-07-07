<?php
//Checks database for name of recipe
function check_db($q) {
    global $conn;
    $sql_check = "SELECT name FROM recipes WHERE name='$q'";
    $result = $conn->query($sql_check);
    
    return $result->num_rows;
    
}

//Places all recipes from database into a dictionary
function getAllRecipes($conn) {
    $sql_allrecipes = "SELECT * FROM recipes ORDER BY name";
    $recipes = array();    
    $result = $conn->query($sql_allrecipes);

    if ($result != false && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recipes[$row['name']] = array();
            $recipes[$row['name']]['instructions'] = $row['instructions'];
            $recipes[$row['name']]['ingredients'] = json_decode($row['ingredients'], true);
            $recipes[$row['name']]['rid'] = $row['rid'];
        }
    }

    return($recipes);
}

//Grabs one recipe from database into a dictionary
function selectRecipeById($id) {
    global $conn;
    $sql_recipe = "SELECT * FROM recipes WHERE rid='$id'";
    $result = $conn->query($sql_recipe);
    
    if ($result != false && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return array();
    }
}

//Adds a recipe to the database
function addRecipe($name, $ingredients, $pre, $cook, $serve) {
    global $conn;
    $sql_addrecipe = "INSERT INTO recipes (name, instructions, ingredients) ";
    $sql_addrecipe .= "VALUES ('".$name."', '".$pre.$cook.$serve."', '".$ingredients."')";
    
    if ($conn->query($sql_addrecipe) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql_addrecipe . "<br>" . $conn->error;
        return false;
    }
}

//Edits a recipe in the database
function editRecipe($name, $ingredients, $pre, $cook, $serve) {
    global $conn;
    $sql_editrecipe = "UPDATE recipes ";
    $sql_editrecipe .= "SET ingredients='$ingredients', instructions='".$pre.$cook.$serve."' ";
    $sql_editrecipe .= "WHERE name='$name'";
    
    if ($conn->query($sql_editrecipe) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql_editrecipe . "<br>" . $conn->error;
        return false;
    }
}

//Delete a recipe from the database
function deleteRecipe($i) {
    global $conn;
    $sql_del = "DELETE FROM recipes WHERE rid='$i'";
    
    $result = $conn->query($sql_del);
}

//Change an array keyed on ids to one keyed on names
function replaceIDsWithRecipes($a, $r) {
    $idArr = array();
    
    foreach ($r as $n => $data) {
        $idArr[(string)$data['rid']] = $n;
    }
    
    foreach ($a as $i=>$v) {
        $a[$i] = $idArr[(string)$v];
    }
    
    return $a; 
}

function createSection($r, $all_r) {
    $ingredients = $all_r[$r]['ingredients'];
    $instructions = $all_r[$r]['instructions'];
    $id = $all_r[$r]['rid'];
    
    $button_html = '<a href="#rec'.$id.'" class="btn btn-info rec-sec center-block" data-toggle="collapse">'.$r.'</a>';
    
    $section_html = '<div id="rec'.$id.'" class="collapse">';
    
    $ingredients_html = '<h3 class="rec-header">Ingredients</h3>';
    $ingredients_html .= "<ul>";
    
    for ($x = 0; $x < count($ingredients); $x++) {
        $html_li = "<li>";
        $html_li .= $ingredients[$x]['measure'] . $ingredients[$x]['unit'] . ": " . $ingredients[$x]['iName'];
        $html_li .= "</li>";
        $ingredients_html .= $html_li;
    }
    
    $ingredients_html .= '</ul>';
    
    $instructions_html = '<h3 class="rec-header">Instructions</h3>';
    
    $instruction_arr = explode("*", $instructions);
    $instruction_temp = explode("~", $instruction_arr[1]);
    
    $inst_prep = $instruction_arr[0];
    $inst_cook = $instruction_temp[0];
    $inst_serve = $instruction_temp[1];
        
    if (strlen($inst_prep) > 0) {
        $instructions_html .= '<p class="prep">'.$inst_prep.'</p>';
    }
    
    if (strlen($inst_cook) > 0) {
        $instructions_html .= '<p class="prep">'.$inst_cook.'</p>';
    }
    
    if (strlen($inst_serve) > 0) {
        $instructions_html .= '<p class="serve">'.$inst_serve.'</p>';
    }
    
    $edit_btn = '<a class="btn btn-primary" href="./edit-recipe.php?id='.$id.'">Edit recipe</a>';
    $del_btn = '<button class="btn btn-danger" role="button" onclick="deleteAlert(\''.$r.'\')">Delete recipe</button>';
    
    
    
    $section_html .= $ingredients_html . $instructions_html . $edit_btn . $del_btn;
    $section_html .= '</div>';
    
    return $button_html . $section_html;
}

?>