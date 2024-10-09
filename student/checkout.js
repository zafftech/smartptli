document.addEventListener('DOMContentLoaded', function () {
    let listCart = [];

    // Define subject options for SK and SMK categories
    const subjectOptionsPRIMARY = ['Bahasa Melayu', 'English', 'Mathematics', 'Science', 'History'];
    const subjectOptionsSECONDARY = ['Mathematics', 'Biology', 'Chemistry', 'Physics', 'Add Math', 'English'];
    const subjectOptionsIQRA = ['Iqra'];
    const subjectOptionsUPKK = ['Arabic Language','Akhlak','Aqidah','Sirah','Ibadah','Jawi & Khat'];

    // Get data from localStorage
    function checkCart() {
        const cartData = localStorage.getItem('cartData');
        if (cartData) {
            listCart = JSON.parse(cartData);
            console.log('Cart items loaded:', listCart); // Log loaded cart items
        }
    }

    checkCart();
    addCartToHTML();

    //JANGAN KACAUU
    // Function to calculate max subject selection per package
    function getMaxSelectionForPackage(product) {
        if (product.name.includes('1 SUBJECT')) {
            return product.quantity; // 1 subject per package
        } else if (product.name.includes('2 SUBJECT')) {
            return product.quantity * 2; // 2 subjects per package
        } else if (product.name.includes('4 SUBJECT')) {
            return product.quantity * 4; // 4 subjects per package
        }
        return product.quantity; // Default fallback
    }

    // Function to generate subject buttons dynamically based on product category and selection limit
    function generateSubjectButtons(product) {
        let subjectOptions = [];

        // Check if product.category exists and log the product for debugging
        console.log("Product data:", product);
        if (!product.category) {
            console.error(`Product with ID ${product.id} is missing a category. Check product data.`);
            return ''; // Return early if category is missing to prevent errors
        }

        // Ensure case-insensitive comparison for the category
        const productCategory = product.category.toUpperCase();

        if (productCategory === 'PRIMARY') {
            subjectOptions = subjectOptionsPRIMARY;
            console.log("Product is PRIMARY:", product.name); // Debugging log
        } else if (productCategory === 'SECONDARY') {
            subjectOptions = subjectOptionsSECONDARY;
            console.log("Product is SECONDARY:", product.name); // Debugging log
        } else if (productCategory === 'IQRA') {
            subjectOptions = subjectOptionsIQRA;
            console.log("Product is IQRA:", product.name); // Debugging log
        } else if (productCategory === 'UPKK') {
            subjectOptions = subjectOptionsUPKK;
            console.log("Product is UPKK:", product.name); // Debugging log
        } else {
            console.error('Unknown category for product:', product.name);
            return ''; // Return empty string if category is not recognized
        }
        
        let maxSelection = getMaxSelectionForPackage(product); // Get max subjects based on package
        console.log('Max selection for', product.name, 'is', maxSelection); // Debugging log

        let buttons = `<div class="subject-container">`; // Create a container for buttons with grid layout

        // Generate buttons for each subject in the selected category
        subjectOptions.forEach((subject) => {
            buttons += `<button class="subject-btn" data-product-id="${product.id}" data-subject="${subject}" data-max="${maxSelection}">
                            ${subject}
                        </button>`;
        });

        buttons += `</div>`; // Close the subject container div

        console.log('Generated buttons:', buttons); // Debugging log
        return buttons;
    }

    // Function to render cart data into HTML
    function addCartToHTML() {
        let listCartHTML = document.querySelector('.returnCart .list');
        listCartHTML.innerHTML = ''; // Clear cart data
        let totalQuantityHTML = document.querySelector('.totalQuantity');
        let totalPriceHTML = document.querySelector('.totalPrice');

        let totalQuantity = 0;
        let totalPrice = 0;

        listCart.forEach(product => {
            if (product) {
                let newP = document.createElement('div');
                newP.classList.add('item');
                let maxSelection = getMaxSelectionForPackage(product);

                newP.innerHTML = 
                `<img src="${product.image}" alt="${product.name}">
                <div class="info">
                    <div class="name">${product.name}</div>
                    <div class="price">RM${product.price}</div>
                </div>
                <div class="quantity">${product.quantity}</div>
                <div class="returnPrice">RM${(product.price * product.quantity).toFixed(2)}</div>
                <div class="button-group" id="button-group-${product.id}">
                    <label style="white-space: nowrap;">Choose ${maxSelection} subject:</label>
                    ${generateSubjectButtons(product)}
                </div>`;
            

                listCartHTML.appendChild(newP);
                totalQuantity += product.quantity;
                totalPrice += product.price * product.quantity;
            }
        });

        totalQuantityHTML.innerText = totalQuantity;
        totalPriceHTML.innerText = 'RM' + totalPrice.toFixed(2);

        // Attach event listeners for the subject buttons
        attachSubjectButtonListeners();
    }

    // Function to attach event listeners to subject buttons
    function attachSubjectButtonListeners() {
        document.querySelectorAll('.subject-btn').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                const maxSelection = parseInt(this.getAttribute('data-max'));
                toggleSubjectSelection(this, productId, maxSelection);
            });
        });
    }

    // Function to toggle subject selection and enforce max selection rules
    function toggleSubjectSelection(button, productId, maxSelection) {
        const selectedButtons = document.querySelectorAll(`#button-group-${productId} .subject-btn.selected`);

        if (!button.classList.contains('selected') && selectedButtons.length >= maxSelection) {
            alert(`You can choose ${maxSelection} subject(s) for this package.`);
            return;
        }

        button.classList.toggle('selected');

        const selectedSubjects = [...document.querySelectorAll(`#button-group-${productId} .subject-btn.selected`)].map(btn => btn.getAttribute('data-subject'));
        console.log(`Product ID: ${productId} Chosen Subject(s): ${selectedSubjects}`);
    }

    // Handle form submission with FormData
    const checkoutForm = document.getElementById('checkoutForm');
    checkoutForm.addEventListener('submit', function (event) {
        event.preventDefault();

        // Create a new FormData object
        const formData = new FormData();
        formData.append('name', document.getElementById('name').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('receipt', document.getElementById('receipt').files[0]); // File to upload

        // Add cart items to the form data
        listCart.forEach(product => {
            if (product && product.id) {
                const buttonGroup = document.getElementById(`button-group-${product.id}`);
                if (buttonGroup) {
                    const selectedSubjects = Array.from(buttonGroup.querySelectorAll('.subject-btn.selected'))
                        .map(button => button.getAttribute('data-subject'));

                    formData.append('cartItems[]', JSON.stringify({
                        productName: product.name,
                        price: product.price,
                        quantity: product.quantity,
                        totalPrice: product.price * product.quantity,
                        selectedSubjects: selectedSubjects
                    }));
                } else {
                    console.warn(`Button group for product ID ${product.id} does not exist.`);
                }
            } else {
                console.warn('Product with missing id found:', product);
            }
        });

        // Send formData using fetch
        fetch('checkout.php', {
            method: 'POST',
            body: formData, // Send FormData directly
        })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    alert('Checkout successful!');
                } else {
                    alert('Checkout failed, please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            });
    });
});
