$(document).ready(function() {

    // Handle "Add to Cart" button click
    $(".add-to-cart").click(function() {
        var productId = $(this).closest(".product").data("id");

        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: { product_id: productId },
            success: function(response) {
                alert((response));
            },
            error: function() {
                alert("An error occurred while adding the product to the cart.");
            }
        });
    });

    // Handle "Buy Now" button click
    $(".buy-now").click(function() {
        var productId = $(this).closest(".product").data("id");

        $.ajax({
            url: 'buy_direct.php',
            method: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    window.location.href = response.redirect;
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert("An error occurred while processing your purchase: " + error);
            }
        });
    });

    // Handle updating cart quantities
    // $(".update-cart").click(function() {
    //     var cartId = $(this).data("cart-id");
    //     var quantity = $(this).siblings(".quantity").val();

    //     $.ajax({
    //         url: 'update_cart.php',
    //         method: 'POST',
    //         data: { cart_id: cartId, quantity: quantity },
    //         success: function(response) {
    //             alert("Cart updated successfully!");
    //             location.reload(); // Refresh the page to reflect changes
    //         },
    //         error: function() {
    //             alert("An error occurred while updating the cart.");
    //         }
    //     });
    // });

    
});









