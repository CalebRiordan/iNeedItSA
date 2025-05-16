CREATE DATABASE IF NOT EXISTS ineedit_db;

USE ineedit_db;

-- Tables

CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_no VARCHAR(20) NOT NULL,
    location VARCHAR(100),
    province VARCHAR(50),
    address VARCHAR(255) NOT NULL,
    profile_pic_url VARCHAR(255),
    date_joined DATE NOT NULL,
    login_token VARCHAR(255),
    is_buyer BOOLEAN DEFAULT False,
    is_seller BOOLEAN DEFAULT False
);

CREATE TABLE buyer (
    user_id INT PRIMARY KEY,
    ship_address VARCHAR(255),
    num_orders INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

CREATE TABLE seller (
    user_id INT PRIMARY KEY,
    verified VARCHAR(255),
    products_sold INT DEFAULT 0,
    total_views INT DEFAULT 0,
    date_registered DATE,
    date_verified DATE,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

CREATE TABLE product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.0,
    quant_in_stock INT DEFAULT 0,
    product_condition VARCHAR(50) NOT NULL,
    condition_details VARCHAR(255),
    date_created DATE,
    pct_discount INT DEFAULT 0,
    views INT DEFAULT 0,
    avg_rating DECIMAL(2,1),
    category VARCHAR(100) NOT NULL,
    seller_id INT NOT NULL,
    FOREIGN KEY (seller_id) REFERENCES user(user_id) ON DELETE CASCADE 
    -- ON DELETE CASCADE for the scope of this university project
    -- Would not delete in a real-world database
);

CREATE TABLE product_image_url (
    img_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    img_url VARCHAR(255) NOT NULL,
    is_display_img BOOLEAN DEFAULT False,
    FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE CASCADE
);

CREATE TABLE review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_ID INT NOT NULL,
    comment VARCHAR(300),
    rating INT NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE CASCADE
);

CREATE TABLE `order` (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    date DATE NOT NULL,
    ship_address VARCHAR(255) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE SET NULL
);

CREATE TABLE order_item (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    order_id INT NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE SET NULL,
    FOREIGN KEY (order_id) REFERENCES `order`(order_id) ON DELETE CASCADE
);

CREATE TABLE community (
    comm_id INT AUTO_INCREMENT PRIMARY KEY,
    founder_id INT,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(300) NOT NULL,
    date_created DATE NOT NULL,
    FOREIGN KEY (founder_id) REFERENCES user(user_id) ON DELETE SET NULL
);

CREATE TABLE community_participation (
    participation_Id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    comm_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE, 
    FOREIGN KEY (comm_id) REFERENCES community(comm_id) ON DELETE CASCADE
);

CREATE TABLE employee (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    date_registered DATE NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone_no VARCHAR(20) NOT NULL,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE profile_picture (
    prof_pic_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    image BLOB NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

CREATE TABLE product_image (
    product_img_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image BLOB NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);


-- Triggers

DELIMITER //

CREATE TRIGGER update_avg_rating_after_insert
AFTER INSERT ON review
FOR EACH ROW
BEGIN
    UPDATE product
    SET avg_rating = (
        SELECT AVG(rating)
        FROM review
        WHERE product_id = NEW.product_id
    )
    WHERE product_id = NEW.product_id;
END;
//

CREATE TRIGGER update_seller_total_views
AFTER UPDATE ON product
FOR EACH ROW
BEGIN
    IF NEW.views > OLD.views THEN
        UPDATE Seller
        SET total_views = total_views + (NEW.views - OLD.views)
        WHERE user_id = NEW.seller_id;
    END IF;
END;
//

CREATE TRIGGER update_seller_date_verified
BEFORE UPDATE ON seller
FOR EACH ROW
BEGIN
    IF NEW.verified = TRUE AND OLD.verified = FALSE THEN
        SET NEW.date_verified = CURDATE();
    ELSEIF NEW.verified = FALSE AND OLD.verified = TRUE THEN
        SET NEW.date_verified = NULL;
    END IF;
END;
//

DELIMITER ;
