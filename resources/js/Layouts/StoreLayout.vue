<script setup>
import Navbar from '@/Components/Store/Navbar.vue';
import Footer from '@/Components/Store/Footer.vue';
import { onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const initializeTheme = () => {
    if (typeof window.initTheme === 'function') {
        window.initTheme();
    }
};

onMounted(() => {
    initializeTheme();
});

const removeSuccessListener = router.on('success', () => {
    initializeTheme();
});

onUnmounted(() => {
    removeSuccessListener();
});
</script>

<template>
    <div>
        <Navbar />

        <main>
            <slot />
        </main>

        <Footer />
    </div>
</template>
