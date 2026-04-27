<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const values = [
    {
        title: 'Catalog-first commerce',
        body: 'The storefront is driven by the same product, category, variant, and pricing records used by the Laravel backend.',
    },
    {
        title: 'Reliable cart flow',
        body: 'Guest and authenticated carts follow a real lifecycle, allowing this frontend to reflect production-like commerce behavior.',
    },
    {
        title: 'Maintainable UI',
        body: 'These Vue pages are being aligned with Inertia conventions so the frontend stays easy to evolve instead of drifting from backend reality.',
    },
];

const milestones = [
    'Products, categories, brands, variants, cart items, and orders are modeled in the database.',
    'Store pages are served through Inertia and receive real props from Laravel controllers.',
    'Checkout writes real orders and decrements variant stock through application services.',
];
</script>

<template>
    <Head title="About" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #101010 0%, #424242 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-9 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span>About</span>
                        </p>
                        <h1 class="mb-0 bread">About MOAH</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4">
                        <span class="subheading">Why this storefront exists</span>
                        <h2 class="mb-4">A Laravel + Inertia commerce experience grounded in real application data.</h2>
                        <p>
                            This project is more than a theme conversion. The goal is a storefront whose Vue pages
                            accurately reflect real backend entities, validation rules, routes, and shopping flows.
                        </p>
                        <p>
                            That means product cards come from the catalog tables, cart state comes from the active cart,
                            and checkout fields map directly to the order creation pipeline.
                        </p>
                        <p class="mt-4">
                            <Link :href="route('products.index')" class="btn btn-primary mr-2">Browse catalog</Link>
                            <Link :href="route('contact.index')" class="btn btn-outline-dark">Get in touch</Link>
                        </p>
                    </div>

                    <div class="col-lg-6">
                        <div class="bg-light p-4 p-md-5 rounded">
                            <h4 class="mb-4">Project milestones</h4>
                            <ul class="list-unstyled">
                                <li v-for="milestone in milestones" :key="milestone" class="mb-3 d-flex">
                                    <span class="mr-3 mt-1 icon-check text-primary"></span>
                                    <span>{{ milestone }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section bg-light">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8 heading-section text-center">
                        <span class="subheading">Values</span>
                        <h2 class="mb-4">What this refactor is optimizing for</h2>
                    </div>
                </div>

                <div class="row">
                    <div v-for="value in values" :key="value.title" class="col-md-4 d-flex">
                        <div class="media block-6 services p-4 py-md-5 bg-white w-100">
                            <div class="icon d-flex justify-content-center align-items-center mb-4">
                                <span class="flaticon-bag"></span>
                            </div>
                            <div class="media-body text-center">
                                <h3 class="heading">{{ value.title }}</h3>
                                <p>{{ value.body }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
