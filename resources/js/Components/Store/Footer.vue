<script setup>
import { Link } from '@inertiajs/vue3';

const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="ftco-footer ftco-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">MOAH</h2>
                        <p>
                            A modern online store experience built with Laravel, Inertia, and Vue around the actual
                            catalog, cart, and checkout data in this project.
                        </p>
                    </div>
                </div>

                <div class="col-md">
                    <div class="ftco-footer-widget mb-4 ml-md-5">
                        <h2 class="ftco-heading-2">Explore</h2>
                        <ul class="list-unstyled">
                            <li><Link :href="route('home')" class="py-2 d-block">Home</Link></li>
                            <li><Link :href="route('products.index')" class="py-2 d-block">Shop</Link></li>
                            <li><Link :href="route('blog.index')" class="py-2 d-block">Journal</Link></li>
                            <li><Link :href="route('about')" class="py-2 d-block">About</Link></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Support</h2>
                        <ul class="list-unstyled">
                            <li><Link :href="route('contact.index')" class="py-2 d-block">Contact us</Link></li>
                            <li><Link :href="route('cart.index')" class="py-2 d-block">View cart</Link></li>
                            <li><Link :href="route('checkout.index')" class="py-2 d-block">Checkout</Link></li>
                            <li><Link :href="route('login')" class="py-2 d-block">Account</Link></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Contact</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li>
                                    <span class="icon icon-map-marker"></span>
                                    <span class="text">Cairo, Egypt</span>
                                </li>
                                <li>
                                    <a href="tel:+201123347663">
                                        <span class="icon icon-phone"></span>
                                        <span class="text">+20 112 334 7663</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:support@moah.store">
                                        <span class="icon icon-envelope"></span>
                                        <span class="text">support@moah.store</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <p>Copyright &copy; {{ currentYear }} MOAH. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</template>
