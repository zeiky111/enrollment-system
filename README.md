Niezl Skye B. De Torres
BSIS 3A

This project is a Student Enrollment System built using PHP (with PDO) on the backend and JavaScript (Fetch API + async/await) on the frontend. It provides full CRUD functionality (Create, Read, Update, Delete) for managing Students, Programs, School Years, Semesters, Subjects, and Enrollments.
The system is designed to handle student registration, subject assignments per semester, and record management—all done asynchronously without page reloads.
Key Features
Student Management – Add, view, update, and delete student records. Each student has program details and allowance information. Supports searching and filtering by program.
Program Management – Manage academic programs; includes adding, updating, and deleting programs.
Years & Semesters – Handle school years and semesters efficiently.
Subject Management – Add, update, and remove subjects. Subjects are linked to semesters, with options to filter/search.
Enrollment Management – Enroll students in subjects, edit existing enrollments, and remove them when necessary.
UI/UX – Modal forms for data entry, async operations with Fetch API, real-time DOM updates, and basic input validation.
Known Limitations
Code can still be improved through refactoring and better structure.
Inline validation is minimal error handling mostly depends on backend checks.
Some actions (like deleting programs with students or subjects tied to enrollments) may cause errors if not carefully handled.
Features such as pagination, loading indicators, and advanced validation were not yet implemented.
Setup Guide
Requirements
XAMPP v3.3.0 (or later) – includes Apache, PHP, and MySQL/MariaDB
A modern browser (Google Chrome, Microsoft Edge, or Firefox recommended)
Installation Steps
Download or clone this project into your htdocs folder inside XAMPP.
Open XAMPP Control Panel, then start Apache and MySQL.
Import the database:
Go to http://localhost/phpmyadmin
Create a new database 
Import the provided .sql file found inside the project folder.
Configure the database connection:
Open connect.php
Update database credentials if needed:
