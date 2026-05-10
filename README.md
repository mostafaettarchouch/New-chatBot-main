# Moroccan Legal Assistant Chatbot

A professional, government-style informational chatbot designed to help Moroccan citizens understand legal procedures (e.g., Passport issuance, Marriage procedures, etc.) through an interactive, multi-language interface.

## 🚀 Features
- **Arabic NLP Matching**: Token-based keyword matching with confidence scoring.
- **Multi-language Support**: Supports Arabic (default), French, and English.
- **RTL Support**: Full Right-to-Left layout for Arabic.
- **Admin Dashboard**: Manage procedures, keywords, and review unanswered questions.
- **Portable Setup**: Configured with SQLite for immediate testing without MySQL.

---

## 🛠️ Prerequisites
Ensure you have the following installed on your system:
- **PHP 8.2+** (with `pdo_sqlite` extension enabled)
- **Node.js 18+** & **npm**
- **Composer** (PHP dependency manager)

---

## 📦 Installation & Setup

### 1. Backend Setup (Laravel)
The backend is the core engine that processes chat messages and manages the database.

```bash
cd backend
# Install PHP dependencies
php composer.phar install

# Configure environment
# Ensure .env has: DB_CONNECTION=sqlite
php artisan key:generate

# Initialize SQLite database
# On Windows (PowerShell):
New-Item -Path database/database.sqlite -ItemType File
# On Linux/Mac:
touch database/database.sqlite

# Run migrations and seed sample data (Passport procedure)
php artisan migrate --seed
```

### 2. Frontend Setup (Citizen Chat)
The main interface where users interact with the chatbot.

```bash
cd frontend
npm install
```

### 3. Admin Panel Setup
The dashboard for managing legal data and reviewing user questions.

```bash
cd admin-panel
npm install
```

---

## 🏃 How to Run the Project
You will need to run three separate terminals:

### Terminal 1: Backend API
```bash
cd backend
php artisan serve --port=8000
```
*Accessible at: http://localhost:8000*

### Terminal 2: Citizen Frontend
```bash
cd frontend
npm run dev -- --port 5173
```
*Accessible at: http://localhost:5173*

### Terminal 3: Admin Panel
```bash
cd admin-panel
npm run dev -- --port 5174
```
*Accessible at: http://localhost:5174*

---

## 🔐 Admin Credentials
To access the Admin Panel ([http://localhost:5174](http://localhost:5174)):
- **Email**: `admin@example.com`
- **Password**: `password123`

---

## 🧪 Testing the Chatbot
1. Open the Frontend ([http://localhost:5173](http://localhost:5173)).
2. Type **"جواز سفر"** (Passport) in the chat.
3. The chatbot should respond with specific steps, requirements, and documents needed for a Moroccan passport.

---

## ⚠️ Troubleshooting & Notes
- **Database**: This project is currently configured to use **SQLite** for ease of testing. If you wish to use **MySQL**, update the `DB_CONNECTION` in `backend/.env`.
- **Encoding**: Ensure all PHP files are saved in **UTF-8 without BOM** to avoid "Namespace declaration" errors.
- **Missing Folders**: Ensure `bootstrap/cache` and `storage/framework` folders exist in the backend (these are required by Laravel).
- **API URL**: If you change the backend port, update the `baseURL` in `frontend/src/services/api.js` and `admin-panel/src/services/api.js`.
