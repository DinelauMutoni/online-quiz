-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `online_quiz`;
USE `online_quiz`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL UNIQUE,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` varchar(255) NOT NULL,
    `is_admin` boolean DEFAULT false,
    `remember_token` varchar(100) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Quizzes table
CREATE TABLE IF NOT EXISTS `quizzes` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `time_limit` int DEFAULT NULL COMMENT 'Time limit in minutes',
    `is_active` boolean DEFAULT true,
    `start_date` timestamp NULL DEFAULT NULL,
    `end_date` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Questions table
CREATE TABLE IF NOT EXISTS `questions` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `question_text` varchar(255) NOT NULL,
    `type` enum('multiple_choice', 'true_false') NOT NULL,
    `points` int DEFAULT 1,
    `quiz_id` bigint(20) UNSIGNED NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Answers table
CREATE TABLE IF NOT EXISTS `answers` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `question_id` bigint(20) UNSIGNED NOT NULL,
    `answer_text` varchar(255) NOT NULL,
    `is_correct` boolean NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Quiz attempts table
CREATE TABLE IF NOT EXISTS `quiz_attempts` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) UNSIGNED NOT NULL,
    `quiz_id` bigint(20) UNSIGNED NOT NULL,
    `score` int DEFAULT 0,
    `started_at` timestamp NOT NULL,
    `completed_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
INSERT INTO `users` (`name`, `email`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES
('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', true, NOW(), NOW());

-- Insert sample quiz
INSERT INTO `quizzes` (`title`, `description`, `time_limit`, `is_active`, `created_at`, `updated_at`) VALUES
('Sample Quiz', 'This is a sample quiz to demonstrate the system.', 30, true, NOW(), NOW());

-- Insert sample questions
INSERT INTO `questions` (`question_text`, `type`, `points`, `quiz_id`, `created_at`, `updated_at`) VALUES
('What is the capital of France?', 'multiple_choice', 1, 1, NOW(), NOW()),
('Is the Earth flat?', 'true_false', 1, 1, NOW(), NOW());

-- Insert sample answers
INSERT INTO `answers` (`question_id`, `answer_text`, `is_correct`, `created_at`, `updated_at`) VALUES
(1, 'Paris', true, NOW(), NOW()),
(1, 'London', false, NOW(), NOW()),
(1, 'Berlin', false, NOW(), NOW()),
(1, 'Madrid', false, NOW(), NOW()),
(2, 'True', false, NOW(), NOW()),
(2, 'False', true, NOW(), NOW()); 