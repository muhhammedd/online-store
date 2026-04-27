<div class="py-1 bg-black">
    <div class="container">
        <div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
            <div class="col-lg-12 d-block">
                <div class="row d-flex">
                    <div class="col-md pr-4 d-flex topper align-items-center">
                        <div class="icon mr-2 d-flex justify-content-center align-items-center">
                            <span class="icon-phone2"></span>
                        </div>
                        <span class="text">+201123347663</span>
                    </div>
                    <div class="col-md pr-4 d-flex topper align-items-center">
                        <div class="icon mr-2 d-flex justify-content-center align-items-center">
                            <span class="icon-paper-plane"></span>
                        </div>
                        <span class="text">muhamedeiddev@gmail.com</span>
                    </div>
                    <div class="col-md-5 pr-4 d-flex topper align-items-center text-lg-right">
                        <span class="text">3-5 Business days delivery &amp; Free Returns</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">MOAH</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item dropdown {{ request()->routeIs('products.*', 'cart.*', 'checkout.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Catalog</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                        <a class="dropdown-item" href="{{ route('products.index') }}">Shop</a>
                        <a class="dropdown-item" href="{{ route('products.show', 'nike-free-rn-2019-id') }}">Single Product</a>
                        <a class="dropdown-item" href="{{ route('cart.index') }}">Cart</a>
                        <a class="dropdown-item" href="{{ route('checkout.index') }}">Checkout</a>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                    <a href="{{ route('about') }}" class="nav-link">About</a>
                </li>
                <li class="nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                    <a href="{{ route('blog.index') }}" class="nav-link">Blog</a>
                </li>
                <li class="nav-item {{ request()->routeIs('contact.index') ? 'active' : '' }}">
                    <a href="{{ route('contact.index') }}" class="nav-link">Contact</a>
                </li>
                <li class="nav-item cta cta-colored">
                    <a href="{{ route('cart.index') }}" class="nav-link"><span class="icon-shopping_cart"></span>[0]</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- END nav -->
