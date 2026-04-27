<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

const page = usePage();
const user = page.props.auth?.user;

const form = useForm({
    name: user?.name ?? '',
    email: user?.email ?? '',
    message: '',
});

const submit = () => {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('message'),
    });
};
</script>

<template>
    <Head title="Contact" />

    <StoreLayout>
        <section class="hero-wrap hero-bread" style="background-image: linear-gradient(135deg, #161616 0%, #575757 100%);">
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-center">
                    <div class="col-md-9 text-center text-white">
                        <p class="breadcrumbs">
                            <span class="mr-2"><Link :href="route('home')">Home</Link></span>
                            <span>Contact</span>
                        </p>
                        <h1 class="mb-0 bread">Contact Us</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="ftco-section contact-section bg-light">
            <div class="container">
                <div class="row d-flex mb-5 contact-info">
                    <div class="col-md-4 d-flex">
                        <div class="info bg-white p-4 w-100">
                            <p><span>Address:</span> Cairo, Egypt</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="info bg-white p-4 w-100">
                            <p><span>Phone:</span> <a href="tel:+201123347663">+20 112 334 7663</a></p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="info bg-white p-4 w-100">
                            <p><span>Email:</span> <a href="mailto:support@moah.store">support@moah.store</a></p>
                        </div>
                    </div>
                </div>

                <div class="row block-9">
                    <div class="col-md-7 d-flex">
                        <form class="bg-white p-5 contact-form w-100" @submit.prevent="submit">
                            <h3 class="mb-4">Send a message</h3>

                            <div v-if="page.props.flash?.success" class="alert alert-success">
                                {{ page.props.flash.success }}
                            </div>

                            <div class="form-group">
                                <input v-model="form.name" type="text" class="form-control" placeholder="Your Name">
                                <small v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</small>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="email" class="form-control" placeholder="Your Email">
                                <small v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</small>
                            </div>

                            <div class="form-group">
                                <textarea
                                    v-model="form.message"
                                    cols="30"
                                    rows="7"
                                    class="form-control"
                                    placeholder="Tell us how we can help"
                                ></textarea>
                                <small v-if="form.errors.message" class="text-danger">{{ form.errors.message }}</small>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary py-3 px-5" :disabled="form.processing">
                                    {{ form.processing ? 'Sending...' : 'Send Message' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-5 d-flex">
                        <div class="bg-white p-5 w-100">
                            <h3 class="mb-4">Need help with the store?</h3>
                            <p class="mb-4">
                                Reach out for product questions, order support, storefront feedback, or implementation help.
                            </p>
                            <ul class="list-unstyled">
                                <li class="mb-3">Questions about products and availability</li>
                                <li class="mb-3">Help with checkout or order status</li>
                                <li class="mb-3">Feedback on the Laravel + Vue storefront experience</li>
                            </ul>
                            <Link :href="route('products.index')" class="btn btn-outline-dark">Browse products</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
