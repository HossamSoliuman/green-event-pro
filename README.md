# GreenEventPro

**Sustainable Event Management & Austrian UZ 62 Certification SaaS**

---

## 🌿 Overview

GreenEventPro is a multi-tenant SaaS platform for event organizers to:
- Input sustainability data across 10 modules (Mobility, Accommodation, Venue, Procurement, Catering, etc.)
- Automatically calculate **CO₂ footprint** with Austrian emission factors
- Auto-score events against **UZ 62 Green Meetings/Green Events** certification criteria
- Generate official **PDF reports** and the Green Events Austria checklist
- Track KPIs across multiple events

**Stack:** Laravel 11 · Blade · Tailwind CSS · Alpine.js · MySQL · DomPDF

---

## 🚀 Installation

### Requirements
- PHP 8.3+
- MySQL 8.0+
- Composer 2.x
- Node.js 20+ & npm

### Setup

```bash
# 1. Clone and install PHP dependencies
git clone <repo>
cd greenevents
composer install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
DB_DATABASE=greenevents
DB_USERNAME=root
DB_PASSWORD=yourpassword

# 4. Run migrations and seed demo data
php artisan migrate
php artisan db:seed

# 5. Install frontend assets (optional – CDN used by default)
npm install && npm run build

# 6. Start server
php artisan serve
```

### Demo Login
```
Email:    demo@greenevents.at
Password: password
```

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           RegisterController, LoginController
│   │   ├── Modules/        10 module controllers (Mobility, Catering, etc.)
│   │   ├── Reports/        CarbonReport, UZ62Report, Checklist
│   │   └── Organization/   Settings, Users, Billing
│   └── Middleware/
│       └── TenantMiddleware.php
├── Models/
│   ├── Organization.php    Multi-tenant root model
│   ├── Event.php           Core event with all relationships
│   ├── EventMobility.php   UZ 62 Module 1 (M1–M17)
│   ├── EventCatering.php   UZ 62 Module 6 (C1–C34, VK1–VK4)
│   └── ...                 All 13 module models
└── Services/
    ├── CarbonFootprintService.php   Full CO₂ calculator
    └── UZ62ScoringService.php       Full certification scoring engine

database/migrations/        18 migration files
resources/views/
├── layouts/app.blade.php   Main authenticated layout with sidebar
├── auth/                   Login + Register
├── dashboard/              KPI overview
├── events/                 CRUD + show with 13-tab module nav
│   └── modules/            10 module forms
├── reports/                UZ62 scorecard + CO₂ report (screen + PDF)
├── organization/           Settings, Users, Billing
└── analytics/              Multi-event comparison charts
```

---

## 🏗️ UZ 62 Modules

| Module | Criteria | Max Points |
|--------|----------|-----------|
| 1. Mobility | M1–M17 | 27.5 |
| 2. Accommodation | U1–U3 | 12 |
| 3. Venue (Building) | Va1–Va27 | 33 |
| 3b. Venue (Outdoor) | Vb1–Vb15 | 18 |
| 4. Procurement/Waste | B1–B33 | 32–35 |
| 5. Exhibitors | A1–A8 | 11 |
| 6. Catering | C1–C34, VK1–VK4 | 39.5 |
| 7. Communication | K1–K7 | 3.5–4.5 |
| 8. Social | S1–S13 | 13–16 |
| 9. Technology | T1–T5 | 4 |

**Passing threshold:** 28% of theoretically achievable points + ALL MUSS criteria passed

---

## 🔧 CO₂ Emission Factors

| Source | Factor |
|--------|--------|
| Car (avg) | 0.210 kg CO₂/km/person |
| Short-haul flight | 0.255 kg CO₂/km/person |
| Train (AT/DE) | 0.032 kg CO₂/km/person |
| AT electricity grid | 0.158 kg CO₂/kWh |
| Hotel night (avg) | 18.0 kg CO₂/night |
| Hotel night (eco-certified) | 8.5 kg CO₂/night |
| Standard catering | 7.0 kg CO₂/person/day |
| Vegetarian catering | 2.5 kg CO₂/person/day |
| Vegan catering | 1.7 kg CO₂/person/day |

---

## 💳 Subscription Plans

| Plan | Price | Events/Year | Users |
|------|-------|-------------|-------|
| Free | €0 | 2 | 1 |
| Starter | €49/mo | 5 | 1 |
| Professional | €149/mo | 25 | 5 |
| Enterprise | €399/mo | Unlimited | 20 |

---

## 📋 Key Implementation Notes

1. **MUSS Blocking:** If ANY MUSS criterion fails, certification is blocked regardless of SOLL points
2. **Auto-satisfy:** UZ 200 certified venues automatically get 15.5 points (Va criteria)
3. **Hybrid events:** M17 becomes MUSS when `events.is_hybrid = true`
4. **Regional definition:** Within ~150km of venue location (per UZ 62 footnotes)
5. **Points threshold:** 28% of *theoretically achievable* points (not total possible)
6. **Historical records:** Carbon and UZ62 score records are appended, never overwritten

---

## 📄 License

MIT License — Based on: Österreichisches Umweltzeichen UZ 62 v5.1 (July 2022)
