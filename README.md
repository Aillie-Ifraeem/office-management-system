 Office Management System (OMS) - Prototype

 Overview

This project is a **role-based Office Management System (OMS)** built using **PHP, MySQL, and JavaScript**.
It demonstrates a complete workflow of task assignment and submission across multiple roles.

> ⚠️ This is a **prototype** focused on backend logic, data flow, and system design. UI improvements are ongoing.

---

 Features

 Authentication & Roles

* Secure login system
* Role-based access:

  * Manager
  * Team Leader
  * Employee

---

 Workflow System

 Manager

* Views Team Leaders and Employees
* (Planned) Assign tasks to Team Leaders
* Reviews submissions from Team Leaders

 Team Leader

* Receives tasks from Manager
* Assigns tasks to Employees
* Views employee submissions
* Forwards completed tasks to Manager

 Employee

* Views assigned tasks
* Submits completed work (with file + remarks)
* Marks attendance

---

 Task Flow

Manager → Team Leader → Employee → Team Leader → Manager

---

 Additional Features

* Task submission system
* File upload support
* Attendance tracking
* Dynamic UI using JavaScript (Fetch API)

---
 Tech Stack

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL

---

 Setup Instructions

1. Clone the repository:

```bash
git clone https://github.com/YOUR_USERNAME/office-management-system.git
```

2. Move project to your server directory:

* XAMPP → `htdocs`
* WAMP → `www`

3. Import database:

* Open phpMyAdmin
* Create database (e.g., `oms`)
* Import provided SQL file

4. Configure database connection:

* Update `db/connection.php` with your credentials

5. Run project:

```
http://localhost/office-management-system
```

---

 Future Improvements

* Manager task assignment UI
* Task approval/rejection system
* Search & filters
* Better UI/UX
* Security improvements (prepared statements, password hashing)

---

 Screenshots

*(Add screenshots here later for better presentation)*

---

 Author

**Aillie Ifraeem**

---

 Note

This project focuses on **understanding backend architecture and workflow design**, rather than final production-level UI.
