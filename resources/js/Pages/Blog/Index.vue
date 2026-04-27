<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const posts = [
    {
        slug: 'catalogs-that-convert',
        title: 'Designing catalog pages that stay aligned with your backend data',
        excerpt: 'Why product cards should be driven by real variants, prices, and images instead of copied static markup.',
        category: 'Frontend',
        published_at: 'April 26, 2026',
    },
    {
        slug: 'inertia-checkout-flow',
        title: 'What a healthy Inertia checkout flow looks like in Laravel',
        excerpt: 'A quick look at cart state, order validation, flash messages, and redirect-based page transitions.',
        category: 'Architecture',
        published_at: 'April 25, 2026',
    },
    {
        slug: 'theme-to-vue-refactor',
        title: 'Refactoring an HTML theme into maintainable Vue pages',
        excerpt: 'How to replace Blade leftovers and hard-coded links with valid Inertia + Ziggy patterns.',
        category: 'Refactor',
        published_at: 'April 24, 2026',
    },
];
</script>

<template>
    <Head title="Journal" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #151515 0%, #4a4a4a 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-9 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span>Journal</span>
                        </p>
                        <h1 class="mb-0 bread">Store Journal</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section bg-light">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8 heading-section text-center">
                        <span class="subheading">Insights</span>
                        <h2 class="mb-4">Notes from the storefront rebuild</h2>
                        <p>These articles document the ideas behind the Laravel + Inertia storefront architecture.</p>
                    </div>
                </div>

                <div class="row">
                    <div v-for="post in posts" :key="post.slug" class="col-md-6 col-lg-4 d-flex">
                        <article class="blog-entry bg-white p-4 w-100">
                            <div class="text d-block">
                                <div class="meta mb-3">
                                    <div>{{ post.published_at }}</div>
                                    <div>{{ post.category }}</div>
                                </div>
                                <h3 class="heading">
                                    <Link :href="route('blog.single', post.slug)">{{ post.title }}</Link>
                                </h3>
                                <p>{{ post.excerpt }}</p>
                                <p class="mb-0">
                                    <Link :href="route('blog.single', post.slug)" class="btn btn-primary py-2 px-3">
                                        Read article
                                    </Link>
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
