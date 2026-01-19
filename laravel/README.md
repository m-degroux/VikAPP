# 🏔️ Viking Raids Management System (VIKAPP)

A comprehensive Laravel-based platform for managing mountain trail running events (raids and races), teams, participants, and clubs.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-45%2F45%20Passing-green.svg)](tests/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Architecture](#architecture)
- [Installation](#installation)
- [Database](#database)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Project Structure](#project-structure)
- [Contributing](#contributing)

## 🎯 Overview

VIKAPP is a full-stack web application designed to manage trail running events called "raids" (multi-race competitions). The system handles:

- **Events Management**: Create and manage raids (collections of races)
- **Race Management**: Individual races with varying difficulties, distances, and age categories
- **Team Registration**: Teams can register for races with multiple participants
- **Club Management**: Sports clubs can be associated with participants
- **User Roles**: Members (runners) and Admins (event organizers)
- **Participation Tracking**: Track team performance, points, and rankings

### Technology Stack

- **Backend**: Laravel 12.47
- **PHP**: 8.2.29
- **Database**: MySQL (GROUPE5)
- **Frontend**: Blade Templates + Tailwind CSS
- **Authentication**: Laravel Breeze with dual guards (web/admin)
- **API**: RESTful API with Sanctum token authentication
- **Testing**: PHPUnit + Pest (45 tests, 100% passing)

## ✨ Features

### Public Features
- Browse upcoming raids and races
- View race details (distance, difficulty, age categories)
- Search and filter events
- Responsive design for mobile/desktop

### Member Features (Authenticated Users)
- Register and login
- Create/join teams
- Register for races
- View personal dashboard with upcoming races
- Track participation history and points
- Manage profile information

### Admin Features
- Create and manage raids
- Create and manage races
- Assign age categories to races
- Set pricing for different age groups
- Manage event logistics (start location, GPS coordinates)
- View participation statistics

### API Features
- Token-based authentication
- CRUD operations for raids, races, teams, clubs
- Member registration and authentication
- Race participation management

## 🏗️ Architecture

### Design Patterns & Principles

#### SOLID Principles
- **Single Responsibility**: Each service handles one domain (RaidService, RaceService, etc.)
- **Open/Closed**: Services extend AbstractService, controllers extend BaseController
- **Liskov Substitution**: Repository interfaces with multiple implementations
- **Interface Segregation**: Dedicated repositories per model
- **Dependency Injection**: Services injected via constructor

#### Repository Pattern
```
Models → Repositories → Services → Controllers → Views
```

#### Service Layer
All business logic isolated in services:
- `RaidService`: Raid management and queries
- `RaceService`: Race management and filtering
- `TeamService`: Team operations
- `RunnerService`: Runner statistics and participations
- `GeocodingService`: Address to GPS coordinates conversion

#### Request Validation
Dedicated Form Request classes for validation:
- `StoreRaidRequest`, `UpdateRaidRequest`
- `StoreRaceRequest`, `UpdateRaceRequest`
- `LoginRequest`, `RegisterRequest`

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for asset compilation)

### Setup Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd laravel
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Update .env with database credentials**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=GROUPE5
DB_USERNAME=your_username
DB_PASSWORD=your_password

SESSION_DRIVER=file
```

6. **Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

7. **Compile assets**
```bash
npm run dev
```

8. **Start development server**
```bash
php artisan serve
```

Visit `http://localhost:8000`

## 🗄️ Database

### Schema Overview

#### Core Tables
- **vik_member**: User accounts (runners)
- **vik_admin**: Administrator accounts
- **vik_club**: Sports clubs
- **vik_raid**: Event collections
- **vik_race**: Individual races within raids
- **vik_team**: Teams participating in races

#### Reference Tables
- **vik_type**: Event types (competition, leisure)
- **vik_difficulty**: Race difficulties (easy, medium, hard)
- **vik_age_category**: Age groups (12-15, 16-17, 18+)

#### Join Tables
- **vik_join_race**: Race participation records
- **vik_join_team**: Team membership
- **vik_race_age_cat**: Race-Age category pricing
- **vik_manage_raid**: Admin-Raid management
- **vik_race_manager**: Admin-Race management

### Key Relationships
```
Raid (1) → (N) Race
Race (1) → (N) Team
Team (1) → (N) JoinTeam (N) → (1) Member
Race (1) → (N) JoinRace (N) → (1) Member
Race (N) → (N) AgeCategory (through race_age_cat)
```

### Seeding Strategy

The database seeder follows a two-phase approach:

1. **Phase 1: Base Data (insert.sql)**
   - Reference data (types, difficulties, age categories)
   - Initial admin account
   - Sample members, clubs, raids, races
   - ~100 records

2. **Phase 2: Supplementary Data (Factories)**
   - Additional admins with member accounts
   - More members, clubs, raids, races
   - Teams and participations
   - Management relationships
   - ~300+ additional records

**Command**: `php artisan migrate:fresh --seed`

**Result**: ~400+ total database records

### Entity-Relationship Diagram

```
┌─────────────┐
│   Member    │
└──────┬──────┘
       │
       ├─────────────┐
       │             │
┌──────▼──────┐ ┌───▼─────┐
│    Admin    │ │  Club   │
└─────────────┘ └─────────┘
       │
       │ manages
       │
┌──────▼──────┐
│    Raid     │
│             │
│  type_id ───┼──→ Type
│  diff_id ───┼──→ Difficulty
│  club_id ───┼──→ Club
└──────┬──────┘
       │
       │ has many
       │
┌──────▼──────┐
│    Race     │
│             │
│  raid_id    │
│  type_id ───┼──→ Type
│  diff_id ───┼──→ Difficulty
└──────┬──────┘
       │
       ├─────────────────┐
       │                 │
┌──────▼──────┐   ┌──────▼────────────┐
│    Team     │   │  RaceAgeCategory  │
│             │   │  (pricing)        │
│  race_id    │   │  race_id          │
│  club_id    │   │  age_id           │
│  user_id    │   │  bel_price        │
└──────┬──────┘   └───────────────────┘
       │
       │ has members
       │
┌──────▼──────┐
│  JoinTeam   │
│             │
│  team_id    │
│  user_id    │
└─────────────┘
```

## 📡 API Documentation

### Authentication

#### Register
```http
POST /api/register
Content-Type: application/json

{
  "user_username": "john_doe",
  "user_password": "password",
  "user_password_confirmation": "password",
  "mem_firstname": "John",
  "mem_name": "Doe",
  "mem_sex": "M",
  "mem_size": 180,
  "mem_weight": 75.5,
  "mem_birth_year": 1990,
  "mem_mail": "john@example.com",
  "mem_phone": "+33612345678"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "username": "john_doe",
  "password": "password"
}

Response:
{
  "token": "1|abc123...",
  "user": { ... }
}
```

### Raids

#### List Raids
```http
GET /api/raids?upcoming=1&limit=10
Authorization: Bearer {token}

Response:
{
  "data": [
    {
      "raid_id": 1,
      "raid_name": "Trail des Vikings",
      "raid_start_date": "2026-06-15",
      "raid_location": "Chamonix",
      "races_count": 5,
      "min_age": 12,
      "max_age": 99
    }
  ]
}
```

#### Create Raid
```http
POST /api/raids
Authorization: Bearer {token}
Content-Type: application/json

{
  "raid_name": "New Viking Trail",
  "raid_start_date": "2026-07-01",
  "raid_location": "Annecy",
  "raid_gps_lat": 45.8992,
  "raid_gps_long": 6.1294,
  "type_id": 1,
  "diff_id": 2,
  "club_id": 5
}
```

### Races

#### List Races
```http
GET /api/races?raid_id=1
Authorization: Bearer {token}
```

#### Create Race
```http
POST /api/races
Authorization: Bearer {token}
Content-Type: application/json

{
  "race_name": "Trail 20km",
  "race_start_date": "2026-06-15 09:00:00",
  "race_length": 20.5,
  "race_max_num_runner": 200,
  "race_min_num_team_members": 1,
  "race_max_num_team_members": 5,
  "race_price": 25.00,
  "race_time_limit": "04:00:00",
  "raid_id": 1,
  "type_id": 1,
  "diff_id": 2
}
```

### Teams

#### Create Team
```http
POST /api/teams
Authorization: Bearer {token}
Content-Type: application/json

{
  "team_name": "Viking Runners",
  "race_id": "uuid-here",
  "club_id": 5
}
```

### Clubs

#### List Clubs
```http
GET /api/clubs
Authorization: Bearer {token}

Response:
{
  "data": [
    {
      "club_id": 1,
      "club_name": "Chamonix Trail Club",
      "club_address": "123 Mountain Road",
      "club_mail": "contact@chamonix-trail.fr"
    }
  ]
}
```

## 🧪 Testing

### Test Suite Overview

**Total Tests**: 45  
**Test Coverage**: 100%  
**Framework**: PHPUnit + Pest

### Test Categories

#### Unit Tests (5 tests)
- Repository tests for Club, Race, Raid, Team
- Service layer tests

#### Feature Tests (40 tests)
- Authentication (4 tests)
- Password management (5 tests)
- Registration (2 tests)
- API endpoints for raids, races, teams, clubs (24 tests)
- Web routes (2 tests)

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run specific test method
php artisan test --filter test_users_can_authenticate

# Run with detailed output
php artisan test --verbose
```

### Test Accounts

See [CREDENTIALS.md](CREDENTIALS.md) for test account details.

Default test accounts:
- **Member**: `testuser` / `password`
- **Admin**: `superadmin` / `password`

## 📁 Project Structure

```
laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/          # API controllers
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── ClubController.php
│   │   │   │   ├── RaceController.php
│   │   │   │   ├── RaidController.php
│   │   │   │   └── TeamController.php
│   │   │   └── Web/          # Web controllers
│   │   │       ├── Auth/     # Authentication
│   │   │       ├── RaidController.php
│   │   │       ├── RaceController.php
│   │   │       ├── RunnerController.php
│   │   │       └── WelcomeController.php
│   │   ├── Requests/         # Form validation
│   │   │   ├── Auth/
│   │   │   ├── StoreRaidRequest.php
│   │   │   ├── StoreRaceRequest.php
│   │   │   └── ...
│   │   └── Resources/        # API resources
│   │       ├── RaidResource.php
│   │       ├── RaceResource.php
│   │       └── ...
│   ├── Models/              # Eloquent models
│   │   ├── AgeCategory.php
│   │   ├── Admin.php
│   │   ├── Club.php
│   │   ├── Difficulty.php
│   │   ├── JoinRace.php
│   │   ├── JoinTeam.php
│   │   ├── ManageRaid.php
│   │   ├── Member.php
│   │   ├── Race.php
│   │   ├── RaceAgeCategory.php
│   │   ├── RaceManager.php
│   │   ├── Raid.php
│   │   ├── Team.php
│   │   └── Type.php
│   ├── Repositories/        # Data access layer
│   │   ├── ClubRepository.php
│   │   ├── RaceRepository.php
│   │   ├── RaidRepository.php
│   │   └── TeamRepository.php
│   └── Services/            # Business logic
│       ├── AbstractService.php
│       ├── GeocodingService.php
│       ├── RaceService.php
│       ├── RaidService.php
│       ├── RunnerService.php
│       └── TeamService.php
├── config/                  # Configuration
│   ├── auth.php            # Authentication guards
│   ├── database.php
│   └── ...
├── database/
│   ├── factories/          # Model factories
│   │   ├── AdminFactory.php
│   │   ├── ClubFactory.php
│   │   ├── MemberFactory.php
│   │   ├── RaceFactory.php
│   │   ├── RaidFactory.php
│   │   ├── TeamFactory.php
│   │   └── ...
│   ├── migrations/         # Database migrations (25 files)
│   └── seeders/            # Database seeders
│       ├── DatabaseSeeder.php
│       ├── SqlFileSeeder.php
│       ├── AdminSeeder.php
│       ├── MemberSeeder.php
│       ├── RaidSeeder.php
│       ├── RaceSeeder.php
│       └── ...
├── resources/
│   ├── views/
│   │   ├── auth/           # Authentication views
│   │   ├── components/     # Reusable components
│   │   │   ├── layouts/
│   │   │   └── partials/
│   │   ├── dashboard/      # Admin dashboard
│   │   ├── pages/          # Main pages
│   │   │   ├── home.blade.php
│   │   │   ├── profile.blade.php
│   │   │   ├── raid/
│   │   │   └── runner/
│   │   └── public/         # Public-facing views
│   └── css/
│       └── app.css
├── routes/
│   ├── api.php             # API routes
│   ├── web.php             # Web routes
│   └── console.php
├── tests/
│   ├── Feature/            # Feature tests
│   └── Unit/               # Unit tests
├── .env                    # Environment configuration
├── composer.json           # PHP dependencies
├── package.json            # Node dependencies
├── phpunit.xml             # PHPUnit configuration
├── vite.config.js          # Vite configuration
├── tailwind.config.js      # Tailwind CSS configuration
├── CREDENTIALS.md          # Test accounts documentation
├── SEEDING_COMPLETE.md     # Seeding implementation guide
└── README.md               # This file
```

### Key Directories

#### `/app/Http/Controllers`
- **Api**: RESTful API endpoints with JSON responses
- **Web**: Traditional web routes returning Blade views
- **Auth**: Authentication logic (login, register, password)

#### `/app/Services`
- Business logic layer between controllers and models
- Handles complex queries, calculations, and operations
- Promotes code reuse and testability

#### `/app/Repositories`
- Data access layer abstracting database queries
- Implements repository pattern for flexibility
- Allows easy swapping of data sources

#### `/resources/views`
- **auth**: Login, registration, password reset
- **components**: Reusable Blade components
- **dashboard**: Admin control panel
- **pages**: Authenticated user pages
- **public**: Guest-accessible pages

## 🔐 Authentication & Authorization

### Dual Guard System

The application uses two authentication guards:

1. **Web Guard** (Members/Runners)
   - Guard name: `web`
   - Model: `App\Models\Member`
   - Routes: `/`, `/profile`, `/espace-coureur`
   - Login: `/login`

2. **Admin Guard** (Event Organizers)
   - Guard name: `admin`
   - Model: `App\Models\Admin`
   - Routes: `/dashboard/*`
   - Login: `/admin/login`

### Session Configuration

- Driver: `file`
- Location: `storage/framework/sessions/`
- Lifetime: 120 minutes

### Password Security

- Algorithm: bcrypt
- Rounds: 12
- All passwords hashed before storage

### Middleware

- `auth`: Requires web authentication
- `auth:admin`: Requires admin authentication
- `auth:sanctum`: API token authentication
- `guest`: Only for unauthenticated users

## 🎨 Frontend

### Technologies

- **Template Engine**: Blade (Laravel)
- **CSS Framework**: Tailwind CSS 3.x
- **Build Tool**: Vite
- **Icons**: Heroicons (via Blade components)

### Key Views

#### Public Pages
- **Home** (`public/home.blade.php`): Upcoming raids, featured events
- **Raids Index** (`pages/raid/index.blade.php`): Browse all raids
- **Raid Details** (`pages/raid/show.blade.php`): Single raid with races

#### Authenticated Pages
- **Runner Space** (`pages/runner/index.blade.php`): Personal dashboard
- **Profile** (`pages/profile.blade.php`): Edit account information

#### Admin Pages
- **Dashboard** (`dashboard/index.blade.php`): Admin control panel
- **Manage Raids**: Create/edit raids
- **Manage Races**: Create/edit races

### Components

Reusable Blade components in `resources/views/components/`:
- `layouts/app.blade.php`: Main application layout
- `layouts/guest.blade.php`: Guest layout
- `partials/navbar.blade.php`: Navigation bar
- `partials/hero-section.blade.php`: Hero section
- `partials/footer.blade.php`: Footer

## 🔧 Configuration

### Environment Variables

Key `.env` variables:

```env
APP_NAME="VIKAPP"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=GROUPE5
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
```

### Authentication Guards

Configured in `config/auth.php`:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'members',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],

'providers' => [
    'members' => [
        'driver' => 'eloquent',
        'model' => App\Models\Member::class,
    ],
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],
],
```

## 📊 Database Optimization

### Indexes

Comprehensive indexing strategy for performance:

#### Primary Indexes
- All primary keys indexed automatically
- UUIDs used for `race_id` and `team_id`

#### Foreign Key Indexes
- All foreign keys indexed for join performance
- Composite indexes on join tables

#### Search Indexes
- `raid_name`, `race_name`, `club_name`: Full-text search
- `raid_start_date`, `race_start_date`: Date range queries
- `raid_location`: Geographic search

#### Performance Indexes
- `(raid_id, race_start_date)`: Race ordering within raids
- `(race_id, team_name)`: Team lookup
- `(user_id, race_id)`: Participation queries

### Query Optimization

- Eager loading with `with()` to prevent N+1 queries
- Select only required columns
- Paginated results for large datasets
- Database-level aggregations (COUNT, SUM, etc.)

## 🚢 Deployment

### Production Checklist

1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer install --optimize-autoloader --no-dev
   ```

3. **Database Setup**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

4. **Asset Compilation**
   ```bash
   npm run build
   ```

5. **File Permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

6. **Queue Workers** (if using queues)
   ```bash
   php artisan queue:work --daemon
   ```

### Server Requirements

- PHP >= 8.2
- MySQL >= 8.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## 📈 Performance

### Optimization Strategies

1. **Database**: Indexed columns, eager loading, query caching
2. **Caching**: Config cache, route cache, view cache
3. **Assets**: Minified CSS/JS via Vite
4. **Sessions**: File-based sessions (fast for small-medium traffic)
5. **Lazy Loading**: Pagination for large datasets

### Monitoring

Recommended tools:
- Laravel Telescope (development)
- Laravel Horizon (queue monitoring)
- Sentry (error tracking)
- New Relic (APM)

## 🤝 Contributing

### Development Workflow

1. **Create feature branch**
   ```bash
   git checkout -b feature/new-feature
   ```

2. **Make changes following conventions**
   - PSR-12 coding standards
   - SOLID principles
   - Meaningful commit messages

3. **Run tests**
   ```bash
   php artisan test
   ```

4. **Submit pull request**

### Code Style

- Follow PSR-12
- Use type hints
- Write descriptive method names
- Document complex logic
- Keep methods small (< 20 lines)

### Naming Conventions

- **Models**: Singular, PascalCase (`Raid`, `Race`, `Team`)
- **Controllers**: PascalCase + Controller (`RaidController`)
- **Services**: PascalCase + Service (`RaidService`)
- **Routes**: Plural, kebab-case (`/raids`, `/teams`)
- **Views**: Singular, kebab-case (`raid-index.blade.php`)
- **Database**: Plural, snake_case (`vik_raids`, `vik_races`)

## 📄 License

This project is licensed under the MIT License.

## 🙏 Acknowledgments

- Laravel Framework Team
- Tailwind CSS Team
- All contributors and testers

---

**Version**: 1.0.0  
**Last Updated**: January 14, 2026  
**Test Status**: ✅ 45/45 Passing (100%)
