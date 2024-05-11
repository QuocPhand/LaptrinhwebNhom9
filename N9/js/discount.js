document.addEventListener('DOMContentLoaded', function() {
    function updateProductDisplay(products) {
        const productList = $('#product-list');
        productList.empty(); 

        products.forEach(product => {
            const productElement = $(`
                <div class="col-sm-6 col-md-4 col-lg-3 product-details">
                    <div class="product-content">
                        <img src="${product.img}" alt="${product.title}" class="product-img">
                        <div class="text-container">
                            <h4>${product.title}</h4>
                            <h2 class="discounted-price">${product.discountedPrice}</h2>
                            <h3 class="original-price">${product.originalPrice}</h3>
                        </div>
                    </div>
                </div>
            `);

            // Append the new product element to the product list
            productList.append(productElement);
        });
    }

    // Make an AJAX request to fetch discounted products
    $.ajax({
        url: 'discount_results.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            updateProductDisplay(data);
        },
        error: function() {
            console.error('Failed to fetch discounted products.');
        }
    });
});
