-- Table: users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    contact_info VARCHAR(255),
    is_logged_in BOOLEAN DEFAULT FALSE,
    role VARCHAR(50),  -- e.g., 'admin', 'designer', 'operator', 'customer', 'supervisor', 'qc'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: expenses
CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT
);

-- Table: design_request_headers
CREATE TABLE design_request_headers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL,
    supervisor_id INT,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (supervisor_id) REFERENCES users(id)
);

-- Table: design_requests
CREATE TABLE design_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_request_header_id INT NOT NULL,
    reference_image VARCHAR(255),
    description TEXT,
    total_pieces INT NOT NULL,
    price_per_design DECIMAL(10, 2),
    price_per_piece DECIMAL(10, 2),
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    supervisor_id INT,
    FOREIGN KEY (design_request_header_id) REFERENCES design_request_headers(id),
    FOREIGN KEY (supervisor_id) REFERENCES users(id)
);

-- Table: designs
CREATE TABLE designs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    designer_id INT NOT NULL,
    design_files TEXT NOT NULL,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES design_requests(id),
    FOREIGN KEY (designer_id) REFERENCES users(id)
);

-- Table: machine_operations
CREATE TABLE machine_operations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operator_id INT NOT NULL,
    design_id INT NOT NULL,
    assistant_id INT,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP,
    quantity INT NOT NULL,
    comments TEXT,
    FOREIGN KEY (operator_id) REFERENCES users(id),
    FOREIGN KEY (design_id) REFERENCES designs(id),
    FOREIGN KEY (assistant_id) REFERENCES users(id)
);

-- Table: qc_operations
CREATE TABLE qc_operations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qc_id INT NOT NULL,
    design_id INT NOT NULL,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP,
    quantity_checked INT NOT NULL,
    comments TEXT,
    FOREIGN KEY (qc_id) REFERENCES users(id),
    FOREIGN KEY (design_id) REFERENCES designs(id)
);

-- Table: payroll_jobs
CREATE TABLE payroll_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_request_id INT NOT NULL,
    employee_id INT NOT NULL,
    job_type VARCHAR(50),  -- e.g., 'designer', 'operator', 'qc'
    pieces_worked INT NOT NULL,
    pay_per_piece DECIMAL(10, 2) NOT NULL,
    total_pay DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (design_request_id) REFERENCES design_requests(id),
    FOREIGN KEY (employee_id) REFERENCES users(id)
);

-- Table: daily_payroll
CREATE TABLE daily_payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    work_date TIMESTAMP NOT NULL,
    total_pieces INT NOT NULL,
    daily_total_pay DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES users(id)
);

-- Table: weekly_payroll
CREATE TABLE weekly_payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    week_start_date TIMESTAMP NOT NULL,
    week_end_date TIMESTAMP NOT NULL,
    weekly_total_pay DECIMAL(10, 2) NOT NULL,
    paid BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (employee_id) REFERENCES users(id)
);

-- Table: transaction_headers
CREATE TABLE transaction_headers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL,
    payment_date TIMESTAMP,
    rating INT,
    feedback TEXT,
    FOREIGN KEY (customer_id) REFERENCES users(id)
);

-- Table: transaction_details
CREATE TABLE transaction_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    design_request_id INT NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transaction_headers(id),
    FOREIGN KEY (design_request_id) REFERENCES design_requests(id)
);

-- Table: activity_logs
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type VARCHAR(255) NOT NULL,
    activity_details TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
