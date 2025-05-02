CREATE DATABASE ineedit_db;

USE ineedit_db;

CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_no VARCHAR(20) NOT NULL,
    location VARCHAR(100),
    address VARCHAR(255) NOT NULL,
    date_joined DATE NOT NULL,
    profile_pic_url VARCHAR(255),
    is_buyer BOOLEAN DEFAULT False,
    is_seller BOOLEAN DEFAULT False,
);

CREATE TABLE Buyer (
    user_id INT PRIMARY KEY,
    ship_address VARCHAR(255),
    num_orders INT DEFAULT 0
    FOREIGN KEY (user_id) REFERENCES User(user_id),
);

CREATE TABLE Seller (
    user_id INT PRIMARY KEY,
    verified VARCHAR(255),
    products_sold INT DEFAULT 0,
    date_registered DATE,
    FOREIGN KEY (user_id) REFERENCES User(user_id),
);

CREATE TABLE Product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    desc TEXT NOT NULL,
    price DECIMAL DEFAULT 0,
    quant_in_stock INT DEFAULT 0,
    colours VARCHAR(255),
    condition VARCHAR(50) NOT NULL,
    condition_details VARCHAR(255),
    pct_discount INT DEFAULT 0,
    views INT DEFAULT 0,
    avg_rating DECIMAL,
    category VARCHAR(100) NOT NULL,
    seller_id INT NOT NULL,
    FOREIGN KEY (seller_id) REFERENCES Seller(user_id),
);

CREATE TABLE ProductImage (
    img_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    img_url VARCHAR(255) NOT NULL,
    is_display_img BOOLEAN DEFAULT False,
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

CREATE TABLE Order (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    ship_address VARCHAR(255) NOT NULL,
    total DECIMAL NOT NULL,
);

CREATE TABLE OrderItem (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    order_id INT NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (product_id) REFERENCES Product(product_id),
    FOREIGN KEY (order_id) REFERENCES Order(order_id),
);