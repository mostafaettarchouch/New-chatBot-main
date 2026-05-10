-- Moroccan Legal Chatbot Database Schema
-- Created: 2025-05-09

USE legal_chatbot;

-- Languages table
CREATE TABLE IF NOT EXISTS languages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    code VARCHAR(5) NOT NULL UNIQUE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Legal Categories table
CREATE TABLE IF NOT EXISTS legal_categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    language_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Legal Procedures table
CREATE TABLE IF NOT EXISTS legal_procedures (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    summary TEXT,
    legal_category_id BIGINT UNSIGNED NOT NULL,
    language_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (legal_category_id) REFERENCES legal_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Procedure Steps table
CREATE TABLE IF NOT EXISTS procedure_steps (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    legal_procedure_id BIGINT UNSIGNED NOT NULL,
    step_number INT UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (legal_procedure_id) REFERENCES legal_procedures(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Keywords table
CREATE TABLE IF NOT EXISTS keywords (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    word VARCHAR(255) NOT NULL,
    weight INT DEFAULT 1,
    legal_procedure_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (legal_procedure_id) REFERENCES legal_procedures(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Questions table
CREATE TABLE IF NOT EXISTS questions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    text TEXT NOT NULL,
    language_id BIGINT UNSIGNED NOT NULL,
    matched_response TEXT,
    confidence_score DOUBLE DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Unanswered Questions table
CREATE TABLE IF NOT EXISTS unanswered_questions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    question_id BIGINT UNSIGNED,
    text TEXT NOT NULL,
    language_id BIGINT UNSIGNED NOT NULL,
    resolved BOOLEAN DEFAULT FALSE,
    resolved_by INT UNSIGNED,
    notes TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE SET NULL,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users table (for admin)
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Personal Access Tokens table (Sanctum)
CREATE TABLE IF NOT EXISTS personal_access_tokens (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities LONGTEXT,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX (tokenable_type, tokenable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default language (Arabic)
INSERT INTO languages (name, code, is_default, created_at, updated_at) 
VALUES ('العربية', 'ar', TRUE, NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Insert legal category
INSERT INTO legal_categories (name, description, language_id, created_at, updated_at)
SELECT 'إجراءات حكومية', 'إجراءات حكومية عامة', id, NOW(), NOW() 
FROM languages WHERE code = 'ar'
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Insert sample legal procedure (Passport)
INSERT INTO legal_procedures (title, description, summary, legal_category_id, language_id, created_at, updated_at)
SELECT 'كيفية الحصول على جواز سفر', 
       'هذا الإجراء يشرح كيفية الحصول على جواز سفر جديد أو تجديده في المغرب',
       'تقديم طلب الحصول على جواز سفر مع المتطلبات المطلوبة',
       lc.id, l.id, NOW(), NOW()
FROM languages l
JOIN legal_categories lc ON lc.language_id = l.id
WHERE l.code = 'ar' AND lc.name = 'إجراءات حكومية'
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Insert procedure steps for passport
INSERT INTO procedure_steps (legal_procedure_id, step_number, description, created_at, updated_at)
SELECT lp.id, 1, 'توجه إلى المصلحة المحلية لمكتب الشرطة مع جميع الوثائق المطلوبة', NOW(), NOW()
FROM legal_procedures lp
WHERE lp.title = 'كيفية الحصول على جواز سفر'
LIMIT 1;

INSERT INTO procedure_steps (legal_procedure_id, step_number, description, created_at, updated_at)
SELECT lp.id, 2, 'ادفع الرسوم المالية واحصل على الجواز بعد التحقق من البيانات', NOW(), NOW()
FROM legal_procedures lp
WHERE lp.title = 'كيفية الحصول على جواز سفر'
LIMIT 1;

-- Insert keywords for passport procedure
INSERT INTO keywords (word, weight, legal_procedure_id, created_at, updated_at)
SELECT 'جواز سفر', 3, lp.id, NOW(), NOW()
FROM legal_procedures lp
WHERE lp.title = 'كيفية الحصول على جواز سفر'
LIMIT 1;

INSERT INTO keywords (word, weight, legal_procedure_id, created_at, updated_at)
SELECT 'حصول', 2, lp.id, NOW(), NOW()
FROM legal_procedures lp
WHERE lp.title = 'كيفية الحصول على جواز سفر'
LIMIT 1;

INSERT INTO keywords (word, weight, legal_procedure_id, created_at, updated_at)
SELECT 'سفر', 1, lp.id, NOW(), NOW()
FROM legal_procedures lp
WHERE lp.title = 'كيفية الحصول على جواز سفر'
LIMIT 1;

-- Insert admin user (password: password123 - hashed with bcrypt)
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES ('Admin', 'admin@example.com', '$2y$12$9DnP6.Y8P1u1Y1m.uX9oY.lZKhq3Y8P1u1Y1m.uX9oY.lZKhq3Y8P', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();
