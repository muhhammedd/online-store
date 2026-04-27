<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="mb-2 text-lg font-semibold">Welcome back{{ user?.name ? `, ${user.name}` : '' }}.</h3>
                        <p class="mb-4 text-sm text-gray-600">
                            Your account area is active. From here you can manage your profile or jump back to the storefront.
                        </p>
                        <div class="flex gap-3">
                            <Link :href="route('profile.edit')" class="rounded-md bg-gray-900 px-4 py-2 text-white">
                                Edit Profile
                            </Link>
                            <Link :href="route('home')" class="rounded-md border border-gray-300 px-4 py-2 text-gray-700">
                                Visit Store
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
