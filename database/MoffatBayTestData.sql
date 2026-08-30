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

USE MoffatBay;
DELETE FROM RolePermission;
DELETE FROM ContactMessage;
DELETE FROM Reservation;
DELETE FROM `User`;
DELETE FROM Permission;
DELETE FROM Role;

-- ====================================================
-- TEST DATA FOR MOFFAT BAY DATABASE
-- ====================================================


-- ----------------------------------------------------
-- Role Test Data
-- ----------------------------------------------------

INSERT INTO Role (Id, Name)
VALUES
    (1, 'Guest'),
    (2, 'Customer'),
    (3, 'Employee'),
    (4, 'Admin');


-- ----------------------------------------------------
-- Permission Test Data
-- ----------------------------------------------------

INSERT INTO Permission (Id, Name)
VALUES
    (1, 'View Reservations'),
    (2, 'Create Reservations'),
    (3, 'Edit Reservations'),
    (4, 'Delete Reservations'),
    (5, 'View Contact Messages'),
    (6, 'View Customer Profile'),
    (7, 'Employee Profile View');


-- ----------------------------------------------------
-- User Test Data
-- ----------------------------------------------------
-- Test password for these accounts can simply be treated
-- as "password" for development/testing purposes.
--
-- PasswordHash contains a bcrypt hash suitable for storing
-- instead of plain-text passwords.

INSERT INTO `User`
    (Id, Email, FirstName, LastName, PasswordHash, RoleId)
VALUES
    (
        1,
        'john.smith@gmail.com',
        'John',
        'Smith',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
        2
    ),
    (
        2,
        'sarah.johnson@gmail.com',
        'Sarah',
        'Johnson',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
        3
    ),
    (
        3,
        'michael.admin@gmail.com',
        'Michael',
        'Anderson',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
        4
    );


-- ----------------------------------------------------
-- Reservation Test Data
-- ----------------------------------------------------

INSERT INTO Reservation
    (
        Id,
        UserId,
        ConfirmationNumber,
        RoomType,
        CheckIn,
        CheckOut,
        GuestCount,
        QuotedPrice,
        SpecialRequests
    )
VALUES
    (
        1,
        1,
        'MBR-100001',
        'double full beds',
        '2026-09-10',
        '2026-09-13',
        2,
        360.00,
        'Late check-in requested.'
    ),
    (
        2,
        2,
        'MBR-100002',
        'queen',
        '2026-10-05',
        '2026-10-06',
        2,
        135.00,
        'Guest requests a room with a bay view.'
    ),
    (
        3,
        3,
        'MBR-100003',
        'double queen beds',
        '2026-11-20',
        '2026-11-27',
        4,
        1050.00,
        'Please provide extra towels and pillows.'
    );


-- ----------------------------------------------------
-- ContactMessage Test Data
-- ----------------------------------------------------

INSERT INTO ContactMessage
    (
        Id,
        UserId,
        FullName,
        Email,
        Phone,
        Status,
        Subject,
        Message
    )
VALUES
    (
        1,
        1,
        'John Smith',
        'john.smith@gmail.com',
        '555-123-4567',
        'New',
        'Reservation Question',
        'I would like to know if early check-in is available for my upcoming reservation.'
    ),
    (
        2,
        2,
        'Sarah Johnson',
        'sarah.johnson@gmail.com',
        '555-234-5678',
        'In Progress',
        'Room Availability',
        'Are there any family suites available during the first week of December?'
    ),
    (
        3,
        3,
        'Michael Anderson',
        'michael.admin@gmail.com',
        NULL,
        'Resolved',
        'Website Feedback',
        'The reservation page is working well. I am submitting this message as a test of the contact form.'
    );


-- ----------------------------------------------------
-- RolePermission Test Data
-- ----------------------------------------------------

-- Role IDs
-- 1 = Guest
-- 2 = Customer
-- 3 = Employee
-- 4 = Admin
--
-- Permission IDs
-- 1 = View Reservations
-- 2 = Create Reservations
-- 3 = Edit Reservations
-- 4 = Delete Reservations
-- 5 = View Contact Messages
-- 6 = View Customer Profile
-- 7 = Employee Profile View


-- Guest permissions
INSERT INTO RolePermission (RoleId, PermissionId)
VALUES
    (1, 1),  -- View reservations
    (1, 2);  -- Create reservations


-- Customer permissions
INSERT INTO RolePermission (RoleId, PermissionId)
VALUES
    (2, 1),  -- View reservations
    (2, 2),  -- Create reservations
    (2, 3),  -- Edit reservations
    (2, 6);  -- View customer profile


-- Employee permissions
INSERT INTO RolePermission (RoleId, PermissionId)
VALUES
    (3, 1),  -- View reservations
    (3, 2),  -- Create reservations
    (3, 3),  -- Edit reservations
    (3, 5),  -- View contact messages
    (3, 6),  -- View customer profile
    (3, 7);  -- Employee profile view


-- Admin permissions
INSERT INTO RolePermission (RoleId, PermissionId)
VALUES
    (4, 1),  -- View reservations
    (4, 2),  -- Create reservations
    (4, 3),  -- Edit reservations
    (4, 4),  -- Delete reservations
    (4, 5),  -- View contact messages
    (4, 6),  -- View customer profile
    (4, 7);  -- Employee profile view


-- ====================================================
-- OPTIONAL: VERIFY TEST DATA
-- ====================================================

SELECT * FROM Role;
SELECT * FROM Permission;
SELECT * FROM `User`;
SELECT * FROM Reservation;
SELECT * FROM ContactMessage;
SELECT * FROM RolePermission;
