-- ============================================
-- SRCM Inter College - Complete Database
-- ============================================

CREATE DATABASE IF NOT EXISTS srcm_college;
USE srcm_college;

-- Settings / Site Config
CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) UNIQUE NOT NULL,
  setting_value TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES
('college_name', 'SRCM Inter College'),
('college_tagline', 'Excellence in Education Since Decades'),
('college_address', 'Jainpur, Gorakhpur, Uttar Pradesh - 273151'),
('college_phone', '+91-XXXXXXXXXX'),
('college_email', 'srcmintercollege@gmail.com'),
('college_school_code', '1326'),
('college_board', 'U.P. Board (UPMSP)'),
('college_facebook', 'https://www.facebook.com/p/SRCM-Inter-College-Jainpur-Gorakhpur-100057306735847/'),
('college_instagram', '#'),
('college_youtube', '#'),
('college_timing', 'Monday–Saturday: 7:30 AM – 2:00 PM'),
('about_text', 'SRCM Inter College, Jainpur, Gorakhpur is a recognised institution affiliated to U.P. Board (School Code: 1326). We offer Science, Arts and Commerce streams for Classes 9 to 12.'),
('admission_status', 'open'),
('meta_title', 'SRCM Inter College - Jainpur, Gorakhpur'),
('meta_description', 'SRCM Inter College Jainpur Gorakhpur - UP Board affiliated school offering quality education in Science, Arts and Commerce for Class 9 to 12.');

-- Slider
CREATE TABLE sliders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200),
  subtitle TEXT,
  btn_text VARCHAR(100),
  btn_link VARCHAR(200),
  bg_gradient VARCHAR(200) DEFAULT 'linear-gradient(135deg,#1a3a5c,#2d6a8a)',
  image_path VARCHAR(300),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO sliders (title, subtitle, btn_text, btn_link, bg_gradient, sort_order) VALUES
('SRCM Inter College, Jainpur', 'Shaping Young Minds with Values, Knowledge & Discipline', 'Know More', 'about.php', 'linear-gradient(135deg,#0d2a44,#1a5c8a)', 1),
('Admissions Open 2025-26', 'Enroll Now for Class 9th to 12th — Limited Seats Available!', 'Apply Now', 'admission.php', 'linear-gradient(135deg,#5c1a0d,#8a3d0d)', 2),
('Outstanding Board Results', 'Our Students Consistently Achieve Top Marks in U.P. Board', 'See Results', 'results.php', 'linear-gradient(135deg,#1a5c1a,#2d6a4a)', 3),
('Modern Infrastructure', 'Well-Equipped Labs, Smart Classrooms, Library & Sports', 'Explore', 'facilities.php', 'linear-gradient(135deg,#3d1a5c,#5c1a4a)', 4);

-- Announcements
CREATE TABLE announcements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title TEXT NOT NULL,
  detail TEXT,
  ann_date DATE,
  is_new TINYINT(1) DEFAULT 1,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO announcements (title, ann_date, is_new) VALUES
('वार्षिक परीक्षा 2024-25 — March 2025 से प्रारम्भ', '2025-03-01', 1),
('Class XII Practical Examination Schedule Released', '2025-02-15', 1),
('Admission Open 2025-26 — Class 9 to 12', '2025-01-10', 1),
('सरस्वती पूजा समारोह — 2 फरवरी 2025', '2025-01-20', 0),
('अर्द्धवार्षिक परीक्षा परिणाम घोषित', '2025-01-05', 0),
('Annual Sports Day — December 2024', '2024-11-30', 0);

-- News / UP Board Updates
CREATE TABLE news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title TEXT NOT NULL,
  detail TEXT,
  link VARCHAR(500),
  news_date DATE,
  category ENUM('college','upboard') DEFAULT 'college',
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO news (title, news_date, category) VALUES
('वार्षिक परीक्षा 2024-25 — March 2025 से प्रारम्भ', '2025-03-01', 'college'),
('Class XII Practical Exam Schedule 2024-25', '2025-02-15', 'college'),
('Admission Open for Session 2025-26', '2025-01-10', 'college'),
('Saraswati Puja Celebration — 2 February 2025', '2025-01-20', 'college'),
('UP Board Practical Exam Class X & XII 2024-25', '2025-02-10', 'upboard'),
('Registration for Class IX & XI 2025-26', '2025-02-01', 'upboard'),
('UP Board Time Table 2025 — Class 10 & 12', '2025-01-15', 'upboard'),
('UP Government Scholarship Scheme 2024-25', '2024-12-10', 'upboard');

-- Gallery Categories
CREATE TABLE gallery_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100),
  is_active TINYINT(1) DEFAULT 1
);

INSERT INTO gallery_categories (name, slug) VALUES
('Annual Function', 'annual-function'),
('Sports Day', 'sports-day'),
('Cultural Events', 'cultural-events'),
('Saraswati Puja', 'saraswati-puja'),
('Independence Day', 'independence-day'),
('Science Lab', 'science-lab');

-- Gallery
CREATE TABLE gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  title VARCHAR(200),
  image_path VARCHAR(300) NOT NULL,
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES gallery_categories(id)
);

-- Results
CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  exam_year VARCHAR(10),
  class VARCHAR(20),
  stream VARCHAR(50),
  total_students INT,
  passed_students INT,
  first_div INT,
  second_div INT,
  distinctions INT,
  pass_percent DECIMAL(5,2),
  is_active TINYINT(1) DEFAULT 1
);

INSERT INTO results (exam_year, class, stream, total_students, passed_students, first_div, distinctions, pass_percent) VALUES
('2024', 'Class 12', 'Science', 45, 45, 38, 12, 100.00),
('2024', 'Class 12', 'Arts', 60, 60, 48, 8, 100.00),
('2024', 'Class 12', 'Commerce', 30, 30, 24, 5, 100.00),
('2024', 'Class 10', 'All', 80, 80, 64, 10, 100.00),
('2023', 'Class 12', 'All', 120, 118, 95, 18, 98.33),
('2023', 'Class 10', 'All', 75, 74, 58, 9, 98.67);

-- Toppers
CREATE TABLE toppers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  exam_year VARCHAR(10),
  student_name VARCHAR(200),
  class VARCHAR(20),
  stream VARCHAR(50),
  marks_obtained INT,
  total_marks INT,
  rank_position INT,
  photo_path VARCHAR(300),
  is_active TINYINT(1) DEFAULT 1
);

INSERT INTO toppers (exam_year, student_name, class, stream, marks_obtained, total_marks, rank_position) VALUES
('2024', 'Science Topper', 'Class 12', 'Science', 485, 500, 1),
('2024', 'Arts Topper', 'Class 12', 'Arts', 478, 500, 1),
('2024', 'Commerce Topper', 'Class 12', 'Commerce', 471, 500, 1),
('2024', 'High School Topper', 'Class 10', 'All', 492, 500, 1);

-- Staff
CREATE TABLE staff (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  designation VARCHAR(200),
  subject VARCHAR(200),
  qualification VARCHAR(300),
  experience VARCHAR(100),
  photo_path VARCHAR(300),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

INSERT INTO staff (name, designation, subject, qualification, experience, sort_order) VALUES
('प्रधानाचार्य महोदय', 'Principal', 'Administration', 'M.A., B.Ed.', '20+ Years', 1),
('Hindi Lecturer', 'Senior Lecturer', 'Hindi', 'M.A. Hindi, B.Ed.', '15+ Years', 2),
('Mathematics Teacher', 'Lecturer', 'Mathematics', 'M.Sc. Maths, B.Ed.', '12+ Years', 3),
('Science Teacher', 'Lecturer', 'Physics/Chemistry', 'M.Sc. Physics, B.Ed.', '10+ Years', 4),
('Social Science Teacher', 'Lecturer', 'History/Geography', 'M.A. History, B.Ed.', '8+ Years', 5),
('English Teacher', 'Lecturer', 'English', 'M.A. English, B.Ed.', '7+ Years', 6);

-- Fee Structure
CREATE TABLE fee_structure (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class VARCHAR(50),
  stream VARCHAR(50),
  admission_fee INT,
  registration_fee INT,
  monthly_fee INT,
  session_year VARCHAR(20) DEFAULT '2025-26',
  is_active TINYINT(1) DEFAULT 1
);

INSERT INTO fee_structure (class, stream, admission_fee, registration_fee, monthly_fee) VALUES
('Class 9', 'All', 500, 200, 250),
('Class 10', 'All', 500, 200, 250),
('Class 11', 'Science', 700, 300, 350),
('Class 11', 'Arts', 600, 300, 300),
('Class 11', 'Commerce', 600, 300, 300),
('Class 12', 'Science', 700, 300, 350),
('Class 12', 'Arts', 600, 300, 300),
('Class 12', 'Commerce', 600, 300, 300);

-- TC (Transfer Certificate) Search
CREATE TABLE tc_records (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tc_number VARCHAR(50) UNIQUE,
  student_name VARCHAR(200),
  father_name VARCHAR(200),
  class VARCHAR(50),
  stream VARCHAR(50),
  admission_year VARCHAR(10),
  leaving_year VARCHAR(10),
  issue_date DATE,
  is_active TINYINT(1) DEFAULT 1
);

-- Admissions / Enquiries
CREATE TABLE enquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_name VARCHAR(200),
  father_name VARCHAR(200),
  phone VARCHAR(20),
  email VARCHAR(200),
  class_applying VARCHAR(50),
  stream VARCHAR(50),
  message TEXT,
  status ENUM('new','contacted','admitted','rejected') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials / Thoughts
CREATE TABLE thoughts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  thought_text TEXT NOT NULL,
  is_active TINYINT(1) DEFAULT 1
);

INSERT INTO thoughts (thought_text) VALUES
('"ज्ञान ही सबसे बड़ा धन है, इसे कोई चुरा नहीं सकता।"'),
('"विद्यार्थी जीवन का हर पल अमूल्य है — इसे व्यर्थ न करें।"'),
('"कठिन परिश्रम ही सफलता का एकमात्र मार्ग है।"'),
('"शिक्षा वह हथियार है जिससे दुनिया बदली जा सकती है।"'),
('"सफलता का कोई शॉर्टकट नहीं — मेहनत करते रहो।"'),
('"स्वयं पर विश्वास रखो — तुम जरूर सफल होगे।"');

-- Admin Users
CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(200),
  role ENUM('superadmin','admin') DEFAULT 'admin',
  last_login TIMESTAMP,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: username=admin, password=srcm@2025
INSERT INTO admin_users (username, password, full_name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'superadmin');

-- Contact Messages
CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200),
  phone VARCHAR(20),
  email VARCHAR(200),
  subject VARCHAR(300),
  message TEXT,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
