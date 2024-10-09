document.addEventListener('DOMContentLoaded', function () {
    let iconCart = document.querySelector('.iconCart');
    let cart = document.querySelector('.cart');
    let container = document.querySelector('.container');
    let close = document.querySelector('.close');

    // Set initial state of cart to be closed when the page loads
    cart.style.right = '-400px';  // Ensure cart starts hidden off-screen
    container.style.transform = 'translateX(0)';  // Ensure container starts in its original position

    // Handle cart open/close functionality
    iconCart.addEventListener('click', () => {
        if (cart.style.right === '-400px') {  // Initially off-screen
            cart.style.right = '0';  // Slide in
        } else {
            cart.style.right = '-400px';  // Slide out
        }
    });

    // Close the cart and reset the layout
    close.addEventListener('click', () => {
        cart.style.right = '-400px';  // Slide out
        container.style.transform = 'translateX(0)';  // Reset content position
    });

    let products = null;

    // Fetch data from the JSON file
    fetch('product.json')  // Ensure the path to product.json is correct
        .then(response => response.json())
        .then(data => {
            products = data;
            console.log(products);  // Debugging log to verify product data
            addDataToHTML();
        })
        .catch(error => console.error('Error fetching products:', error));

    // Add the fetched data to the HTML product list
    function addDataToHTML() {
        let listProductHTML = document.querySelector('.listProduct');
        listProductHTML.innerHTML = '';  // Clear existing content

        if (products != null) {
            products.forEach(product => {
                console.log(product);  // Debugging log to verify product data
                let newProduct = document.createElement('div');
                newProduct.classList.add('item');
                newProduct.innerHTML = `
                    <img src="${product.image}">
                    <h2>${product.name}</h2>
                    <div class="price">RM${product.price}</div>
                    <button class="add-to-cart-btn" data-id="${product.id}">Add To Cart</button>`;
                listProductHTML.appendChild(newProduct);
            });

            // Attach event listeners to "Add to Cart" buttons
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-id'));
                    addCart(productId);
                });
            });
        }
    }

    let listCart = [];

    // Check if cart exists in cookies
    function checkCart() {
        var cookieValue = document.cookie
            .split('; ')
            .find(row => row.startsWith('listCart='));
        if (cookieValue) {
            listCart = JSON.parse(cookieValue.split('=')[1]);
        }
    }

    checkCart();

    // Add product to cart and save in cookies
    function addCart(idProduct) {
        let productCopy = JSON.parse(JSON.stringify(products));  // Deep copy of products

        // If product is not already in the cart
        if (!listCart[idProduct]) {
            let dataProduct = productCopy.filter(
                product => product.id == idProduct
            )[0];

            // Add product to cart with initial quantity
            listCart[idProduct] = dataProduct;
            listCart[idProduct].quantity = 1;
        } else {
            // Increase quantity if product already exists in cart
            listCart[idProduct].quantity++;
        }

        // Save cart to cookies
        let timeSave = "expires=Thu,31 Dec 2025 23:59:59";
        document.cookie = "listCart=" + JSON.stringify(listCart) + ";" + timeSave + ";path=/;";

        addCartToHTML();
    }

    // Update cart display in HTML
    function addCartToHTML() {
        let listCartHTML = document.querySelector('.listCart');
        listCartHTML.innerHTML = '';  // Clear cart display

        let totalHTML = document.querySelector('.totalQuantity');
        let totalQuantity = 0;

        if (listCart) {
            listCart.forEach((product, index) => {
                if (product) {
                    let newCart = document.createElement('div');
                    newCart.classList.add('item');
                    newCart.innerHTML = `
                        <img src="${product.image}">
                        <div class="content">
                            <div class="name">
                            ${product.name}
                            </div>
                            <div class="price">
                                RM${product.price}/1 product
                            </div>
                            <div class="quantity">
                                <button class="quantity-minus" data-id="${index}">-</button>
                                <span class="value">${product.quantity}</span>
                                <button class="quantity-plus" data-id="${index}">+</button>
                            </div>
                        </div>`;
                    listCartHTML.appendChild(newCart);
                    totalQuantity += product.quantity;
                }
            });

            // Attach event listeners for quantity controls
            document.querySelectorAll('.quantity-minus').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-id'));
                    changeQuantity(productId, '-');
                });
            });

            document.querySelectorAll('.quantity-plus').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-id'));
                    changeQuantity(productId, '+');
                });
            });

            // Add event listener for checkout button
            document.querySelector('.checkout').addEventListener('click', () => {
                // Save cart data to localStorage and redirect to checkout page
                localStorage.setItem('cartData', JSON.stringify(listCart));
                window.location.href = 'checkout.html';
            });
        }

        totalHTML.innerText = totalQuantity;
    }

    // Change product quantity in cart
    function changeQuantity(productId, type) {
        if (listCart[productId]) {
            if (type === '+') {
                listCart[productId].quantity++;
            } else if (type === '-') {
                listCart[productId].quantity--;
                if (listCart[productId].quantity === 0) {
                    delete listCart[productId];  // Remove product if quantity is 0
                }
            }

            // Save updated cart to cookies
            saveCart();
            addCartToHTML();  // Refresh cart display
        }
    }

    // Save cart to cookies
    function saveCart() {
        let timeSave = "expires=Thu, 31 Dec 2025 23:59:59";
        document.cookie = "listCart=" + JSON.stringify(listCart) + ";" + timeSave + ";path=/;";
    }

    // Initial call to render cart data
    addCartToHTML();
});
