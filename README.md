# GuessMyRent

A web game where players estimate the monthly rent of real properties. The closer the guess, the higher the score.

## How it works

Each round, a property is shown (photos, city, surface area, type, floor, number of rooms…) and the player submits a rent estimate. The score is calculated as:

```
score = max(0, round(100 - abs(guess - rent) / rent * 100))
```

A perfect guess earns 100 points. Scores accumulate across games and feed a leaderboard. Guests can play one game without an account; registered users get full history tracking and leaderboard ranking.

## Stack

| Layer    | Technology                           |
|----------|--------------------------------------|
| Backend  | PHP 8+, MVC architecture (hand-rolled) |
| Database | MySQL                                |
| Frontend | Vanilla JS, SCSS (compiled via Sass) |
| Config   | `vlucas/phpdotenv`                   |
| Routing  | Custom `Router` class (no framework) |
| External API | Supabase (property data, read-only)  |

## Project structure

```
GmR/
├── app/
│   ├── controller/     # Controllers (Game, User, Estate, Message, Api, Page)
│   ├── model/          # Models with PDO queries
│   └── view/           # PHP templates per feature
│       ├── admin/      # Admin dashboard views
│       ├── auth/       # Login / register
│       ├── game/       # Play & result pages
│       ├── profil/     # User profile & history
│       └── skeleton/   # Header, footer, alerts
├── config/
│   ├── config.php      # Path constants
│   └── Router.php      # URL routing switch
├── data/
│   └── GmR.sql         # Database schema & seed
├── public/
│   ├── css/            # Compiled CSS
│   ├── sass/           # Source SCSS
│   ├── js/             # Client-side scripts
│   └── img/ audio/ vid/
└── index.php           # Entry point
```

## Installation

**Requirements:** PHP 8.1+, MySQL 8+, Composer, Node.js + npm

### 1. Clone & install dependencies

```bash
git clone https://github.com/Bourdours/GuessMyRent.git
cd GuessMyRent
composer install
npm install
```

### 2. Database

Import the schema and seed data:

```bash
mysql -u <user> -p <database_name> < data/GmR.sql
```

### 3. Environment

Create a `.env` file at the project root:

```env
DB_HOST=localhost
DB_NAME=your_database
DB_USER=your_user
DB_PASS=your_password
```

### 4. Web server

Point your virtual host document root to the project root (`index.php` is the entry point). All requests must be rewritten to it.

**Apache example (`.htaccess` at project root):**

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

### 5. Compile SCSS (optional, CSS already included)

```bash
npm run sass
```

## Routes

| URL                      | Description                        |
|--------------------------|------------------------------------|
| `/`                      | Home                               |
| `/jeu`                   | Play a game                        |
| `/regle`                 | Rules                              |
| `/info`                  | About                              |
| `/auth`                  | Login / register                   |
| `/profil`                | User profile                       |
| `/profil/history`        | Game history                       |
| `/contact`               | Contact form                       |
| `/contact/proposer`      | Submit a property                  |
| `/admin`                 | Admin dashboard (admin only)       |
| `/admin/biens`           | Manage properties                  |
| `/admin/biens/en-attente`| Review submitted properties        |
| `/admin/parties`         | View all games                     |
| `/admin/utilisateurs`    | Manage users                       |
| `/admin/api`             | API management                     |
| `/admin/messagerie`      | Messages                           |

## Security

- CSRF tokens on all state-changing forms
- Passwords hashed with `password_hash()`
- PDO prepared statements throughout
- Guest game sessions limited server-side (1 game max)

## Author

Gaetan Maillot — final project for developer certification.
