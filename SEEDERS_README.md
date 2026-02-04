# Database Seeders Documentation

This document describes all the seeders that have been created for the tour management system.

## New Seeders Created

### 1. ActivitySeeder
Seeds homepage activities with images.

**Location:** `database/seeders/ActivitySeeder.php`

**Data Seeded:**
- Wildlife Safari
- Mountain Climbing
- Beach Holidays
- Photography Tours
- Cultural Tours
- Water Activities

**Features:**
- Includes images (image_url)
- Includes icons (as fallback)
- Display order configured
- All items set as active

### 2. CoreValueSeeder
Seeds Core Values for the About Page with images.

**Location:** `database/seeders/CoreValueSeeder.php`

**Data Seeded:**
- Sustainability
- Community
- Safety
- Excellence
- Authenticity
- Integrity

**Features:**
- Includes images (image_url)
- Includes icons (as fallback)
- Display order configured
- All items set as active

### 3. WhyTravelWithUsSeeder
Seeds "Why Travel With Us" items for the About Page with images.

**Location:** `database/seeders/WhyTravelWithUsSeeder.php`

**Data Seeded:**
- Expert Local Guides
- Sustainable Tourism
- Safety First Approach
- Award Winning Service
- Local Partnerships
- Personalized Service

**Features:**
- Includes images (image_url)
- Display order configured
- All items set as active

## Running Seeders

### Run All Seeders
```bash
php artisan migrate:fresh --seed
```
or
```bash
php artisan db:seed
```

### Run Individual Seeders
```bash
php artisan db:seed --class=ActivitySeeder
php artisan db:seed --class=CoreValueSeeder
php artisan db:seed --class=WhyTravelWithUsSeeder
```

## Seeder Order in DatabaseSeeder

The seeders are called in the following order in `DatabaseSeeder.php`:

1. TourismRolePermissionSeeder
2. ComprehensiveUserSeeder
3. HeroSlideSeeder
4. SmsGatewaySeeder
5. NotificationProviderSeeder
6. PaymentGatewaySeeder
7. TourCategorySeeder
8. HomepageDestinationSeeder
9. TanzaniaSpecialistToursSeeder
10. TourItinerarySeeder
11. HotelSeeder
12. AboutPageSeeder (sections, team, recognitions, timeline, statistics)
13. **ActivitySeeder** ⭐ NEW
14. **CoreValueSeeder** ⭐ NEW
15. **WhyTravelWithUsSeeder** ⭐ NEW

## Important Notes

### Image Paths
The seeders include placeholder image paths (e.g., `images/activities/wildlife-safari.jpg`). These images should be:
- Placed in the `public/images/` directory structure
- Or uploaded through the admin dashboard gallery system
- Or updated to use gallery image IDs instead

### Updating Existing Data
All seeders use `updateOrCreate()` to avoid duplicates. This means:
- If data already exists (matched by title/name), it will be updated
- If data doesn't exist, it will be created
- Running seeders multiple times is safe and won't create duplicates

### Admin Management
All seeded data can be managed through the admin dashboard:
- **Activities:** Admin → Homepage Sections → Activities
- **Core Values:** Admin → About Page → Values tab
- **Why Travel With Us:** Admin → About Page → Why Travel With Us tab

## Models and Features

### Activity Model
- Table: `activities`
- Supports: images from gallery or direct URLs, icons, display order
- Scopes: `active()`, `forHomepage()`
- Display: Homepage activities section

### AboutPageValue Model
- Table: `about_page_values`
- Supports: images from gallery or direct URLs, icons (as fallback), display order
- Display: About page "Our Core Values" section

### WhyTravelWithUs Model
- Table: `why_travel_with_us`
- Supports: images from gallery or direct URLs, display order
- Scopes: `active()`, `forDisplay()`
- Display: About page "Why Travel With Us" section

## Frontend Integration

### Homepage
Activities are displayed in the "What You Can Experience" section with images.

### About Page
- Core Values are displayed in the "Our Core Values" section with images
- Why Travel With Us items are displayed in the "Why Travel With Us" section with images

Both sections fall back to icons if no images are provided.












