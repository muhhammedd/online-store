<script setup>
import { computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const variants = computed(() => props.product.variants ?? []);
const selectableVariants = computed(() => variants.value.filter((variant) => variant.is_active));

const form = useForm({
    product_id: props.product.id,
    product_variant_id: selectableVariants.value.length === 1 ? selectableVariants.value[0].id : '',
    quantity: 1,
});

const selectedVariant = computed(() => (
    selectableVariants.value.find((variant) => variant.id === Number(form.product_variant_id)) ?? null
));

const currentPrice = computed(() => selectedVariant.value?.price ?? props.product.sale_price ?? props.product.base_price);
const currentStock = computed(() => selectedVariant.value?.stock ?? null);
const gallery = computed(() => props.product.images?.length ? props.product.images : [{ image_url: '/images/product-1.png', alt_text: props.product.name }]);

const formatCurrency = (value) => new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
}).format(Number(value ?? 0));

const variantLabel = (variant) => {
    if (!variant?.attribute_options?.length) {
        return variant?.sku ? `SKU: ${variant.sku}` : 'Default variant';
    }

    return variant.attribute_options
        .map((option) => `${option.attribute?.name ?? 'Option'}: ${option.value}`)
        .join(' | ');
};

const submit = () => {
    form.post(route('cart.store', props.product.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="product.name" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #111 0%, #444 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-9 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span class="mr-2"><Link :href="route('products.index')">Shop</Link></span>
                            <span>{{ product.name }}</span>
                        </p>
                        <h1 class="mb-0 bread">{{ product.name }}</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-5">
                        <div class="bg-light p-4">
                            <img class="img-fluid mb-3" :src="gallery[0].image_url" :alt="gallery[0].alt_text || product.name">

                            <div class="row">
                                <div v-for="image in gallery.slice(1)" :key="image.id ?? image.image_url" class="col-4 mb-3">
                                    <img class="img-fluid border" :src="image.image_url" :alt="image.alt_text || product.name">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 product-details pl-md-5">
                        <span class="subheading">{{ product.category?.name ?? 'Catalog product' }}</span>
                        <h3 class="mb-3">{{ product.name }}</h3>

                        <p class="price mb-4">
                            <span>{{ formatCurrency(currentPrice) }}</span>
                        </p>

                        <p class="mb-3">{{ product.short_description || 'This product is available in the live catalog.' }}</p>
                        <p class="mb-4">{{ product.description || 'Open product details and choose the right configuration before adding it to your cart.' }}</p>

                        <div class="mb-4">
                            <p class="mb-2"><strong>Brand:</strong> {{ product.brand?.name ?? 'Not specified' }}</p>
                            <p class="mb-2"><strong>Base SKU:</strong> {{ product.sku || 'N/A' }}</p>
                            <p class="mb-0">
                                <strong>Availability:</strong>
                                <span v-if="currentStock !== null">{{ currentStock }} units in stock</span>
                                <span v-else>Stock managed at product level</span>
                            </p>
                        </div>

                        <div v-if="page.props.flash?.success" class="alert alert-success">
                            {{ page.props.flash.success }}
                        </div>

                        <div v-if="page.props.errors?.cart" class="alert alert-danger">
                            {{ page.props.errors.cart }}
                        </div>

                        <form @submit.prevent="submit">
                            <div v-if="selectableVariants.length" class="form-group">
                                <label for="variant-select">Choose variant</label>
                                <select id="variant-select" v-model="form.product_variant_id" class="form-control">
                                    <option disabled value="">Select a variant</option>
                                    <option
                                        v-for="variant in selectableVariants"
                                        :key="variant.id"
                                        :value="variant.id"
                                    >
                                        {{ variantLabel(variant) }} - {{ formatCurrency(variant.price) }}
                                    </option>
                                </select>
                                <small v-if="form.errors.product_variant_id" class="text-danger">
                                    {{ form.errors.product_variant_id }}
                                </small>
                            </div>

                            <div v-if="selectedVariant" class="alert alert-light border">
                                {{ variantLabel(selectedVariant) }}
                            </div>

                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input
                                    id="quantity"
                                    v-model.number="form.quantity"
                                    type="number"
                                    min="1"
                                    :max="currentStock || 999"
                                    class="form-control"
                                >
                                <small v-if="form.errors.quantity" class="text-danger">{{ form.errors.quantity }}</small>
                            </div>

                            <div class="d-flex flex-wrap">
                                <button type="submit" class="btn btn-black py-3 px-5 mr-2 mb-2" :disabled="form.processing">
                                    {{ form.processing ? 'Adding...' : 'Add to Cart' }}
                                </button>
                                <Link :href="route('cart.index')" class="btn btn-primary py-3 px-5 mb-2">View Cart</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
