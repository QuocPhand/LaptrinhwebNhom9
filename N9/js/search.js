$(document).ready(function() {
    const searchForm = $('#search-form');

    searchForm.on('submit', function(event) {
        event.preventDefault(); 

        const query = searchForm.find('input[name="query"]').val();

        // Ajax
        $.ajax({
            url: `search_results.php?query=${encodeURIComponent(query)}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                updateSearchResults(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching search results:', textStatus, errorThrown);
            }
        });
    });

    function updateSearchResults(products) {
        const searchResults = $('#search-results');
        searchResults.empty(); 

        if (products.length > 0) {
            products.forEach(product => {
                const productElement = $(`
                    <div class="col-sm-6 col-md-3 col-lg-3 product-details">
                        <div class="product-content">
                            <img src="${product.img}" alt="${product.title}" class="product-img">
                            <div class="text-container">
                                <h4>${product.title}</h4>
                                <h3>${product.price}</h3>
                            </div>
                        </div>
                    </div>
                `);
                searchResults.append(productElement);
            });
        } else {
            searchResults.html(`
                <div class="no-search">
                    <img src="images/Not found.jpg" alt="Không có món bạn tìm kiếm" class="no-search-img">
                    <h2>Chúng tôi không có món bạn tìm kiếm!</h2>
                </div>
            `);
        }
    }
});
