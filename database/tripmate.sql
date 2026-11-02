CREATE DATABASE tripmate;
USE tripmate;

-- 1. USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. TRIPS
CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    destination VARCHAR(150) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    budget DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    currency VARCHAR(3) NOT NULL DEFAULT 'BAM',
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. DESTINATIONS
CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    country VARCHAR(100),
    FOREIGN KEY (trip_id) REFERENCES trips(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. ACTIVITIES
CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    destination_id INT,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    activity_date DATE NOT NULL,
    start_time TIME,
    FOREIGN KEY (trip_id) REFERENCES trips(id)
        ON DELETE CASCADE,
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. EXPENSES
CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) NOT NULL DEFAULT 'BAM',
    expense_date DATE NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (trip_id) REFERENCES trips(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. PACKING ITEMS
CREATE TABLE packing_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    item_name VARCHAR(150) NOT NULL,
    is_packed BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (trip_id) REFERENCES trips(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- SAMPLE DATA

INSERT INTO users (name, email, password_hash, role)
VALUES (
    'Test User',
    'test@tripmate.com',
    'REPLACE_WITH_BCRYPT_HASH',
    'user'
);

INSERT INTO trips (
    user_id, name, destination, start_date, end_date,
    budget, currency, description
)
VALUES (
    1, 'Summer in Italy', 'Rome, Italy',
    '2027-07-10', '2027-07-17',
    1500.00, 'EUR', 'A summer holiday in Italy'
);

INSERT INTO destinations (trip_id, name, country)
VALUES
    (1, 'Rome', 'Italy'),
    (1, 'Florence', 'Italy');

INSERT INTO activities (
    trip_id, destination_id, title, description,
    activity_date, start_time
)
VALUES
    (1, 1, 'Visit the Colosseum', 'Explore the historic landmark',
     '2027-07-11', '10:00:00'),
    (1, 2, 'Visit the Uffizi Gallery', 'Explore the art collection',
     '2027-07-14', '11:00:00');

INSERT INTO expenses (
    trip_id, category, amount, currency, expense_date, description
)
VALUES
    (1, 'Accommodation', 300.00, 'EUR', '2027-07-10', 'Hotel booking'),
    (1, 'Food', 45.00, 'EUR', '2027-07-11', 'Lunch');

INSERT INTO packing_items (trip_id, item_name, is_packed)
VALUES
    (1, 'Passport', TRUE),
    (1, 'Clothes', FALSE),
    (1, 'Toothbrush', FALSE);


