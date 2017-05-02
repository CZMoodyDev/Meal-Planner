<?php
$front_matter = array(
    'www' => '.'
);

include_once $front_matter['www'] . '/includes/connection.php';
include_once $front_matter['www'] . '/snippets/mealplanner_fn.php';
session_start();

$recipes = getAllRecipes($conn); 

$instruction_list = "";
$shopping = "";
$master_shop_list = array();

if (isset($_GET['recs'])) {   
    $recArray = explode(",", $_GET['recs']);
    $recArray = replaceIDsWithRecipes($recArray, $recipes);
    
        
    $n = sizeof($recArray);
    $seen_inst = array();
    
    for ($x = 0; $x < $n; $x++) {
        if (!in_array($recArray[$x], $seen_inst)) {
            $instruction_list = $instruction_list . "<h3>" . $recArray[$x] . "</h3>";
            $instruction_list .= '<table><tr>';
            $formatted_i = str_replace("*", "<br><strong>", $recipes[$recArray[$x]]['instructions']);
            $formatted_i = str_replace("~", "</strong><br><i>", $formatted_i);
            $formatted_i .= "</i>";
            $instruction_list .= '<td width="50%">' . $formatted_i . '</td>';
            
            $instruction_list .= '<td><ul>';
            foreach ($recipes[$recArray[$x]]['ingredients'] as $index => $data) {
                $itemU = $data['unit'];
                $itemM = $data['measure'];
                $itemN = $data['iName'];
                $instruction_list .= '<li>' . $itemM . $itemU . ' ' . $itemN . '</li>';
            }
            
            $instruction_list .= '</ul></td></tr></table>';
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
        $ingredient_metrics = array();
        foreach ($data as $unit => $amount) {
            array_push($ingredient_metrics, (string)$amount . $unit);
        }
        $ingredient_lines .= implode(" + ", $ingredient_metrics);
        $shopping .= $ingredient_lines . "<br>";
    }
}

?>
<h1>Instruction List:</h1>
<p><?php echo $instruction_list ?></p>
<h1>Shopping List:</h1>
<p><?php echo $shopping ?></p>
