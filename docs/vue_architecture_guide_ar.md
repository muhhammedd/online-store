# دليل تحسين بنية Vue (Vue Architecture) للمتجر

لقد تم الآن إنشاء البنية الأساسية (Architecture) لتحويل متجرك بالكامل إلى تطبيق **SPA (Single Page Application)** بسرعة فائقة عبر Vue و Inertia.

---

## 1. ما الذي تم إنجازه؟ (What was implemented?)

### أ. ملف الجذر `app.blade.php`
- في Inertia، لا نحتاج لتكرار ملفات `head.blade.php` وغيرها. يتم تحميل الـ CSS والـ JavaScript الأساسي مرة واحدة فقط في هذا الملف.
- يحتوي هذا الملف الآن على `@inertia` والذي يقوم بتهيئة بيئة Vue بالكامل.

### ب. مكون الشريط العلوي `Navbar.vue`
- تم تحويل الكود الخاص بـ `navbar.blade.php` إلى مكون Vue (`resources/js/Components/Store/Navbar.vue`).
- **المهم هنا:** قمت بتبديل الروابط التقليدية `<a href="..">` بمكون `<Link :href="..">` الخاص بـ Inertia. هذا هو السر الذي يجعل الانتقال بين الصفحات يحدث بلحظة دون وميض (Reload)!
- تم استخدام `route().current()` من مكتبة Ziggy (المثبتة لديك) لتظليل الرابط النشط (Active Link) أوتوماتيكياً.

### ج. مكون التذييل `Footer.vue`
- تم تحويله بالكامل وربط الصفحات بشكل صحيح بمكون الـ `<Link>`.
- تم تحويل أكواد الـ JavaScript القديمة الخاصة بعرض السنة `document.write(new Date().getFullYear())` إلى كود Vue حديث `{{ new Date().getFullYear() }}`.

### د. الهيكل الأساسي `StoreLayout.vue`
هذا هو المكون الأهم! (`resources/js/Layouts/StoreLayout.vue`).
بدلاً من استخدام `@extends('layouts.store')`، في Vue ستستخدم هذا الـ Layout لتغليف صفحاتك.
هو يحتوي على `Navbar` بالأعلى، و `Footer` بالأسفل، ووسم `<slot />` في المنتصف ليتم وضع محتوى الصفحة فيه.

---

## 2. كيف تستخدم هذا الهيكل في صفحاتك؟ (How to use it)

الآن، لنفترض أنك تريد كتابة الصفحة الرئيسية (Home/Index.vue). كيف ستستخدم الهيكل؟

الأمر بسيط جداً:

```html
<script setup>
import { Head, Link } from '@inertiajs/vue3';
// 1. استيراد الهيكل
import StoreLayout from '@/Layouts/StoreLayout.vue';

defineProps({
    featuredProducts: Array,
});
</script>

<template>
    <Head title="Home" />

    <!-- 2. تغليف الصفحة بالهيكل -->
    <StoreLayout>
        
        <!-- هذا المحتوى سيظهر مكان وسم <slot /> في الهيكل -->
        <div class="hero-wrap">
            <h1>Welcome to MOAH</h1>
        </div>
        
    </StoreLayout>
</template>
```

---

## 3. خطوة إضافية لتحديث الـ `vite.config.js` 
لكي يفهم نظام Vite مسار الـ `@` (والذي يشير إلى مجلد `resources/js`) الموجود في الكود بالأعلى، تأكد أن ملف `vite.config.js` يحتوي على إعداد الـ `resolve`:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // إضافة هذا الجزء:
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

---

## 4. نصيحة حول (jQuery Plugins)
القالب القديم الخاص بك (Colorlib) يستخدم مكتبات قديمة مثل `owl.carousel` و `waypoints` والتي تعتمد على إعادة تحميل الصفحة لتعمل (Page Refresh). 
بما أننا الآن في تطبيق Vue الذي لا يعيد تحميل الصفحة، قد تلاحظ أن الـ Slider (الصور المتحركة) لا يعمل عند التنقل.
**الحل المستقبلي:** ستحتاج إما لتغييرها بمكتبات جاهزة متوافقة مع Vue (مثل `vue3-carousel` أو `Swiper.js`) وهو الأفضل لضمان عدم وجود أخطاء، أو إعادة تشغيل دالة jQuery بداخل دالة `onMounted` في الـ Layout.
