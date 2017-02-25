<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
session_start();

$sql_allrecipes = "SELECT * FROM recipes";
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

$instruction_list = "";
$shopping = "";
$master_shop_list = array();

if (isset($_POST['rec-list'])) {
    echo $_POST['rec-list'] . "<br>";
    
    $recArray = explode(",", $_POST['rec-list']);
    $n = sizeof($recArray);
    $seen_inst = array();
    
    for ($x = 0; $x < $n; $x++) {
        if (!in_array($recArray[$x], $seen_inst)) {
            $instruction_list = $instruction_list . "<h3>" . $recArray[$x] . "</h3>" . $recipes[$recArray[$x]]['instructions'];
        }
        array_push($seen_inst, $recArray[$x]);
        
        foreach ($recipes[$recArray[$x]]['ingredients'] as $index => $data) {

            if (!array_key_exists(strtolower($data['iName']), $master_shop_list)) {
                $master_shop_list[strtolower($data['iName'])] = array();
            }
            if (!array_key_exists($data['unit'], $master_shop_list[strtolower($data['iName'])])) {
                $master_shop_list[strtolower($data['iName'])][$data['unit']] = 0;
            }
            $master_shop_list[strtolower($data['iName'])][$data['unit']] += (float)$data['measure'];
        }
    }
    
    ksort($master_shop_list);
    foreach ($master_shop_list as $name => $data) {
        $ingredient_lines = $name . ": ";
        foreach ($data as $unit => $amount) {
            $ingredient_lines .= (string)$amount . $unit . " ";
        }
        $shopping .= $ingredient_lines . "<br>";
    }
}

?>
<h1>Instruction List:</h1>
<p><?php echo $instruction_list ?></p>
<h1>Shopping List:</h1>
<p><?php echo $shopping ?></p>
