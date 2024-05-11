document.addEventListener('DOMContentLoaded', function() {
    const categoryElements = document.querySelectorAll('.category-name');

    categoryElements.forEach((element, index) => {
        element.addEventListener('click', function() {
            // Remove highlight from all elements
            categoryElements.forEach(el => el.classList.remove('highlight-border'));
            element.classList.add('highlight-border');

            // Fetch products for the selected category
            fetchProducts(element.dataset.categoryId);
        });

        // Automatically trigger a click on the first category to show its products initially
        if (index === 0) {
            element.click();
        }
    });
});

function fetchProducts(categoryId) {
    fetch(`get_products.php?categoryId=${categoryId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => updateProductDisplay(data))
        .catch(error => console.error('Error fetching products:', error));
}

function updateProductDisplay(products) {
    const productList = document.getElementById('product-list');
    productList.innerHTML = '';

    products.forEach(product => {
        const productElement = document.createElement('div');
        productElement.classList.add('col-sm-6', 'col-md-3', 'col-lg-3', 'product-details');
        productElement.innerHTML = `
            <div class="product-content">
                <img src="images/${product.category_id}/${product.img}" alt="${product.title}" class="product-img">
                <div class="text-container">
                    <h4>${product.title}</h4>
                    <h5>${product.thumbnail}</h5>
                    <h3>${product.price}</h3>
                </div>
                <button class="btn">Đặt ngay</button>
            </div>
        `;
        productList.appendChild(productElement);
    });
}
