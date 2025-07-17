-- Dental Practice Pay Survey Database Schema
-- Run this SQL in your MySQL database to create the required table

CREATE DATABASE IF NOT EXISTS dental_pay_survey;
USE dental_pay_survey;

-- Create the pay_surveys table
CREATE TABLE IF NOT EXISTS pay_surveys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    role ENUM('Dentist','Hygienist','Assistant','Office Manager','Lab Tech','Other') NOT NULL,
    state VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    practice VARCHAR(150) NULL,
    hourly_rate DECIMAL(6,2) NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Indexes for better query performance
    INDEX idx_state_country (state, country),
    INDEX idx_role (role),
    INDEX idx_submitted_at (submitted_at)
);
