# دليل تحويل المتجر من Blade إلى Vue.js باستخدام Inertia.js

الكثير من المبرمجين عندما يقررون استخدام Vue.js مع Laravel يقومون ببناء (API) كامل وفصل النظامين (Frontend و Backend) تماماً. هذا مكلف جداً، يحتاج صيانة مضاعفة، ويصعّب بناء المتجر! 

**الحل السحري:** هو استخدام **Inertia.js**. فهو يسمح لك باستخدام Vue كواجهة للمستخدم بنفس سهولة استخدام Blade، دون الحاجة لبناء API معقد!

---

## 1. ما هو Inertia.js وكيف يعمل؟
تخيل أن Inertia هو جسر يربط Laravel بـ Vue مباشرة:
- **في Blade:** كان المتحكم (Controller) يرسل البيانات (Data) إلى ملف الـ HTML (Blade) ويتم دمجها في الخادم (Server).
- **في Inertia:** المتحكم يرسل البيانات كـ (JSON) مباشرة إلى مكون (Vue Component)، وتقوم Vue بعرضها فوراً في متصفح المستخدم دون إعادة تحميل الصفحة (Single Page Application - SPA).

---

## 2. ما الذي تم تغييره الآن؟ (The Transformation)

في الخطوة السابقة، قمت بتغيير `HomeController` و `ShopController` لك:

**الكود القديم (Blade):**
```php
return view('index', compact('featuredProducts'));
```

**الكود الجديد (Inertia + Vue):**
```php
use Inertia\Inertia;
// ...
return Inertia::render('Home/Index', [
    'featuredProducts' => $featuredProducts
]);
```
هذا السطر البسيط يبحث عن ملف اسمه `Index.vue` داخل مجلد `resources/js/Pages/Home/` ويمرر له المنتجات!

---

## 3. كيف تكتب كود Vue بدلاً من Blade؟ (The Syntax Translation)

تم إنشاء ملف `Index.vue` لك داخل مجلد الصفحات. 
لكي تنسخ تصميمك القديم (HTML/Blade) إلى Vue، هناك قواعد بسيطة جداً للترجمة:

### أ. استقبال البيانات (Props)
في Blade كنت تستخدم المتغيرات مباشرة. في Vue، يجب أن "تستقبلها" أولاً في الأعلى:
```html
<script setup>
defineProps({
    featuredProducts: Array,
    latestProducts: Array,
});
</script>
```

### ب. طباعة المتغيرات
- **Blade:** `<h1>{{ $product->name }}</h1>`
- **Vue:** `<h1>{{ product.name }}</h1>` (بدون علامة `$`).

### ج. حلقات التكرار (Loops)
- **Blade:** 
```html
@foreach($products as $product)
    <div>{{ $product->name }}</div>
@endforeach
```
- **Vue:**
```html
<div v-for="product in products" :key="product.id">
    {{ product.name }}
</div>
```
*(ملاحظة: في Vue، الـ `:key` إجباري في أي تكرار لكي يعمل بسلاسة).*

### د. الشروط (If Statements)
- **Blade:** 
```html
@if($product->is_active)
    <span>Active</span>
@else
    <span>Hidden</span>
@endif
```
- **Vue:**
```html
<span v-if="product.is_active">Active</span>
<span v-else>Hidden</span>
```

### هـ. الروابط والانتقال السريع (Links & Navigation)
وهذا هو أهم شيء! لكي يكون موقعك سريعاً جداً كالتطبيقات (SPA) ولا يعيد تحميل الصفحة بالكامل عند الضغط على الروابط، **لا تستخدم وسم `<a>` العادي.**

- **القديم:** `<a href="/products/laptop">View</a>`
- **الجديد في Vue:**
```html
<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link href="/products/laptop">View</Link>
</template>
```
مكون `<Link>` السحري هذا يقوم بجلب بيانات الصفحة الجديدة في أجزاء من الثانية دون إغلاق الصفحة الحالية!

---

## 4. التعامل مع النماذج وإرسال البيانات (Forms)
عندما نقوم ببناء صفحة "إضافة للسلة" أو "تسجيل الدخول"، لن نستخدم `<form action="...">` العادي، بل سنستخدم اختراع Inertia الجميل للـ Forms:

```html
<script setup>
import { useForm } from '@inertiajs/vue3'

// 1. تجهيز بيانات الفورم
const form = useForm({
    quantity: 1,
    product_variant_id: 5,
})

// 2. دالة الإرسال
function addToCart() {
    form.post('/cart') // يقوم بإرسال البيانات للمتحكم CartController مباشرة
}
</script>

<template>
    <form @submit.prevent="addToCart">
        <input type="number" v-model="form.quantity" />
        
        <!-- إظهار رسالة الخطأ إن وجدت -->
        <div v-if="form.errors.quantity">{{ form.errors.quantity }}</div>
        
        <!-- تعطيل الزر أثناء التحميل (Loading state) -->
        <button type="submit" :disabled="form.processing">Add to Cart</button>
    </form>
</template>
```

---

## 5. الخطوات القادمة بالنسبة لك (Your Next Steps)

1. **نقل الـ HTML:** افتح ملف الـ Blade القديم الخاص بالـ Home (`index.blade.php`).
2. انسخ الـ HTML والصقه بداخل وسم `<template>` في الملف الجديد `resources/js/Pages/Home/Index.vue`.
3. قم بتغيير الـ `@foreach` إلى `v-for`.
4. قم بتشغيل خادم التطوير عن طريق تشغيل هذا الأمر في سطر الأوامر (Terminal):
   `npm run dev`
5. هذا الأمر سيقوم بمراقبة أكواد الـ Vue الخاصة بك وتحديثها في المتصفح فوراً أثناء كتابتك (Hot Reload)!
