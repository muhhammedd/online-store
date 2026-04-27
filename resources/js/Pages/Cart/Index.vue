<script setup>
import { reactive, watchEffect } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const props = defineProps({
    cart: {
        type: Object,
        required: true,
    },
    totals: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const quantities = reactive({});

watchEffect(() => {
    (props.cart.items ?? []).forEach((item) => {
        quantities[item.id] = item.quantity;
    });
});

const formatCurrency = (value) => new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
}).format(Number(value ?? 0));

const productImage = (item) => item.product?.images?.[0]?.image_url ?? '/images/product-1.png';

const variantLabel = (item) => item.product_variant?.attribute_options?.length
    ? item.product_variant.attribute_options.map((option) => `${option.attribute?.name ?? 'Option'}: ${option.value}`).join(' | ')
    : null;

const updateQuantity = (itemId) => {
    router.patch(route('cart.update', itemId), {
        quantity: quantities[itemId],
    }, {
        preserveScroll: true,
    });
};

const removeItem = (itemId) => {
    router.delete(route('cart.destroy', itemId), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Cart" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #131313 0%, #464646 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-9 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span>Cart</span>
                        </p>
                        <h1 class="mb-0 bread">Your Cart</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section ftco-cart">
            <div class="container">
                <div v-if="page.props.flash?.success" class="alert alert-success">
                    {{ page.props.flash.success }}
                </div>

                <div v-if="page.props.flash?.error || page.props.errors?.cart" class="alert alert-danger">
                    {{ page.props.flash?.error || page.props.errors?.cart }}
                </div>

                <div v-if="cart.items?.length" class="row">
                    <div class="col-md-12">
                        <div class="cart-list">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr class="text-center">
                                        <th>&nbsp;</th>
                                        <th>Product</th>
                                        <th>Details</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in cart.items" :key="item.id" class="text-center">
                                        <td class="product-remove">
                                            <button type="button" class="btn btn-link p-0" @click="removeItem(item.id)">
                                                <span class="ion-ios-close"></span>
                                            </button>
                                        </td>

                                        <td class="image-prod">
                                            <div class="img" :style="{ backgroundImage: `url(${productImage(item)})` }"></div>
                                        </td>

                                        <td class="product-name text-left">
                                            <h3 class="mb-1">
                                                <Link :href="route('products.show', item.product.slug)">{{ item.product.name }}</Link>
                                            </h3>
                                            <p v-if="variantLabel(item)" class="mb-1">{{ variantLabel(item) }}</p>
                                            <p class="mb-0 text-muted">{{ item.product.short_description || 'Cart item from the active catalog.' }}</p>
                                        </td>

                                        <td class="price">{{ formatCurrency(item.unit_price) }}</td>

                                        <td class="quantity">
                                            <div class="input-group mb-3">
                                                <input
                                                    v-model.number="quantities[item.id]"
                                                    type="number"
                                                    min="1"
                                                    class="quantity form-control input-number"
                                                    @change="updateQuantity(item.id)"
                                                >
                                            </div>
                                        </td>

                                        <td class="total">{{ formatCurrency(item.total_price) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div v-else class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="bg-light p-5 text-center">
                            <h3 class="mb-3">Your cart is empty</h3>
                            <p class="mb-4">Add a product from the live catalog to begin the checkout flow.</p>
                            <Link :href="route('products.index')" class="btn btn-primary">Browse products</Link>
                        </div>
                    </div>
                </div>

                <div v-if="cart.items?.length" class="row justify-content-end">
                    <div class="col col-lg-5 col-md-6 mt-5 cart-wrap">
                        <div class="cart-total mb-3">
                            <h3>Cart Totals</h3>
                            <p class="d-flex">
                                <span>Items</span>
                                <span>{{ totals.items_count }}</span>
                            </p>
                            <p class="d-flex">
                                <span>Subtotal</span>
                                <span>{{ formatCurrency(totals.subtotal) }}</span>
                            </p>
                            <p class="d-flex">
                                <span>Shipping</span>
                                <span>{{ formatCurrency(totals.shipping) }}</span>
                            </p>
                            <p class="d-flex">
                                <span>Discount</span>
                                <span>{{ formatCurrency(totals.discount) }}</span>
                            </p>
                            <hr>
                            <p class="d-flex total-price">
                                <span>Total</span>
                                <span>{{ formatCurrency(totals.total) }}</span>
                            </p>
                        </div>
                        <p class="text-center">
                            <Link :href="route('checkout.index')" class="btn btn-primary py-3 px-4">
                                Proceed to Checkout
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
