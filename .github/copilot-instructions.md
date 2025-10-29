# AI Agent Instructions for Laravel POS System

## Project Overview
This is a Laravel-based Point of Sale (POS) system with integrated marketplace, procurement workflow, and inventory management features. The application serves both B2C (marketplace) and B2B (internal POS) use cases.

## Key Components

### Authentication & User Roles
- Uses Laravel's built-in authentication with role-based access (`app/Models/User.php`)
- Roles: customer, admin, supervisor (see `app/Http/Middleware/Is*.php`)
- Customers have separate login/register flows from internal users

### Core Features
1. **POS System** (`app/Http/Controllers/TransactionController.php`)
   - Cart management with real-time stock validation
   - Supports both regular and wholesale pricing tiers
   - Payment method integration

2. **Marketplace** (`app/Http/Controllers/Marketplace*.php`)
   - Public product catalog
   - Session-based cart
   - Customer order management
   - Online payment processing

3. **Procurement Workflow** (`app/Http/Controllers/Procurement*.php`)
   - Purchase Request → Purchase Order → Goods Receipt → Invoice flow
   - Supplier product management
   - Document generation (PDF) and approval workflows

4. **Inventory Management** (`app/Models/Item.php`, `app/Models/Category.php`)
   - Stock tracking
   - Category management
   - Price management (regular and wholesale)
   - Image handling with multiple storage locations

## Common Development Tasks

### Setting Up Development Environment
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### Adding New Features
1. Start with routes in `routes/web.php` - follow existing route grouping patterns
2. Add middleware checks (auth, roles) consistent with existing patterns
3. Follow CRUD resource convention with custom methods as needed
4. Use existing trait patterns for common functionality

### Database Work
- Migrations in `database/migrations/` follow sequential naming
- Models in `app/Models/` use clear relationship definitions
- Use factories (`database/factories/`) for testing data
- Follow existing casting patterns for price/numeric fields

### Handling Images
- Product images stored in `public/storage/` or `public/images/items/`
- Use `getPhotoUrlAttribute()` accessor pattern from `Item` model
- Always handle missing images with fallback to `no-image.png`

## Best Practices
1. Use role middleware for access control (`->middleware(IsAdmin::class)`)
2. Follow RESTful resource routing with custom methods when needed
3. Implement proper model relationships and type casting
4. Use service pattern for complex business logic
5. Follow existing naming conventions for routes and controllers

## Common Gotchas
- Cart operations require stock validation (`CartController@count_stock`)
- Online orders have different processing flow from POS transactions
- Price fields require proper decimal casting
- Image URLs may be external or local with different storage paths