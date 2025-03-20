


### 🏫 School Management System

A **School Management System** built with Laravel to manage students, teachers, classes, subjects, attendance, fees, and more.  

---

## 📌 Features

✅ **Admin Dashboard** – Manage the entire school system from one place  
✅ **User Roles & Authentication** – Admin, Teacher, Student, and Parent roles  
✅ **Student Management** – Add, update, and delete student records  
✅ **Teacher Management** – Assign teachers to subjects and classes  
✅ **Class & Subject Management** – Organize curriculum efficiently  
✅ **Notifications & Reports** – Send notifications and generate reports  

---

## 🚀 Installation

### 1️⃣ Clone the Repository  
```bash
git clone https://github.com/defaltastra/Laraschool.git
cd Laraschool
```


### 3️⃣ Set Up Environment  
Copy `.env.example` to `.env` and update database credentials:  
```bash
cp .env.example .env
```
Update `.env` file:
```ini
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4️⃣ Generate Application Key  
```bash
php artisan key:generate
```

### 5️⃣ Run Migrations   
```bash
php artisan migrate 
```

### 6️⃣ Start Development Server  
```bash
php artisan serve
```
Visit **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your browser.

---

## 🛠 Technologies Used

- **Laravel** – Backend framework  
- **MySQL** – Database  
- **AdminLTE** – Dashboard UI  

---

## 🔐 User Roles

| Role      | Description |
|-----------|------------|
| Admin     | Full access to all features |
| Teacher   | Manages students, subjects, and attendance |
| Student   | Views grades, timetable, and notifications |



---

## ⚡ License

This project is **open-source** under the [MIT License](LICENSE).

---
