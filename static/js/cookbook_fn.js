var INGREDIENT_COUNT = 1;
    
function addIngredient() {
    INGREDIENT_COUNT++;

    var formGroup = '<div class="form-group">';
    var formEnd = '</div>';
    
    var htmlIngLabel = '<label for="iName-'+INGREDIENT_COUNT+'" id="i-lbl-'+INGREDIENT_COUNT+'">Ingredient Name:</label>';
    var htmlIngInput = '<input id="iName-'+INGREDIENT_COUNT+'" name="iName-'+INGREDIENT_COUNT+'" type="text"  class="form-control">';
    var htmlIngredient = formGroup + htmlIngLabel + htmlIngInput + formEnd;
    
    var htmlUnitLabel = '<label for="unit-'+INGREDIENT_COUNT+'" id="u-lbl-'+INGREDIENT_COUNT+'">Units:</label>';
    var htmlUnitInput = '<input name="unit-'+INGREDIENT_COUNT+'" id="unit-'+INGREDIENT_COUNT+'" type="text"  class="form-control">';
    var htmlUnit = formGroup + htmlUnitLabel + htmlUnitInput + formEnd;
    
    var htmlAmountLabel = '<label for="amount-'+INGREDIENT_COUNT+'" id="a-lbl-'+INGREDIENT_COUNT+'">Amount:</label>';
    var htmlAmountInput = '<input name="amount-'+INGREDIENT_COUNT+'" id="amount-'+INGREDIENT_COUNT+'" type="text" class="form-control">';
    var htmlAmount = formGroup + htmlAmountLabel + htmlAmountInput + formEnd;
    
    var htmlBtn = '<button id="rmv-'+INGREDIENT_COUNT+'" type="button" class="rmv-ingred btn btn-danger" onClick="removeIngredient(\''+INGREDIENT_COUNT+'\')">Remove ingredient</button>';
    htmlBtn = formGroup + htmlBtn + formEnd;
    
    var fullIngredient = '<div class="indiv-ingred">' + htmlIngredient + htmlUnit + htmlAmount + htmlBtn + '</div>';
    
    $("#builder-form").append(fullIngredient);
}

function removeIngredient(s) {   
    if (INGREDIENT_COUNT > 0) {
        var elements = ["amount-" + s, "unit-" + s, "iName-" + s, "i-lbl-" + s, "u-lbl-" + s, "a-lbl-" + s, "rmv-" + s, "spc-" + s];
        
        for (var i = 0; i < elements.length; i++) {
            $("#"+elements[i]).remove();
        }
        
        INGREDIENT_COUNT--;        
    }
}

function deleteAlert(i) {
    var r = confirm("Delete " + i + " from database?");
    if (r) {
        location.href = "./delete-recipe.php?id=" + recipes[i]['rid'];
    }
}
