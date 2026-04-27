<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
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
const user = page.props.auth?.user;

const form = useForm({
    customer_name: user?.name ?? '',
    phone: '',
    email: user?.email ?? '',
    country: 'Egypt',
    city: '',
    address: '',
    address2: '',
    payment_method: 'cod',
    notes: '',
});

const formatCurrency = (value) => new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
}).format(Number(value ?? 0));

const variantLabel = (item) => item.product_variant?.attribute_options?.length
    ? item.product_variant.attribute_options.map((option) => `${option.attribute?.name ?? 'Option'}: ${option.value}`).join(' | ')
    : null;

const submit = () => {
    form.post(route('checkout.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Checkout" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #111 0%, #4c4c4c 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-9 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span class="mr-2"><Link :href="route('cart.index')">Cart</Link></span>
                            <span>Checkout</span>
                        </p>
                        <h1 class="mb-0 bread">Checkout</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section">
            <div class="container">
                <div v-if="page.props.errors?.checkout" class="alert alert-danger">
                    {{ page.props.errors.checkout }}
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <form class="billing-form" @submit.prevent="submit">
                            <div class="row">
                                <div class="col-md-7">
                                    <h3 class="mb-4 billing-heading">Billing Details</h3>

                                    <div class="form-group">
                                        <label for="customer_name">Full Name</label>
                                        <input id="customer_name" v-model="form.customer_name" type="text" class="form-control">
                                        <small v-if="form.errors.customer_name" class="text-danger">{{ form.errors.customer_name }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input id="phone" v-model="form.phone" type="text" class="form-control">
                                        <small v-if="form.errors.phone" class="text-danger">{{ form.errors.phone }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" v-model="form.email" type="email" class="form-control">
                                        <small v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input id="country" v-model="form.country" type="text" class="form-control">
                                        <small v-if="form.errors.country" class="text-danger">{{ form.errors.country }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input id="city" v-model="form.city" type="text" class="form-control">
                                        <small v-if="form.errors.city" class="text-danger">{{ form.errors.city }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input id="address" v-model="form.address" type="text" class="form-control">
                                        <small v-if="form.errors.address" class="text-danger">{{ form.errors.address }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="address2">Address line 2</label>
                                        <input id="address2" v-model="form.address2" type="text" class="form-control">
                                        <small v-if="form.errors.address2" class="text-danger">{{ form.errors.address2 }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Order Notes</label>
                                        <textarea id="notes" v-model="form.notes" rows="5" class="form-control"></textarea>
                                        <small v-if="form.errors.notes" class="text-danger">{{ form.errors.notes }}</small>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="cart-detail cart-total bg-light p-4 mb-4">
                                        <h3 class="billing-heading mb-4">Order Summary</h3>

                                        <div v-for="item in cart.items" :key="item.id" class="mb-3 border-bottom pb-3">
                                            <p class="mb-1"><strong>{{ item.product.name }}</strong></p>
                                            <p v-if="variantLabel(item)" class="mb-1 text-muted">{{ variantLabel(item) }}</p>
                                            <p class="mb-0 text-muted">
                                                {{ item.quantity }} x {{ formatCurrency(item.unit_price) }}
                                            </p>
                                        </div>

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

                                    <div class="cart-detail bg-light p-4">
                                        <h3 class="billing-heading mb-4">Payment Method</h3>

                                        <div class="form-group">
                                            <div class="radio">
                                                <label>
                                                    <input v-model="form.payment_method" type="radio" value="cod" class="mr-2">
                                                    Cash on delivery
                                                </label>
                                            </div>
                                            <small v-if="form.errors.payment_method" class="text-danger">{{ form.errors.payment_method }}</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary py-3 px-4" :disabled="form.processing">
                                            {{ form.processing ? 'Placing order...' : 'Place Order' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
