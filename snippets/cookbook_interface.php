<?php
$sql_allrecipes = "SELECT * FROM recipes";
$recipes = array();    
$result = $conn->query($sql_allrecipes);

if ($result != false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipes[$row['name']] = array();
        $recipes[$row['name']]['instructions'] = $row['instructions'];
        $recipes[$row['name']]['ingredients'] = json_decode($row['ingredients']);
        $recipes[$row['name']]['rid'] = $row['rid'];
    }
}

ksort($recipes);
//Build Cookbook List ?>

<ul>
<?php
$recipe_json = json_encode($recipes);
foreach ($recipes as $n => $data) {
    echo '<li><button onClick="displayRecipe(\''.$n.'\')">'.$n.'</button></li>';
}
?>
</ul>
<div id="currentRecipe">
    <p>Click on a recipe to display it</p>
    <table style="width:65%" border="1">
        <tr>
            <th>Name</th>
            <th>Instructions</th>
            <th>Ingredients</th>
        </tr>
        <tr>
            <td id="rec-name">NULL</td>
            <td id="rec-inst">NULL</td>
            <td id="rec-ingr">NULL</td>
        </tr>
    </table>
</div>