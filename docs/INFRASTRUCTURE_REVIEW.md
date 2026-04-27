# تقرير مراجعة البنية التحتية

هذا الملف يلخص الملاحظات والتعديلات المطلوبة على البنية الحالية قبل البدء في كتابة منطق الأعمال والعمليات البرمجية.

## الحالة العامة

البنية الحالية تحتوي على الأساسيات من حيث وجود `migrations` و`models` و`controllers`، لكن يوجد عدم تطابق واضح بين:

- تصميم قاعدة البيانات في الخطة
- أسماء الأعمدة والعلاقات في `migrations`
- الحقول والعلاقات داخل `models`
- الراوتس والميثودز الموجودة في `controllers`
- أسماء ملفات `views` التي يتم استدعاؤها

بالتالي المشروع بصيغته الحالية غير جاهز بعد للدخول في طبقة منطق الأعمال، ويحتاج أولًا إلى تثبيت البنية التحتية.

## الملاحظات الحرجة

### 1. استخدام Controllers داخل migrations بدل أسماء أعمدة foreign keys

يوجد أكثر من migration يستخدم `foreignId()` بشكل غير صحيح عبر تمرير Controller class بدل اسم العمود.

أمثلة:

- `database/migrations/2026_04_22_123038_create_products_table.php`
- `database/migrations/2026_04_22_123348_create_carts_table.php`
- `database/migrations/2026_04_22_123428_create_cart_items_table.php`
- `database/migrations/2026_04_22_123739_create_product_variants_table.php`
- `database/migrations/2026_04_22_123950_product_images.php`

### لماذا هذا مهم

Laravel يتوقع أسماء أعمدة فعلية مثل:

- `brand_id`
- `category_id`
- `user_id`
- `cart_id`
- `product_id`

استخدام Controller class هنا سيؤدي إلى بناء schema غير صحيحة، أو إلى كسر العلاقات لاحقًا مع Eloquent.

### المطلوب

استبدال جميع هذه الحالات بصيغة صحيحة، مثل:

```php
$table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
$table->foreignId('category_id')->constrained()->cascadeOnDelete();
```

## 2. تكرار بعض الجداول في migrations

يوجد تكرار واضح لجدول `contact_messages` في ملفين:

- `database/migrations/2026_04_22_123653_create_contact_messages_table.php`
- `database/migrations/2026_04_22_134747_contact_messages.php`

### لماذا هذا مهم

وجود أكثر من migration ينشئ نفس الجدول سيؤدي إلى فشل أثناء `migrate` أو إلى تضارب في بنية قاعدة البيانات.

### المطلوب

- حذف أو دمج أحد الملفين
- الإبقاء على migration واحدة فقط لجدول `contact_messages`
- التأكد أن هذه الـ migration تحتوي جميع الأعمدة المطلوبة حسب الخطة

## 3. بعض migrations لا تحتوي على `down()` صحيح

ملفات فيها `down()` فارغ أو غير مكتمل:

- `database/migrations/2026_04_22_123950_product_images.php`
- `database/migrations/2026_04_22_124055_product_variant_attribute_option.php`
- `database/migrations/2026_04_22_134747_contact_messages.php`

### لماذا هذا مهم

أي rollback أو `migrate:fresh` لاحقًا سيكون غير موثوق، وهذا يعرقل التطوير والاختبارات.

### المطلوب

إضافة `Schema::dropIfExists(...)` بشكل صحيح لكل جدول تم إنشاؤه.

## 4. أسماء الأعمدة لا تطابق الخطة بالكامل

بعض الحقول الحالية لا تتوافق مع الخطة الأصلية.

أمثلة:

- `products.sale_price` يجب أن يكون `nullable`
- `products.brand_id` يجب أن يكون `nullable`
- `product_variants.stock` في الخطة هو `stock_quantity`
- `carts.status` في الخطة يشمل `active`, `converted`, `abandoned`

### لماذا هذا مهم

عندما تبدأ في كتابة المنطق والفلترة والسلة والطلبات، أي اختلاف بين الخطة والـ schema سيؤدي إلى ترقيعات متتالية بدل تصميم ثابت.

### المطلوب

مراجعة جميع الجداول ومقارنتها بالخطة واعتماد نسخة نهائية ثابتة للأسماء والأنواع والقيم الممكنة.

## الملاحظات المهمة على الموديلات

### 5. Model `Product` غير مطابق للمعايير ولا للجدول

الملف:

- `app/Models/Product.php`

المشاكل:

- اسم الكلاس مكتوب `product` بحرف صغير
- يحتوي على `fillable` لا تطابق الجدول
- يستخدم علاقات مع كلاسات غير موجودة مثل:
  - `Product_image`
  - `Product_varient`

### لماذا هذا مهم

هذا سيؤدي إلى أخطاء مباشرة في autoloading والعلاقات والاستعلامات.

### المطلوب

- تعديل اسم الكلاس إلى `Product`
- تحديث `fillable` ليطابق الأعمدة الفعلية
- تصحيح أسماء العلاقات لتستخدم موديلات موجودة فعليًا

## 6. موديلات أخرى لا تطابق الجداول

أمثلة:

- `app/Models/ProductVariant.php`
  - يحتوي `fillable` = `name`, `slug`
  - بينما الجدول الفعلي يحتوي `sku`, `price`, `stock`, `is_active`

- `app/Models/AttributeOption.php`
  - يستخدم `name` بدل `value`

- `app/Models/Order.php`
  - يستخدم `total` بينما الجدول يحتوي `subtotal`, `discount_total`, `shipping_total`, `grand_total`

- `app/Models/Cart.php`
  - يحتوي فقط `user_id` في `fillable` بينما الجدول يحتوي أيضًا `session_id`, `status`

### المطلوب

ضبط كل Model بحيث يطابق الجدول الفعلي 1:1 من حيث:

- `fillable`
- `casts`
- أسماء العلاقات
- أسماء methods بصيغة Laravel المعتادة

## 7. أسماء العلاقات غير موحدة وفيها أخطاء إملائية

أمثلة:

- `productvarient()`
- `productimage()`
- `attributeOption()`
- `cartItem()`
- `orderItem()`

### لماذا هذا مهم

حتى لو عملت تقنيًا في بعض الحالات، فهي ستجعل الكود مربكًا وغير متسق، كما أن بعضها مرتبط أصلًا بكلاسات أو جداول غير مضبوطة.

### المطلوب

اعتماد أسماء قياسية وواضحة:

- `productVariants()`
- `productImages()`
- `attributeOptions()`
- `cartItems()`
- `orderItems()`

## 8. لا يوجد Model لـ `ProductImage`

مع أن هناك migration لجدول `product_images`، لا يوجد موديل مقابل له.

### لماذا هذا مهم

الخطة تعتمد على صور المنتجات وعلاقتها بالمنتج، وغياب الموديل سيعطل التعامل المنظم معها داخل Eloquent.

### المطلوب

إنشاء `app/Models/ProductImage.php` وربطه مع `Product`.

## 9. علاقة Many-to-Many للمتغيرات والخيارات غير مكتملة

الخطة تعتمد على ربط:

- `product_variants`
- `attribute_options`

لكن الوضع الحالي يحتاج ضبط في:

- اسم pivot table
- اسم العلاقة في الموديلات
- تعريف المفاتيح إذا لم تتبع naming convention القياسي

### المطلوب

إما:

- توحيد اسم الجدول حسب convention

أو:

- التصريح باسم الجدول والمفاتيح داخل `belongsToMany()`

مثال:

```php
return $this->belongsToMany(
    AttributeOption::class,
    'product_variant_attribute_options',
    'product_variant_id',
    'attribute_option_id'
);
```

## الملاحظات على controllers و routes

### 10. الراوتس تشير إلى methods غير موجودة

في `routes/web.php` هناك routes تعتمد على methods غير معرفة فعليًا داخل الكنترولرز.

أمثلة:

- `ShopController@show`
- `CartController@store`
- `CartController@update`
- `CartController@destroy`
- `CheckoutController@store`
- `ContactController@store`

### لماذا هذا مهم

حتى لو ظهر `route:list` بنجاح، التنفيذ الفعلي سيفشل عند استدعاء هذه المسارات.

### المطلوب

إما:

- إنشاء هذه methods الآن كحد أدنى

أو:

- إزالة الراوتس غير الجاهزة مؤقتًا حتى لا يبقى المشروع في حالة مضللة

## 11. الكنترولرز ترجع Views بأسماء غير موجودة

أمثلة:

- `HomeController` يرجع `view('home')`
- `ShopController` يرجع `view('shop')`
- `CartController` يرجع `view('cart')`
- `CheckoutController` يرجع `view('checkout')`

بينما الملفات الموجودة فعليًا هي مثل:

- `resources/views/index.blade.php`
- `resources/views/product/index.blade.php`
- `resources/views/product/show.blade.php`
- `resources/views/cart/index.blade.php`
- `resources/views/cart/checkout.blade.php`

### لماذا هذا مهم

هذه الصفحات ستفشل مباشرة عند الفتح لأن أسماء الـ views لا تطابق الملفات الموجودة.

### المطلوب

توحيد واحد من الخيارين:

- تعديل الكنترولرز لتشير إلى المسارات الصحيحة للـ views
- أو إعادة تنظيم ملفات الـ views لتطابق ما تستدعيه الكنترولرز

## 12. جزء الإدارة غير مربوط بعد كما في الخطة

الخطة تطلب:

- Admin dashboard
- CRUD للمنتجات
- CRUD للتصنيفات
- CRUD للعلامات التجارية
- عرض الطلبات

لكن حاليًا:

- لا توجد admin resource routes كاملة
- أكثر الكنترولرز الإدارية فارغة

### المطلوب

إضافة الراوتس الإدارية تحت `auth`، ثم استكمال الحد الأدنى من CRUD structure قبل البدء بمنطق الإدارة.

## الملاحظات على validation و requests

### 13. لا توجد Form Requests للمسارات الأساسية

داخل `app/Http/Requests` لا يوجد إلا:

- `ProfileUpdateRequest`

### لماذا هذا مهم

الخطة تعتمد على `Form Requests` و`Validation`، وبدونها ستتسرب قواعد الإدخال إلى الكنترولرز بشكل مبكر وغير منظم.

### المطلوب

إضافة Requests مبدئية على الأقل لـ:

- Contact form
- Add to cart
- Update cart item
- Checkout
- Admin product/category/brand forms

## الملاحظات على البيانات الأولية

### 14. Seeders غير جاهزة بحسب الخطة

الملف الحالي:

- `database/seeders/DatabaseSeeder.php`

ما زال ينشئ مستخدمًا تجريبيًا فقط.

### المطلوب

إضافة seeders لـ:

- `categories`
- `brands`
- `attributes`
- `attribute_options`
- `products`
- `product_variants`
- admin user

## ترتيب التنفيذ المقترح

ينصح بتنفيذ الإصلاحات بهذا الترتيب:

1. إصلاح جميع `migrations` واعتماد schema نهائية ثابتة
2. إزالة التكرار وإكمال `down()`
3. ضبط `models` والعلاقات والأسماء
4. إنشاء `ProductImage` model
5. توحيد `routes/controllers/views`
6. إضافة `Form Requests`
7. إضافة `seeders`
8. بعد ذلك فقط بدء منطق:
   - عرض المنتجات
   - الفلترة
   - السلة
   - الطلبات
   - التواصل
   - الإدارة

## الخلاصة النهائية

المشروع يحتوي بداية جيدة من حيث إنشاء الملفات، لكن البنية الحالية ما زالت بحاجة إلى جولة تصحيح أساسية قبل كتابة منطق الأعمال.

أهم قاعدة هنا:

لا تبدأ في منطق `cart`, `checkout`, `orders`, `variants`, `filters` قبل أن تصبح هذه الطبقات مستقرة:

- database schema
- models and relationships
- routes and controllers
- views mapping
- validation layer

إذا تم إصلاح هذه الطبقات الآن، ستكون مرحلة البرمجة التالية أسرع وأنظف وأقل عرضة لإعادة العمل.
