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

?>