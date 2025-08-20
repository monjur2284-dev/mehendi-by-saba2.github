-- SQL schema for yoga_store_demo
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) DEFAULT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT 'placeholder.png'
);

INSERT INTO products (name, slug, description, price, image) VALUES
('Yoga Mat Pro', 'yoga-mat-pro', 'Premium anti-slip yoga mat, 6mm thick.', 1200.00, 'mat.jpg'),
('Yoga Block Set', 'yoga-block-set', 'High-density foam blocks, set of 2.', 800.00, 'block.jpg'),
('Meditation Cushion', 'meditation-cushion', 'Comfortable round cushion.', 950.00, 'cushion.jpg');

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(100) UNIQUE,
  customer_name VARCHAR(255),
  customer_email VARCHAR(255),
  phone VARCHAR(50),
  address TEXT,
  items TEXT, -- json
  total DECIMAL(10,2) DEFAULT 0,
  payment_method VARCHAR(50),
  status VARCHAR(50) DEFAULT 'pending',
  timeline TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
