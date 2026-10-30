document.addEventListener('DOMContentLoaded', () => {
    const PRICES = {
        'vanilla-latte': 145,
        'Pistachio': 165,
        'Biscoff-Latte': 175,
        'Iced-Salted-Caramel-Mocha': 165,
        'Strawberry-Matcha': 175,
        'Iced-Dark-Mocha': 180,
        'Garlic-Bread': 65,
        'Chocolate-Cake': 120,
        'Croissant': 75,
        'Lemon-Cake': 110,
        'Eclair': 95,
        'Scone': 85,
    };
    let cart = loadCartFromStorage();
    function loadCartFromStorage() {
        const storedCart = localStorage.getItem('coffeeiumCart');
        if (storedCart) {
            try {
                return JSON.parse(storedCart);
            } catch (e) {
                console.error("Error parsing cart from storage, starting new cart.", e);
            }
        }
        return {
            items: {}, 
            totalPrice: 0,
            totalCount: 0
        };
    }
    const saveCartToStorage = () => {
        localStorage.setItem('coffeeiumCart', JSON.stringify(cart));
    };
    const alertBox = document.getElementById('alert');
    const itemLinks = document.querySelectorAll('a[data-item-id]');
    const cartCountDisplay = document.getElementById('cart-count');
    const cartPriceDisplay = document.getElementById('cart-price');
    const formatPrice = (price) => {
        return price.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
    };

    const updateCartDisplay = () => {
        cartCountDisplay.textContent = cart.totalCount;
        cartPriceDisplay.textContent = formatPrice(cart.totalPrice);
    };
    updateCartDisplay(); 

    itemLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); 
            
            const itemId = this.getAttribute('data-item-id');
            const itemName = this.querySelector('p').textContent.trim();
            const price = PRICES[itemId];

            if (price) {
                cart.totalCount++;
                cart.totalPrice += price;
                if (!cart.items[itemId]) {
                    cart.items[itemId] = {
                        name: itemName,
                        price: price,
                        quantity: 1
                    };
                } else {
                    cart.items[itemId].quantity++;
                }
                saveCartToStorage();
                updateCartDisplay();
                alertBox.querySelector('p').textContent = `${itemName} added! Total: ${cart.totalCount} items.`;
                alertBox.classList.add('show-alert');
                setTimeout(() => {
                    alertBox.classList.remove('show-alert');
                }, 1500); 

            } else {
                console.error(`Price not found for item ID: ${itemId}`);
            }
        });
    });
});