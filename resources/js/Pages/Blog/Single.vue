<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const props = defineProps({
    slug: {
        type: String,
        required: true,
    },
});

const articles = {
    'catalogs-that-convert': {
        title: 'Designing catalog pages that stay aligned with your backend data',
        publishedAt: 'April 26, 2026',
        category: 'Frontend',
        sections: [
            'A storefront should not invent prices, availability, or imagery that the backend does not actually expose.',
            'The safest product card is one that reads directly from the product payload returned by the controller.',
            'When a product has variants, the listing page should guide users to the details page instead of pretending there is a single universal purchase path.',
        ],
    },
    'inertia-checkout-flow': {
        title: 'What a healthy Inertia checkout flow looks like in Laravel',
        publishedAt: 'April 25, 2026',
        category: 'Architecture',
        sections: [
            'Redirect after successful mutations so users land on a fresh page state and flash messages can be shared consistently.',
            'Keep checkout forms tied to the exact request fields your FormRequest validates.',
            'Share cart counts and flash messages globally so navigation and feedback stay in sync across the app.',
        ],
    },
    'theme-to-vue-refactor': {
        title: 'Refactoring an HTML theme into maintainable Vue pages',
        publishedAt: 'April 24, 2026',
        category: 'Refactor',
        sections: [
            'Blade placeholders like `{{ route(...) }}` do not belong inside Vue templates and will break once copied over directly.',
            'Start by replacing hard-coded anchors and forms with `Link`, `useForm`, and `router` patterns from Inertia.',
            'Then remove placeholder content and connect each screen to real controller props one page at a time.',
        ],
    },
};

const article = computed(() => articles[props.slug] ?? {
    title: 'Article not found',
    publishedAt: 'Unavailable',
    category: 'Journal',
    sections: [
        'This article does not exist in the current static journal dataset.',
        'Use the journal index to open one of the available entries.',
    ],
});
</script>

<template>
    <Head :title="article.title" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #141414 0%, #4f4f4f 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-10 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span class="mr-2"><Link :href="route('blog.index')">Journal</Link></span>
                            <span>Article</span>
                        </p>
                        <h1 class="mb-3 bread">{{ article.title }}</h1>
                        <p class="mb-0">{{ article.category }} | {{ article.publishedAt }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="bg-light p-5">
                            <p v-for="section in article.sections" :key="section" class="mb-4">
                                {{ section }}
                            </p>

                            <div class="mt-5">
                                <Link :href="route('blog.index')" class="btn btn-primary mr-2">Back to journal</Link>
                                <Link :href="route('products.index')" class="btn btn-outline-dark">Browse products</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
