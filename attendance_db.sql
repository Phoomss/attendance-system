drop table users;
drop table attendances;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(10),
    firstname VARCHAR(100),
    surname VARCHAR(100),
    name VARCHAR(200),
    username varchar(255) unique,
    phone varchar(10) unique,
    email VARCHAR(255) UNIQUE,
    password varchar(255),
    role enum('admin','employee') default 'employee',
    picture VARCHAR(255),
    access_token TEXT,
    refresh_token TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from users u 

drop table attendances ;
CREATE TABLE attendances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    attendance_date DATE not null,
    attendance_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    departure_time TIMESTAMP null,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id)
);

drop table leaves ;
CREATE TABLE leaves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    leave_type ENUM('ลาป่วย', 'ลากิจ') NOT NULL,
    leave_date DATE NOT NULL,
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id)
); 

select * from attendances a ;
SELECT * 
FROM attendances a
WHERE employee_id = 1 AND attendance_date = "2025-01-17";


select * from users;

SELECT id, email , password, role from users WHERE email = 'admin@adminl.com' LIMIT 1

SELECT 
    u.id AS user_id,
    u.name AS user_name,
    a.attendance_time,
    a.departure_time,
    l.leave_type,
    l.leave_date,
    l.reason
FROM 
    users u
LEFT JOIN 
    attendances a 
ON 
    u.id = a.employee_id
LEFT JOIN 
    leaves l 
ON 
    u.id = l.employee_id;

