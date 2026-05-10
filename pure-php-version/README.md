# Moroccan Legal Assistant (Pure PHP Pro Edition)

A high-performance, lightweight version of the Legal Assistant Chatbot built using **Pure PHP 8.2**, **Vanilla JavaScript**, and **SQLite**. This version follows the professional standards of 2026, focusing on speed, clean UI/UX, and zero dependencies.

## 🏗️ Architecture (How it Works)

Unlike the main project which uses Laravel and React, this version is "Vanilla":
1.  **Backend (API)**: All logic is handled by `api.php`. It acts as a router that receives JSON requests, interacts with the SQLite database, and returns JSON responses.
2.  **Frontend**: Built with static HTML (`index.php`, `admin.php`) and enhanced with Vanilla JS (`app.js`, `admin.js`) using the `fetch` API.
3.  **Database**: Uses **SQLite**, a file-based database. No MySQL server installation is required.
4.  **State Management**: Uses native PHP `$_SESSION` for secure admin authentication.

## 🌟 Key Features
-   **Zero Dependencies**: No `node_modules`, no `vendor` folder. It runs instantly.
-   **Glassmorphism UI**: Modern transparent design with smooth CSS transitions.
-   **Interactive Dashboard**: Uses `Chart.js` for visual analytics.
-   **Full Admin CRUD**: Manage legal procedures and unanswered questions directly from the browser.
-   **Keyword Engine**: A lightweight NLP-like matching system for Arabic input.

## 📁 File Structure
-   `index.php`: Modern chat interface for citizens.
-   `admin.php`: Professional admin dashboard with tabs and charts.
-   `api.php`: The "Brain" - handles Chat, Login, Stats, and CRUD operations.
-   `setup_db.php`: One-time script to initialize the database.
-   `inc/db.php`: Reusable database connection logic.
-   `style.css`: Unified professional styling.

## 🚀 Getting Started

### 1. Initialize Database
Run the setup script once to create the database file and seed initial data:
```bash
php setup_db.php
```

### 2. Launch Server
Use the built-in PHP server to run the application:
```bash
php -S localhost:8080
```

### 3. Access the Apps
-   **Citizen Chat**: [http://localhost:8080/index.php](http://localhost:8080/index.php)
-   **Admin Panel**: [http://localhost:8080/admin.php](http://localhost:8080/admin.php)

## 🔐 Admin Credentials
-   **Email**: `admin@example.com`
-   **Password**: `password123`

## ⚖️ Comparison with Laravel/React Version
| Feature | Main Project | Pure PHP Version |
| :--- | :--- | :--- |
| **Setup Time** | 10-15 mins | < 1 min |
| **Performance** | High (Structured) | Ultra-Fast (Minimal overhead) |
| **Maintenance** | Easier for large teams | Easier for individual developers |
| **Security** | Framework-guaranteed | Custom-implemented |

---
*Created for Comparison & Performance Testing - 2026.*
