<script setup>
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
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

const items = computed(() => props.products?.data ?? []);
const links = computed(() => props.products?.links ?? []);

const formatCurrency = (value) => new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
}).format(Number(value ?? 0));

const productImage = (product) => product.images?.[0]?.image_url ?? '/images/product-1.png';
const hasVariants = (product) => (product.variants?.length ?? 0) > 0;

const addSimpleProductToCart = (product) => {
    router.post(route('cart.store', product.id), {
        product_id: product.id,
        product_variant_id: null,
        quantity: 1,
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Shop" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #0f0f0f 0%, #4b4b4b 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-9 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span>Shop</span>
                        </p>
                        <h1 class="mb-0 bread">Shop</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                        <div class="sidebar">
                            <div class="sidebar-box-2 mb-4">
                                <h2 class="heading">Active categories</h2>
                                <ul class="list-unstyled">
                                    <li v-for="category in categories" :key="category.id" class="mb-2">
                                        <span>{{ category.name }}</span>
                                        <span class="float-right text-muted">{{ category.products_count ?? 0 }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-box-2 mb-4">
                                <h2 class="heading">Featured picks</h2>
                                <ul class="list-unstyled">
                                    <li v-for="product in featuredProducts.slice(0, 4)" :key="product.id" class="mb-2">
                                        <Link :href="route('products.show', product.slug)">
                                            {{ product.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </div>

                            <div class="sidebar-box-2">
                                <h2 class="heading">Newest arrivals</h2>
                                <ul class="list-unstyled">
                                    <li v-for="product in latestProducts.slice(0, 4)" :key="product.id" class="mb-2">
                                        <Link :href="route('products.show', product.slug)">
                                            {{ product.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-9">
                        <div class="row">
                            <div
                                v-for="product in items"
                                :key="product.id"
                                class="col-sm-12 col-md-6 col-lg-4 ftco-animate d-flex"
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
                                        <p class="mb-3 text-muted">{{ product.short_description || 'Open the product page to review variants, pricing, and stock.' }}</p>

                                        <div class="pricing mt-auto">
                                            <p v-if="product.sale_price" class="price">
                                                <span class="mr-2 price-dc">{{ formatCurrency(product.base_price) }}</span>
                                                <span class="price-sale">{{ formatCurrency(product.sale_price) }}</span>
                                            </p>
                                            <p v-else class="price">
                                                <span>{{ formatCurrency(product.base_price) }}</span>
                                            </p>
                                        </div>

                                        <p class="bottom-area d-flex px-3 mt-3">
                                            <button
                                                v-if="!hasVariants(product)"
                                                type="button"
                                                class="add-to-cart text-center py-2 mr-1 border-0"
                                                @click="addSimpleProductToCart(product)"
                                            >
                                                <span>Add to cart <i class="ion-ios-add ml-1"></i></span>
                                            </button>
                                            <Link
                                                v-else
                                                :href="route('products.show', product.slug)"
                                                class="add-to-cart text-center py-2 mr-1"
                                            >
                                                <span>Choose variant <i class="ion-ios-options ml-1"></i></span>
                                            </Link>

                                            <Link :href="route('products.show', product.slug)" class="buy-now text-center py-2">
                                                Details<span><i class="ion-ios-arrow-forward ml-1"></i></span>
                                            </Link>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="!items.length" class="col-12">
                                <div class="alert alert-light border">
                                    No products are available in the catalog yet.
                                </div>
                            </div>
                        </div>

                        <div v-if="links.length > 3" class="row mt-5">
                            <div class="col text-center">
                                <div class="block-27">
                                    <ul>
                                        <li
                                            v-for="link in links"
                                            :key="`${link.label}-${link.url}`"
                                            :class="{ active: link.active, disabled: !link.url }"
                                        >
                                            <Link v-if="link.url" :href="link.url" v-html="link.label"></Link>
                                            <span v-else v-html="link.label"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
