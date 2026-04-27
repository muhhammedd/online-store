<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const props = defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    latestProducts: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
});

const formatCurrency = (value) => new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
}).format(Number(value ?? 0));

const productImage = (product) => product.images?.[0]?.image_url ?? '/images/product-1.png';
</script>

<template>
    <Head title="Home" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #111 0%, #2b2b2b 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-between">
                    <div class="col-lg-6 text-white py-5">
                        <span class="subheading">Real catalog. Real checkout. Real data.</span>
                        <h1 class="mb-4 bread">Modern storefront experiences for your actual inventory.</h1>
                        <p class="mb-4">
                            Browse featured releases, explore active categories, and move from product discovery to
                            checkout using the live Laravel data powering this project.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <Link :href="route('products.index')" class="btn btn-primary mr-2 mb-2">Browse Products</Link>
                            <Link :href="route('contact.index')" class="btn btn-outline-light mb-2">Contact Sales</Link>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="bg-white p-4 text-center rounded shadow-sm">
                                    <h2 class="mb-1">{{ featuredProducts.length }}</h2>
                                    <p class="mb-0 text-muted">Featured products</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-white p-4 text-center rounded shadow-sm">
                                    <h2 class="mb-1">{{ latestProducts.length }}</h2>
                                    <p class="mb-0 text-muted">Latest arrivals</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-white p-4 rounded shadow-sm">
                                    <h5 class="mb-3">Top categories</h5>
                                    <div class="d-flex flex-wrap">
                                        <span
                                            v-for="category in categories"
                                            :key="category.id"
                                            class="badge badge-light border mr-2 mb-2 p-2"
                                        >
                                            {{ category.name }} ({{ category.products_count ?? 0 }})
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8 heading-section text-center">
                        <span class="subheading">Categories</span>
                        <h2 class="mb-4">Shop by active collection</h2>
                        <p>These categories are pulled from the database and include real product counts.</p>
                    </div>
                </div>

                <div class="row">
                    <div v-for="category in categories" :key="category.id" class="col-md-6 col-lg-4 d-flex mb-4">
                        <div class="product w-100 p-4 bg-light">
                            <div class="text">
                                <span class="subheading">Category</span>
                                <h3 class="mb-2">{{ category.name }}</h3>
                                <p class="mb-3">{{ category.description }}</p>
                                <p class="mb-0 text-muted">{{ category.products_count ?? 0 }} active products</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="!categories.length" class="col-12">
                        <div class="alert alert-light border">
                            No active categories are available yet.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section bg-light">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8 heading-section text-center">
                        <span class="subheading">Featured</span>
                        <h2 class="mb-4">Highlighted products</h2>
                        <p>These products are marked as featured and active in the database.</p>
                    </div>
                </div>

                <div class="row">
                    <div
                        v-for="product in featuredProducts"
                        :key="product.id"
                        class="col-sm-12 col-md-6 col-lg-3 ftco-animate d-flex"
                    >
                        <div class="product d-flex flex-column w-100">
                            <Link :href="route('products.show', product.slug)" class="img-prod">
                                <img class="img-fluid" :src="productImage(product)" :alt="product.name">
                                <span v-if="product.sale_price" class="status">Sale</span>
                                <div class="overlay"></div>
                            </Link>

                            <div class="text py-3 pb-4 px-3 flex-grow-1 d-flex flex-column">
                                <div class="cat mb-2">
                                    <span>{{ product.category?.name ?? 'Uncategorized' }}</span>
                                </div>
                                <h3 class="mb-2">
                                    <Link :href="route('products.show', product.slug)">{{ product.name }}</Link>
                                </h3>
                                <p class="mb-3 text-muted">{{ product.short_description || 'View the product to explore details and variants.' }}</p>
                                <div class="pricing mt-auto">
                                    <p v-if="product.sale_price" class="price">
                                        <span class="mr-2 price-dc">{{ formatCurrency(product.base_price) }}</span>
                                        <span class="price-sale">{{ formatCurrency(product.sale_price) }}</span>
                                    </p>
                                    <p v-else class="price">
                                        <span>{{ formatCurrency(product.base_price) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!featuredProducts.length" class="col-12">
                        <div class="alert alert-light border">
                            No featured products have been configured yet.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8 heading-section text-center">
                        <span class="subheading">Latest arrivals</span>
                        <h2 class="mb-4">Recently published products</h2>
                        <p>The newest active products available in the catalog.</p>
                    </div>
                </div>

                <div class="row">
                    <div
                        v-for="product in latestProducts"
                        :key="product.id"
                        class="col-sm-12 col-md-6 col-lg-3 ftco-animate d-flex"
                    >
                        <div class="product d-flex flex-column w-100">
                            <Link :href="route('products.show', product.slug)" class="img-prod">
                                <img class="img-fluid" :src="productImage(product)" :alt="product.name">
                                <div class="overlay"></div>
                            </Link>

                            <div class="text py-3 pb-4 px-3 flex-grow-1 d-flex flex-column">
                                <div class="cat mb-2">
                                    <span>{{ product.category?.name ?? 'Uncategorized' }}</span>
                                </div>
                                <h3 class="mb-2">
                                    <Link :href="route('products.show', product.slug)">{{ product.name }}</Link>
                                </h3>
                                <div class="pricing mt-auto">
                                    <p class="price">
                                        <span>{{ formatCurrency(product.sale_price ?? product.base_price) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!latestProducts.length" class="col-12">
                        <div class="alert alert-light border">
                            No products are available yet.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
