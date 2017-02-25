<?php
//Checks database for name of recipe
function check_db($q) {
    global $conn;
    $sql_check = "SELECT name FROM recipes WHERE name='$q'";
    $result = $conn->query($sql_check);
    
    return $result->num_rows;
    
}
?>