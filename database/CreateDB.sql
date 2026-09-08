/*
 * CSD 460: Capstone in Software Development
 * Module 4: Database
 * Gold Team
 *   Isaac Ellingson
 *   Patrice Moracchini
 *   Cannon Rivera
 *   José Velázquez Sáenz
 * 8/30/2026
 */

-- ----------------------------------------------------
-- Create Database
-- ----------------------------------------------------
DROP DATABASE IF EXISTS MoffatBay;
CREATE DATABASE MoffatBay;
USE MoffatBay;

-- ----------------------------------------------------
-- Reference Tables
-- ----------------------------------------------------

-- Role table
CREATE TABLE Role (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL UNIQUE
);

-- Permission table
CREATE TABLE Permission (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE RoomType (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL UNIQUE,
    Description VARCHAR(128) NULL DEFAULT NULL,
    MaxGuests INT NOT NULL DEFAULT 1,
    NightlyRate DECIMAL(10,2) NOT NULL,
    Active BOOLEAN NOT NULL DEFAULT TRUE
);

-- ----------------------------------------------------
-- Table With Foreign Keys
-- ----------------------------------------------------

-- User Table
CREATE TABLE User (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(100) NOT NULL UNIQUE,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    PhoneNumber VARCHAR(14) NOT NULL,
    PasswordHash VARCHAR(255) NOT NULL,
    CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    RoleId INT NOT NULL,
    FOREIGN KEY (RoleId) REFERENCES Role(Id)
);

-- Reservation Table
CREATE TABLE Reservation (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    UserId INT NOT NULL,
    ConfirmationNumber VARCHAR(50) NOT NULL UNIQUE,
    RoomTypeId INT NOT NULL,
    CheckIn DATE NOT NULL,
    CheckOut DATE NOT NULL,
    GuestCount INT NOT NULL,
    QuotedPrice DECIMAL(10, 2) NOT NULL,
    CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    SpecialRequests TEXT NULL,
    FOREIGN KEY (UserId) REFERENCES User(Id),
    FOREIGN KEY (RoomTypeId) REFERENCES RoomType(Id)
);

-- ContactMessage Table
CREATE TABLE ContactMessage (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    UserId INT NOT NULL,
    FullName VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Phone VARCHAR(20) NULL,
    CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Status VARCHAR(20) NOT NULL DEFAULT 'New',
    Subject VARCHAR(100) NOT NULL,
    Message TEXT NOT NULL,
    FOREIGN KEY (UserId) REFERENCES User(Id)
);

-- ----------------------------------------------------
-- Junction Table
-- ----------------------------------------------------

-- RolePermission table
CREATE TABLE RolePermission (
    RoleId INT NOT NULL,
    PermissionId INT NOT NULL,
    PRIMARY KEY (RoleId, PermissionId),
    FOREIGN KEY (RoleId) REFERENCES Role(Id),
    FOREIGN KEY (PermissionId) REFERENCES Permission(Id)
);
