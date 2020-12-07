$(function () {
    // removes alerts of success
    setTimeout(function() {
      $(".alert-success").hide('blind', {}, 500)
    }, 5000);

    // removes alerts of error
    setTimeout(function() {
      $(".alert-error").hide('blind', {}, 500)
    }, 5000);

    // jq for multiple select or deselect products
    $('input[name=selectAllpro]').on('change', function() {
        $('input[name="Selected_products[]"]').prop('checked', this.checked);
    });

    // jq for multiple select or deselect categories
    $('input[name=selectAllCat]').on('change', function() {
        $('input[name="catSelect[]"]').prop('checked', this.checked);
    });


    // jq validation for add category form
    $('#addCategoryForm').validate({
        rules: {
            "category_name": {required:true},
            "category_image": {required:true},
        },
        messages: { 
            "category_name": {required: "*Please enter category name"},
            "category_image": {required: "*Please enter category image"}
        } 
    });

    // jq validation for edit category form
    $('#addCategoryForm').validate({
        rules: {
            "category_name": {required:true}
        },
        messages: { 
            "category_name": {required: "*Please enter category name"}
        } 
    });

    // jq validation for add product form
    $('#addProductForm').validate({
        rules: {
            "product_name": {required:true},
            "product_image": {required:true},
            "categories_id": {required:true},
            "product_description": {required:true}
        },
        messages: { 
            "product_name": {required: "*Please enter product name"},
            "product_image": {required: "*Please enter product image"},
            "categories_id": {required: "*Please select product category"},
            "product_description": {required: "*Please enter product description"}
        } 
    });

    // jq validation for edit product form
    $('#editProductForm').validate({
        rules: {
            "product_name": {required:true},
            "categories_id": {required:true},
            "product_description": {required:true}
        },
        messages: { 
            "product_name": {required: "*Please enter product name"},
            "categories_id": {required: "*Please select product category"},
            "product_description": {required: "*Please enter product description"}
        } 
    });

});

// js function to load add categories form
function addCategory(){
    location.href="Categories/add";
}

// js function to load add products form
function addProduct(){
    location.href="Products/add";
}