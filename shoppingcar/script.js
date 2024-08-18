$(document).ready(function() {

    // Handle "Add to Cart" button click
    $(".add-to-cart").click(function() {
        var productId = $(this).closest(".product").data("id");

        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: { product_id: productId },
            success: function(response) {
                alert(JSON.stringify(response));
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
    $(".update-cart").click(function() {
        var cartId = $(this).data("cart-id");
        var quantity = $(this).siblings(".quantity").val();

        $.ajax({
            url: 'update_cart.php',
            method: 'POST',
            data: { cart_id: cartId, quantity: quantity },
            success: function(response) {
                alert("Cart updated successfully!");
                location.reload(); // Refresh the page to reflect changes
            },
            error: function() {
                alert("An error occurred while updating the cart.");
            }
        });
    });

    
});



$(document).ready(function() {
    $('#registration-form').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Create a FormData object from the form

        $.ajax({
            url: 'register.php', // PHP script to handle registration
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = JSON.parse(response); // Parse the JSON response
                if (result.status === 'success') {
                    alert('Registration successful!');
                    window.location.href = 'login.php'; // Redirect to login page
                } else {
                    alert('Error: ' + result.message); // Show error message
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});


