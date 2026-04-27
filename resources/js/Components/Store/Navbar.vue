<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const cartCount = computed(() => page.props.cart?.itemsCount ?? 0);
const currentUser = computed(() => page.props.auth?.user ?? null);
</script>

<template>
    <div>
        <div class="py-1 bg-black">
            <div class="container">
                <div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
                    <div class="col-lg-12 d-block">
                        <div class="row d-flex">
                            <div class="col-md pr-4 d-flex topper align-items-center">
                                <div class="icon mr-2 d-flex justify-content-center align-items-center">
                                    <span class="icon-phone2"></span>
                                </div>
                                <span class="text">+20 112 334 7663</span>
                            </div>
                            <div class="col-md pr-4 d-flex topper align-items-center">
                                <div class="icon mr-2 d-flex justify-content-center align-items-center">
                                    <span class="icon-paper-plane"></span>
                                </div>
                                <span class="text">support@moah.store</span>
                            </div>
                            <div class="col-md-5 pr-4 d-flex topper align-items-center text-lg-right">
                                <span class="text">Curated catalog, fast checkout, and reliable order updates.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav id="ftco-navbar" class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light">
            <div class="container">
                <Link class="navbar-brand" :href="route('home')">MOAH</Link>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#ftco-nav"
                    aria-controls="ftco-nav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="oi oi-menu"></span> Menu
                </button>

                <div id="ftco-nav" class="collapse navbar-collapse">
                    <ul class="navbar-nav ml-auto align-items-lg-center">
                        <li class="nav-item" :class="{ active: route().current('home') }">
                            <Link :href="route('home')" class="nav-link">Home</Link>
                        </li>
                        <li class="nav-item" :class="{ active: route().current('products.*') }">
                            <Link :href="route('products.index')" class="nav-link">Shop</Link>
                        </li>
                        <li class="nav-item" :class="{ active: route().current('blog.*') }">
                            <Link :href="route('blog.index')" class="nav-link">Journal</Link>
                        </li>
                        <li class="nav-item" :class="{ active: route().current('about') }">
                            <Link :href="route('about')" class="nav-link">About</Link>
                        </li>
                        <li class="nav-item" :class="{ active: route().current('contact.*') }">
                            <Link :href="route('contact.index')" class="nav-link">Contact</Link>
                        </li>
                        <li v-if="currentUser" class="nav-item">
                            <Link :href="route('dashboard')" class="nav-link">Dashboard</Link>
                        </li>
                        <li v-else class="nav-item">
                            <Link :href="route('login')" class="nav-link">Login</Link>
                        </li>
                        <li class="nav-item cta cta-colored">
                            <Link :href="route('cart.index')" class="nav-link">
                                <span class="icon-shopping_cart"></span>[{{ cartCount }}]
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>
