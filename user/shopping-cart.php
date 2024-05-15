<?php
include('../server/connection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../style/shopping-cart.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <h2>KGNEXUS</h2>
            <div class="search-box">
                <i class='bx bx-search' id="search-icon"></i>
                <input type="search" placeholder="Search">
            </div>
            <ul class="links">
                <li>
                    <a href="#" id="Home">Your Store<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a>
                    <ul class="dropyourstore" id="yourstoreclick">
                        <li><a href="#">Store</a></li>
                        <li><a href="#">Library</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#">Category<i class="fa fa-angle-down" id="dropdown" aria-hidden="true"></i></a>
                    <ul class="genres" id="genres">
                        <li><a href="">Action</a></li>
                        <li><a href="">Adventure</a></li>
                        <li><a href="">Role-playing</a></li>
                        <li><a href="">Simulation</a></li>
                        <li><a href="">Strategy</a></li>
                        <li><a href="">Sports & Racing</a></li>
                    </ul>

                </li>
                <li><a href="#">Wishlist<i class="" id="dropdown" aria-hidden="true"></i></a></li>
                <li><a href="#">Cart<i class="" id="dropdown" aria-hidden="true"></i></a></li>
            </ul>
            <i class='bx bxs-user-circle' id="user"></i>
            <div class="sub-menu-wrap" id="sub-menu-wrap">
                <a href="profile-user.php">Manage Account</a>
                <a href="sign-up.php">Logout</a>
            </div>
        </nav>

    </header>

    <!-- End of navigation bar -->

    <!-- Javascript Dropdown -->
    <script>
        let yourStore = document.getElementById('yourstoreclick');
        let genres = document.getElementById('genres');
        let special = document.getElementById('special')

        document.getElementById('user').addEventListener('click', function() {
            document.getElementById('sub-menu-wrap').classList.toggle('sub-menu-show');
        });

        user.addEventListener('click', () => {
            dropUser.classList.toggle('user-details-show');
        });

        yourStore.previousElementSibling.addEventListener('click', () => {
            yourStore.classList.toggle('dropyourstore-show');
        });

        genres.previousElementSibling.addEventListener('click', () => {
            genres.classList.toggle('genres-show');
        });

        special.previousElementSibling.addEventListener('click', () => {
            genres.classList.toggle('special-show');
        });
    </script>
    <!-- End of javascript dropdown -->

    

    <!-- main content -->

    <!-- Hidden template for cart item -->
    <template id="cart-item-template">
        <div class="order-item">
            <img src="" alt="Game Image">
            <div class="item-details">
                <p class="item-name"></p>
                <a href="#" class="remove-item">Remove</a>
            </div>
            <div class="item-price"></div>
        </div>
    </template>

    <div class="cart-wrapper">
        <div class="cart">
            <h2>YOUR ORDER</h2>
            <div id="cart-items-container"></div>
            <p>ORDER TOTAL: <span id="order-total">Rp.0</span></p>
        </div>
        <div class="payment-option">
            <h3>YOUR PAYMENT & GIFTING DETAILS</h3>
            <br>
            <div class="payment-method">
                <label>
                    <input type="radio" name="payment" value="wallet">
                    <span class="payment-description">
                        <p>USE WALLET FUNDS (BALANCE Rp. 100.000)</p>
                    </span>
                </label>
            </div>
            <div class="order-total">
                <h5>TOTAL: </h5>
                <p id="payment-total">Rp.0</p>
                <button>PAY FOR YOUR ORDER NOW</button>
            </div>
        </div>
    </div>
    <!-- End of main content -->

<!-- javascript cart  -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const cartItemsContainer = document.getElementById('cart-items-container');
    const orderTotalElement = document.getElementById('order-total');
    const paymentTotalElement = document.getElementById('payment-total');
    const cartItemTemplate = document.getElementById('cart-item-template');
    
    let cart = [];

    // Function to add item to cart
    function addItemToCart(name, price, imageUrl) {
        const newItem = {
            name: name,
            price: price,
            imageUrl: imageUrl
        };
        cart.push(newItem);
        renderCart();
    }

    // Function to remove item from cart
    function removeItemFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    // Function to render cart items
    function renderCart() {
        cartItemsContainer.innerHTML = '';
        let total = 0;
        cart.forEach((item, index) => {
            total += item.price;
            const clone = document.importNode(cartItemTemplate.content, true);
            clone.querySelector('.item-name').textContent = item.name;
            clone.querySelector('img').src = item.imageUrl;
            clone.querySelector('.item-price').textContent = `Rp.${item.price.toLocaleString()}`;
            clone.querySelector('.remove-item').addEventListener('click', (e) => {
                e.preventDefault();
                removeItemFromCart(index);
            });
            cartItemsContainer.appendChild(clone);
        });
        orderTotalElement.textContent = `Rp.${total.toLocaleString()}`;
        paymentTotalElement.textContent = `Rp.${total.toLocaleString()}`;
    }

    // Example: Add items to cart (this can be triggered by user actions in a real application)
    addItemToCart('Fallout 4', 299000, '../images/game-images/header/header-fallout4.jpg');
    addItemToCart('Another Game', 199000, '../images/game-images/header/header-anothergame.jpg');
});
</script>

<!-- End of javascript cart  -->


    <!-- Footer -->
    <footer id="footer" class="show-footer">
        <div class="footer-container">
            <div class="logo">
                <img src="../images/TransparentLogo.png" alt="KGNexus Logo">
            </div>
            <div class="copyright">
                <p>Copyright &copy;2024; Designed by <span class="designer">KGNexus Team</span></p>
            </div>
        </div>
    </footer>
    <!-- End of footer -->

    <!-- Javascript Footer -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var footer = document.getElementById('footer');
            var windowHeight = window.innerHeight;
            var fullHeight = document.documentElement.scrollHeight;
            var footerHeight = footer.offsetHeight;

            function toggleFooter() {
                var scrollPosition = window.scrollY;
                if (scrollPosition + windowHeight >= fullHeight - footerHeight) {
                    footer.classList.add('show-footer');
                } else {
                    footer.classList.remove('show-footer');
                }
            }

            toggleFooter();
            document.addEventListener('scroll', toggleFooter);
            window.addEventListener('resize', toggleFooter);
        });
    </script>
    <!-- End of Javascript Footer -->

</body>

</html>