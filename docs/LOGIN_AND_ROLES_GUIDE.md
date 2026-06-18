# Topper's Hope — Login & Roles Guide (Personal Reference)

**Project:** Topper's Hope Website (Laravel)  
**Production seed (Super Admin only):** `admin@toppershope.com` / `Admin@123` (override with `ADMIN_SEED_PASSWORD` in `.env`)  
**Base URL (local):** `http://127.0.0.1:8000` (replace with your `APP_URL` from `.env`)

Run `php artisan migrate:fresh --seed` on a new database to create schema + default admin + master data (categories, departments, leave types, roles).

---

## 1. Quick summary — who logs in where?

| Role / Panel | Purpose | Login URL | Email | Password |
|--------------|---------|-----------|-------|----------|
| **Super Admin** | Full owner control: revenue, branches, HR users, staff registry, academic overview, access control, audit logs | `/admin/login` | `admin@toppershope.com` | `Admin@123` |
| **HR** | Employees, attendance, leave, payroll, recruitment, documents | `/hr/login` | `hr@gmail.com` | `Admin@123` |
| **Faculty** | Teach assigned courses: content, quizzes, doubts, live meetings | `/faculty/login` | `faculty@gmail.com` | `Admin@123` |
| **Faculty Head** | Everything faculty can do + manage courses, batches, faculty assignments, all students | `/faculty/login` | `facultyhead@gmail.com` | `Admin@123` |
| **Student** | Buy courses, watch lectures, quizzes, doubts, profile | `/login` | `student@gmail.com` | `Admin@123` |
| **Ads** | Marketing campaigns, homepage popup, ad leads | `/ads/login` | `ads@gmail.com` | `Admin@123` |
| **Admission** | CRM: leads, calls, trial access for prospects | `/admission/login` | `admission@gmail.com` | `Admin@123` |
| **Trial student** | Short preview access (issued by Admission team) | `/trial/login` | *trial email from admission* | *set when trial is issued* |

**Public website (no login):** `/` — homepage, categories, course pages, FAQ, contact.

---

## 2. How authentication works in this project

The app uses **separate login pages** and **separate database tables** for most staff panels. Do not use the student `/login` page for HR, Admin, or Faculty.

```
┌─────────────────┬──────────────────┬─────────────────────────────┐
│ Panel           │ Guard / Table    │ After login goes to         │
├─────────────────┼──────────────────┼─────────────────────────────┤
│ Student         │ web → users      │ /student/dashboard          │
│ Faculty         │ web → users      │ /faculty/dashboard          │
│ Super Admin     │ admin → admins   │ /admin/dashboard            │
│ HR              │ hr → hr_users    │ /hr/dashboard               │
│ Ads             │ ads → ads_users  │ /ads/dashboard              │
│ Admission       │ admission →      │ /admission/dashboard        │
│                 │ admission_users  │                             │
│ Trial           │ trial →          │ /trial/dashboard            │
│                 │ trial_accesses   │                             │
└─────────────────┴──────────────────┴─────────────────────────────┘
```

### Student (`users` table)
- Register at `/register` or sign in at `/login`.
- Column `users.role` is usually `student`.
- Optional legacy link: `user_role` + `roles` tables (RBAC).

### Faculty (`users` table)
- Must have `role` = `faculty` or `faculty_head`.
- Sign in only at **`/faculty/login`** (not `/login`).

### Super Admin (`admins` table)
- Separate from `users` table.
- Account must have `is_super_admin = true`.
- Sign in at **`/admin/login`**.

### HR (`hr_users` table)
- Sign in at **`/hr/login`**.
- Account must be `is_active = true`.

---

## 3. What each panel is for (detail)

### Super Admin — `/admin`
- **Purpose:** Business owner / platform administrator.
- **Typical tasks:**
  - View revenue and expenses
  - Manage branches
  - Create HR panel users
  - View all staff (faculty, ads, admission)
  - Global courses & batches overview
  - Force password reset, session control
  - Audit logs
- **Dashboard:** `/admin/dashboard`

### HR — `/hr`
- **Purpose:** Human resources and internal staff operations.
- **Typical tasks:**
  - Departments & designations
  - Employee records (can sync from other panels)
  - Attendance, leave, payroll
  - KPIs, performance reviews
  - Job postings & applications
- **Dashboard:** `/hr/dashboard`

### Faculty — `/faculty`
- **Purpose:** Teachers delivering course content.
- **Typical tasks:**
  - Upload videos & notes
  - Build & publish quizzes
  - Answer student doubts
  - Schedule Google Meet sessions
  - View assigned batch students
- **Faculty Head extra:** `/faculty/head/courses`, batches, assign faculty, all students list.

### Student — `/student`
- **Purpose:** Learners who enroll in batches.
- **Typical tasks:**
  - Browse catalog (`/student/courses`)
  - Checkout & enroll
  - Watch content (`/student/my-courses`)
  - Attempt quizzes, raise doubts
- **Dashboard:** `/student/dashboard`

### Ads — `/ads`
- **Purpose:** Digital marketing team.
- **Typical tasks:**
  - Create landing page campaigns (`/c/{slug}`)
  - Manage global homepage popup
  - View leads from campaigns

### Admission — `/admission`
- **Purpose:** Sales / counselling team.
- **Typical tasks:**
  - Manage contacts & call status
  - Add remarks
  - Issue **trial access** (creates trial login for prospect)
  - Sync leads from ads / registered users

### Trial — `/trial`
- **Purpose:** Prospective students with time-limited preview.
- **Login:** Uses `trial_email` + password (not normal email).
- **Created by:** Admission panel when issuing a trial.

---

## 4. How to create your login accounts

### Option A — Run the seeder (recommended)

From the project folder:

```bash
php artisan db:seed --class=DevLoginAccountsSeeder
```

This creates/updates all emails in the table above with password **`Admin@123`**.

### Option B — Create manually

| Panel | How to create |
|-------|----------------|
| Super Admin | Insert into `admins` table, or use Admin panel → HR Users if you already have access |
| HR | Super Admin → HR Users → Create |
| Faculty | Super Admin → Staff registry, or Faculty Head → assign faculty user in `users` with `role=faculty` |
| Student | Public `/register` or insert into `users` with `role=student` |
| Ads / Admission | Usually created via HR employee sync or direct DB insert into `ads_users` / `admission_users` |

**Password hashing:** Always store bcrypt hash in Laravel:

```php
use Illuminate\Support\Facades\Hash;
Hash::make('Admin@123');
```

---

## 5. Full login URL list (copy-paste)

Assume base URL = `http://127.0.0.1:8000`

| Panel | Login page | Dashboard after login |
|-------|------------|------------------------|
| Student | http://127.0.0.1:8000/login | http://127.0.0.1:8000/student/dashboard |
| Register (new student) | http://127.0.0.1:8000/register | — |
| Faculty | http://127.0.0.1:8000/faculty/login | http://127.0.0.1:8000/faculty/dashboard |
| Super Admin | http://127.0.0.1:8000/admin/login | http://127.0.0.1:8000/admin/dashboard |
| HR | http://127.0.0.1:8000/hr/login | http://127.0.0.1:8000/hr/dashboard |
| Ads | http://127.0.0.1:8000/ads/login | http://127.0.0.1:8000/ads/dashboard |
| Admission | http://127.0.0.1:8000/admission/login | http://127.0.0.1:8000/admission/dashboard |
| Trial | http://127.0.0.1:8000/trial/login | http://127.0.0.1:8000/trial/dashboard |
| Public home | http://127.0.0.1:8000/ | — |

---

## 6. Common mistakes

1. **Faculty using `/login`** — Will fail or redirect to student area. Use **`/faculty/login`**.
2. **HR using `/admin/login`** — HR accounts are in `hr_users`, not `admins`. Use **`/hr/login`**.
3. **Student password on wrong URL** — Students use **`/login`** only.
4. **Trial login** — Uses `trial_email` field, not the person's normal Gmail.
5. **Two role systems** — Some older code uses `roles` + `user_role` tables; faculty/admin checks often use `users.role` column. Seeder sets both where needed for students.

---

## 7. Project structure (high level)

```
toppershope-website/
├── app/Http/Controllers/     # Business logic
├── app/Models/               # User, Course, Batch, Admin, HR, Ads, Admission
├── resources/views/          # Blade UI (welcome, student, faculty, admin, hr, ads)
├── routes/web.php            # Public + faculty + ads + admission + trial
├── routes/admin.php          # Super Admin panel
├── routes/hr.php             # HR panel
├── routes/student.php        # Student panel
├── database/seeders/         # DevLoginAccountsSeeder.php
└── docs/                     # This guide
```

---

## 8. Start the app locally

```bash
cd "d:\Lama Projects\toppershope-website"
composer install
cp .env.example .env   # if needed
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DevLoginAccountsSeeder
php artisan serve
```

Open: http://127.0.0.1:8000

---

## 9. Security note (personal / dev only)

- **`Admin@123` is for local development only.**
- Do not use these emails/passwords on production.
- Change all passwords before going live.

---

*Generated for personal reference — Topper's Hope Website.*
