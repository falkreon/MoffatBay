USE MoffatBay;

-- ====================================================
-- TEST DATA FOR MOFFAT BAY DATABASE
-- ====================================================

-- ----------------------------------------------------
-- Role Test Data
-- ----------------------------------------------------

INSERT INTO Role (Name)
VALUES
    ('Guest'),
    ('Customer'),
    ('Employee'),
    ('Admin');


-- ----------------------------------------------------
-- Permission Test Data
-- ----------------------------------------------------

INSERT INTO Permission (Name)
VALUES
    ('View Reservations'),
    ('Create Reservations'),
    ('Edit Reservations'),
    ('Delete Reservations'),
    ('View Contact Messages'),
    ('View Customer Profile'),
    ('Employee Profile View');
    


-- ----------------------------------------------------
-- User Test Data
-- ----------------------------------------------------
-- Test password for these accounts can simply be treated
-- as "password" for development/testing purposes.
--
-- PasswordHash contains a bcrypt hash suitable for storing
-- instead of plain-text passwords.

INSERT INTO `User`
    (Email, FirstName, LastName, PasswordHash, RoleId)
VALUES
    (
        'john.smith@gmail.com',
        'John',
        'Smith',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
        2
    ),
    (
        'sarah.johnson@gmail.com',
        'Sarah',
        'Johnson',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
        3
    ),
    (
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
        'John Smith',
        'john.smith@gmail.com',
        '555-123-4567',
        'New',
        'Reservation Question',
        'I would like to know if early check-in is available for my upcoming reservation.'
    ),
    (
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

-- Guest permissions
INSERT INTO RolePermission (RoleId, PermissionId)
VALUES
    (1, 1),  -- Guest can view reservations
    (1, 2);  -- Guest can create reservations


-- Employee permissions
INSERT INTO RolePermission (RoleId, PermissionId)
VALUES
    (2, 1),  -- View reservations
    (2, 2),  -- Create reservations
    (2, 3),  -- Edit reservations
    (2, 5);  -- View contact messages


-- Admin permissions
INSERT INTO RolePermission (RoleId, PermissionId)
VALUES
    (3, 1),  -- View reservations
    (3, 2),  -- Create reservations
    (3, 3),  -- Edit reservations
    (3, 4),  -- Delete reservations
    (3, 5),  -- View contact messages
    (3, 6);  -- Manage users


-- ====================================================
-- OPTIONAL: VERIFY TEST DATA
-- ====================================================

SELECT * FROM Role;
SELECT * FROM Permission;
SELECT * FROM `User`;
SELECT * FROM Reservation;
SELECT * FROM ContactMessage;
SELECT * FROM RolePermission;