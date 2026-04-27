# Online Store Database Schema Diagram

This document describes the database structure for the online store project, including application tables, framework tables, Eloquent models, and the relationships between them.

Source of truth used for this document:
- Laravel migrations in [database/migrations](/C:/Users/moham.DESKTOP-VH22AN6/online-store/database/migrations)
- Eloquent models in [app/Models](/C:/Users/moham.DESKTOP-VH22AN6/online-store/app/Models)

Note: the live schema inspection returned no active tables, so this diagram is derived from the migration files and cross-checked against the model layer.

## Entity Relationship Diagram

```mermaid
erDiagram
    USERS {
        bigint id PK
        varchar name
        varchar email UK
        timestamp email_verified_at NULL
        varchar password
        varchar remember_token NULL
        timestamp created_at
        timestamp updated_at
    }

    PASSWORD_RESET_TOKENS {
        varchar email PK
        varchar token
        timestamp created_at NULL
    }

    SESSIONS {
        varchar id PK
        bigint user_id FK NULL
        varchar ip_address NULL
        text user_agent NULL
        longtext payload
        int last_activity
    }

    CACHE {
        varchar key PK
        mediumtext value
        int expiration
    }

    CACHE_LOCKS {
        varchar key PK
        varchar owner
        int expiration
    }

    JOBS {
        bigint id PK
        varchar queue
        longtext payload
        tinyint attempts
        int reserved_at NULL
        int available_at
        int created_at
    }

    JOB_BATCHES {
        varchar id PK
        varchar name
        int total_jobs
        int pending_jobs
        int failed_jobs
        longtext failed_job_ids
        mediumtext options NULL
        int cancelled_at NULL
        int created_at
        int finished_at NULL
    }

    FAILED_JOBS {
        bigint id PK
        varchar uuid UK
        text connection
        text queue
        longtext payload
        longtext exception
        timestamp failed_at
    }

    CATEGORIES {
        bigint id PK
        varchar name
        varchar slug UK
        text description
        boolean is_active
        int parent_id FK NULL
        timestamp created_at
        timestamp updated_at
    }

    BRANDS {
        bigint id PK
        varchar name
        varchar slug UK
        text logo NULL
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        bigint brand_id FK NULL
        bigint category_id FK
        varchar name
        varchar slug UK
        varchar short_description NULL
        text description NULL
        int base_price
        int sale_price NULL
        varchar sku UK NULL
        boolean is_featured
        boolean is_active
        timestamp published_at NULL
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_IMAGES {
        bigint id PK
        bigint product_id FK
        varchar image_url
        int sort_order
        varchar alt_text NULL
        boolean is_primary
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_VARIANTS {
        bigint id PK
        bigint product_id FK
        int stock
        decimal price
        varchar sku UK NULL
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    ATTRIBUTES {
        bigint id PK
        varchar name
        varchar slug
        timestamp created_at
        timestamp updated_at
    }

    ATTRIBUTE_OPTIONS {
        bigint id PK
        bigint attribute_id FK
        varchar value
        int sort_order
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_VARIANT_ATTRIBUTE_OPTIONS {
        bigint id PK
        bigint product_variant_id FK
        bigint attribute_option_id FK
    }

    CARTS {
        bigint id PK
        bigint user_id FK NULL
        varchar session_id NULL
        enum status
        timestamp created_at
        timestamp updated_at
    }

    CART_ITEMS {
        bigint id PK
        bigint cart_id FK
        bigint product_id FK
        bigint product_variant_id FK NULL
        int quantity
        decimal unit_price
        decimal unit_discount
        decimal total_price
        timestamp created_at
        timestamp updated_at
    }

    ORDERS {
        bigint id PK
        bigint user_id FK NULL
        varchar order_number UK
        enum status
        decimal shipping_cost
        decimal discount
        decimal total
        varchar payment_method NULL
        varchar payment_status
        varchar customer_name
        varchar email NULL
        varchar phone
        varchar country
        varchar city
        varchar address
        varchar address2 NULL
        text notes NULL
        timestamp created_at
        timestamp updated_at
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
        bigint product_variant_id FK NULL
        varchar product_name_snapshot
        varchar variant_snapshot NULL
        int quantity
        decimal unit_price
        decimal unit_discount
        decimal total_price
        timestamp created_at
        timestamp updated_at
    }

    CONTACT_MESSAGES {
        bigint id PK
        varchar name
        varchar email
        text message
        boolean is_read
        timestamp created_at
        timestamp updated_at
    }

    USERS ||--o{ SESSIONS : "owns"
    USERS ||--o{ CARTS : "has"
    USERS ||--o{ ORDERS : "places"
    CATEGORIES ||--o{ PRODUCTS : "contains"
    CATEGORIES ||--o{ CATEGORIES : "parent of"
    BRANDS o|--o{ PRODUCTS : "brands"
    PRODUCTS ||--o{ PRODUCT_IMAGES : "has"
    PRODUCTS ||--o{ PRODUCT_VARIANTS : "has"
    PRODUCTS ||--o{ CART_ITEMS : "referenced by"
    PRODUCTS ||--o{ ORDER_ITEMS : "referenced by"
    ATTRIBUTES ||--o{ ATTRIBUTE_OPTIONS : "has"
    PRODUCT_VARIANTS ||--o{ PRODUCT_VARIANT_ATTRIBUTE_OPTIONS : "uses"
    ATTRIBUTE_OPTIONS ||--o{ PRODUCT_VARIANT_ATTRIBUTE_OPTIONS : "assigned to"
    CARTS ||--o{ CART_ITEMS : "contains"
    PRODUCT_VARIANTS o|--o{ CART_ITEMS : "selected as"
    ORDERS ||--o{ ORDER_ITEMS : "contains"
    PRODUCT_VARIANTS o|--o{ ORDER_ITEMS : "captured as"
```

## Table to Model Mapping

| Table | Model | Notes |
| --- | --- | --- |
| `users` | `App\Models\User` | Authentication user model |
| `categories` | `App\Models\Category` | Supports self-referencing parent/child hierarchy |
| `brands` | `App\Models\Brand` | Brand catalog entity |
| `products` | `App\Models\Product` | Core catalog entity |
| `product_images` | `App\Models\ProductImage` | Product gallery and primary image |
| `product_variants` | `App\Models\ProductVariant` | Variant-level stock and price |
| `attributes` | `App\Models\Attribute` | Variant option group, such as size or color |
| `attribute_options` | `App\Models\AttributeOption` | Concrete option values |
| `product_variant_attribute_options` | No dedicated model | Pivot table for many-to-many variant option assignment |
| `carts` | `App\Models\Cart` | Anonymous or authenticated shopping cart |
| `cart_items` | `App\Models\CartItem` | Cart line items |
| `orders` | `App\Models\Order` | Checkout order header |
| `order_items` | `App\Models\OrderItem` | Frozen line-item snapshot for orders |
| `contact_messages` | `App\Models\ContactMessage` | Contact form submissions |
| `password_reset_tokens` | No dedicated model | Laravel framework table |
| `sessions` | No dedicated model | Laravel session store |
| `cache` | No dedicated model | Laravel cache store |
| `cache_locks` | No dedicated model | Laravel cache locking |
| `jobs` | No dedicated model | Laravel queue jobs |
| `job_batches` | No dedicated model | Laravel batch jobs |
| `failed_jobs` | No dedicated model | Laravel failed queue jobs |

## Model Relationship Inventory

| Model | Relationships |
| --- | --- |
| `User` | No explicit app-level relationships defined in the model, though `carts`, `orders`, and `sessions` reference `user_id` in the schema |
| `Category` | `products()`, `children()`, `parent()` |
| `Brand` | `products()` |
| `Product` | `brand()`, `category()`, `images()`, `variants()` |
| `ProductImage` | `product()` |
| `ProductVariant` | `product()`, `attributeOptions()` |
| `Attribute` | `attributeOption()` |
| `AttributeOption` | `attribute()`, `productvariants()` |
| `Cart` | `user()`, `items()` |
| `CartItem` | `cart()`, `product()`, `productVariant()` |
| `Order` | `user()`, `items()` |
| `OrderItem` | `order()`, `product()`, `productVariant()` |
| `ContactMessage` | No relationships |

## Relationship Summary

| Parent | Child | Type | Key |
| --- | --- | --- | --- |
| `users` | `sessions` | One to many | `sessions.user_id -> users.id` |
| `users` | `carts` | One to many | `carts.user_id -> users.id` |
| `users` | `orders` | One to many | `orders.user_id -> users.id` |
| `categories` | `categories` | Self reference | `categories.parent_id -> categories.id` |
| `categories` | `products` | One to many | `products.category_id -> categories.id` |
| `brands` | `products` | One to many | `products.brand_id -> brands.id` |
| `products` | `product_images` | One to many | `product_images.product_id -> products.id` |
| `products` | `product_variants` | One to many | `product_variants.product_id -> products.id` |
| `products` | `cart_items` | One to many | `cart_items.product_id -> products.id` |
| `products` | `order_items` | One to many | `order_items.product_id -> products.id` |
| `attributes` | `attribute_options` | One to many | `attribute_options.attribute_id -> attributes.id` |
| `product_variants` | `product_variant_attribute_options` | One to many | `product_variant_attribute_options.product_variant_id -> product_variants.id` |
| `attribute_options` | `product_variant_attribute_options` | One to many | `product_variant_attribute_options.attribute_option_id -> attribute_options.id` |
| `product_variants` | `attribute_options` | Many to many | Via `product_variant_attribute_options` |
| `carts` | `cart_items` | One to many | `cart_items.cart_id -> carts.id` |
| `product_variants` | `cart_items` | One to many, optional | `cart_items.product_variant_id -> product_variants.id` |
| `orders` | `order_items` | One to many | `order_items.order_id -> orders.id` |
| `product_variants` | `order_items` | One to many, optional | `order_items.product_variant_id -> product_variants.id` |

## Observed Model and Schema Gaps

These are worth knowing when using this document for development or refactoring:

| Area | Observation |
| --- | --- |
| `User` model | The model does not define `carts()` or `orders()` relationships even though both tables reference `user_id`. |
| `Attribute` model | The relationship is named `attributeOption()` but returns `hasMany`, so the plural name `attributeOptions()` would better match the schema. |
| `AttributeOption` model | The many-to-many relationship is named `productvariants()` instead of the conventional `productVariants()`. |
| `Brand` model | The migration includes `logo` and `is_active`, but they are not listed in `$fillable`. |
| `Order` model | `$fillable` contains `state`, but the `orders` table does not have a `state` column. |
| `Product` model | `$fillable` matches the major business columns, but schema-only constraints like unique slug and SKU should still be considered during validation. |
| `Pivot table` | `product_variant_attribute_options` has no timestamps and no dedicated Eloquent pivot model, which is fine for a simple pivot but worth noting. |

## Recommended Usage

- Use the Mermaid block above in project docs, PRs, or onboarding material.
- Treat migrations as the authoritative schema definition until the live database is confirmed and synchronized.
- If you want stricter model accuracy, the next improvement would be aligning the model relationship names and `$fillable` arrays with the schema.
