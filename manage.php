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
<div id="list-maker"> <!-- TODO: EQUAL HEIGHT -->
    <div id="recipe-list">
    <?php if (!empty($recipes)) { ?>
        <ul><?php
        foreach ($recipes as $n => $data) {
            echo '<li><button onClick="addToList(\''.$n.'\')">'.$n.'</button></li>';
        }
        ?></ul>
    <?php } else {
        echo '<p>No recipes found.</p>';
    }?>
    </div>
    <div id="list-panel">
        <div id="shopping-list">
            <p id="shopping-list-canvas">Click on a recipe to add it to the list.</p>
        </div>
        <div id="list-btns">
            <button type="button" onclick="buildList()">Build List</button>
            <button type="button" onclick="saveList()">Save List</button>
            <button type="button" onclick="loadList()">Load List</button>
        </div>
    </div>
</div>

<script type="text/javascript">
var recipes = '<?php echo str_replace("\\r\\n", "<br>", $recipe_json); ?>';
recipes = JSON.parse(recipes);

var RID_LIST = [];
var RID_TRACKER = {};

function addToList(i) {
    var elem = document.getElementById("shopping-list-canvas");
    if (elem != null) {
        elem.remove();       
    }

    var sList = document.getElementById("shopping-list");    
    if (RID_LIST.length == 0) {
        sList.innerHTML = "";
        var ul = document.createElement("ul");
        ul.id = "list-manager";
        sList.appendChild(ul);
    }

    if (RID_TRACKER[recipes[i]['rid'].toString()] != undefined) {
        var recipeLine = document.getElementById(i.replace(/ /g, "-"));
        recipeLine.innerHTML = (RID_TRACKER[recipes[i]['rid'].toString()] + 1).toString() + "x ";
        recipeLine.innerHTML += i;
    } else {
        RID_TRACKER[recipes[i]['rid'].toString()] = 0;
        var li = document.createElement("li");
        li.id = i.replace(/ /g, "-");
        li.onclick = function () {deleteFromList(i);}
        li.innerHTML = (RID_TRACKER[recipes[i]['rid'].toString()] + 1).toString() + "x ";
        li.innerHTML += i;
        
        document.getElementById("list-manager").appendChild(li);
    }
    
    RID_TRACKER[recipes[i]['rid'].toString()]++;
    RID_LIST.push(recipes[i]['rid']);    
}

function deleteFromList(i) {
    if (RID_TRACKER[recipes[i]['rid'].toString()] == 1) {
        delete RID_TRACKER[recipes[i]['rid'].toString()];
        var rLine = document.getElementById(i.replace(/ /g, "-"));
        rLine.remove();
    } else {
        var rLine = document.getElementById(i.replace(/ /g, "-"));
        rLine.innerHTML = (RID_TRACKER[recipes[i]['rid'].toString()] - 1).toString() + "x ";
        rLine.innerHTML += i;
        RID_TRACKER[recipes[i]['rid'].toString()]--;
    }
    
    var index = RID_LIST.indexOf(recipes[i]['rid']);
    if (index > -1) {
        RID_LIST.splice(index, 1);
    }
}

function buildList() {
    
    var str = RID_LIST.join(",");
    location.href = "./grocery_list.php?recs=" + str;
    
}

</script>
</body>
</html>

