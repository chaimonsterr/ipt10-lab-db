# IPT10 Database Programming Laboratory Activity

This repository contains two implementations of a student record management web application built with PHP and MySQL:

1. `ipt10_lab/` - Procedural `mysqli` implementation
2. `ipt10_lab_pdo/` - Object-oriented `PDO` implementation

---

## Web Browser URLs (Files to Run)

To launch and test the applications in your web browser, open:

- **PDO Version:**  
  `http://localhost/ipt10-lab-db/ipt10_lab_pdo/index.php`

- **mysqli Version:**  
  `http://localhost/ipt10-lab-db/ipt10_lab/index.php`

---

## Environment Setup & Installation

### 1. Install XAMPP
1. Download XAMPP for your OS (Windows, macOS, or Linux) from [Apache Friends](https://www.apachefriends.org/download.html).
2. Install and launch the XAMPP Control Panel.
3. Start both the **Apache** and **MySQL** services.

---

### 2. Enable Required PHP Extensions
1. Open your XAMPP `php.ini` file.
2. Search for and uncomment the following lines by removing the leading semicolon `;`:
```ini
extension=mysqli
extension=pdo_mysql
```
3. Save the `php.ini` file and restart Apache in the XAMPP Control Panel.
4. Verify by visiting `http://localhost/phpinfo.php` and checking that `mysqli` and `pdo_mysql` show as **enabled**.

---

### 3. Create the Database in phpMyAdmin
1. Open phpMyAdmin in your browser: `http://localhost/phpmyadmin`.
2. Click on the **SQL** tab.
3. Copy and execute the following SQL script to create the database, `students` table, and default data:

```sql
CREATE DATABASE IF NOT EXISTS `ip10_lab` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ip10_lab`;

CREATE TABLE `students` (
  `id` char(36) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `email` varchar(150) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `program` varchar(200) NOT NULL,
  `enrolment_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `students` (`id`, `first_name`, `middle_name`, `last_name`, `birthday`, `sex`, `email`, `student_number`, `program`, `enrolment_date`, `created_at`, `updated_at`) VALUES
('13bc50ac-be43-11f1-85b8-107c61d3f786', 'Juan', 'Delos', 'Dela Cruz', '2005-03-22', 'Male', 'juan@school.edu', 'STU002', 'Bachelor of Science in Information Technology', '2024-09-01', '2026-10-02 09:24:32', '2026-10-02 09:24:32'),
('13bc514b-be43-11f1-85b8-107c61d3f786', 'Ana', 'Lopez', 'Reyes', '2005-11-08', 'Female', 'ana@school.edu', 'STU003', 'Bachelor of Science in Computer Science', '2024-09-02', '2026-10-02 09:24:32', '2026-10-02 09:24:32'),
('e29aa875-be49-11f1-85b8-107c61d3f786', 'Amiel', 'Supan', 'Santos', '2005-10-24', 'Male', 'amiel.santos@gmail.com', '24-0196-982', 'BS in Nursing', '2024-03-11', '2026-10-02 10:13:16', '2026-10-02 10:23:41');

ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_number` (`student_number`);
```