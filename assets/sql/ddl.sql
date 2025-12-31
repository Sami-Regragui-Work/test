-- Active: 1766926269402@@127.0.0.1@3306@UCCV3
DROP DATABASE IF EXISTS UCCV3;

CREATE DATABASE UCCV3;

USE UCCV3;

CREATE TABLE `users` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `role` enum('admin', 'doctor', 'patient') NOT NULL,
    `username` varchar(50) UNIQUE NOT NULL,
    `email` varchar(100) UNIQUE NOT NULL,
    `password_hash` varchar(255) NOT NULL,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `phone` varchar(20),
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `doctors` (
    `user_id` int PRIMARY KEY,
    `specialization` varchar(100),
    `department_id` int
);

CREATE TABLE `patients` (
    `user_id` int PRIMARY KEY,
    `gender` enum('M', 'F', 'Other') NULL,
    `date_of_birth` date,
    `address` text
);

CREATE TABLE `departments` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `location` varchar(255),
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `appointments` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `date` date NOT NULL,
    `time` time NOT NULL,
    `doctor_id` int NOT NULL,
    `patient_id` int NOT NULL,
    `reason` text,
    `status` enum( 'scheduled', 'done', 'cancelled' ) DEFAULT 'scheduled',
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `prescriptions` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `date` date NOT NULL,
    `doctor_id` int NOT NULL,
    `patient_id` int NOT NULL,
    `medication_id` int NOT NULL,
    `dosage_instructions` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `medications` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `instructions` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `doctors`
ADD FOREIGN KEY (`user_id`)
REFERENCES `users` (`id`);

ALTER TABLE `doctors`
ADD FOREIGN KEY (`department_id`)
REFERENCES `departments` (`id`);

ALTER TABLE `patients`
ADD FOREIGN KEY (`user_id`)
REFERENCES `users` (`id`);

ALTER TABLE `appointments`
ADD FOREIGN KEY (`doctor_id`)
REFERENCES `doctors` (`user_id`);

ALTER TABLE `appointments`
ADD FOREIGN KEY (`patient_id`)
REFERENCES `patients` (`user_id`);

ALTER TABLE `prescriptions`
ADD FOREIGN KEY (`doctor_id`)
REFERENCES `doctors` (`user_id`);

ALTER TABLE `prescriptions`
ADD FOREIGN KEY (`patient_id`)
REFERENCES `patients` (`user_id`);

ALTER TABLE `prescriptions`
ADD FOREIGN KEY (`medication_id`)
REFERENCES `medications` (`id`);