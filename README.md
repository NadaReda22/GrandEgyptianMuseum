# 🏛️ Grand Egyptian Museum (GEM) Digital Platform | Graduation Project

![Project Type](https://img.shields.io/badge/Project-Graduation-gold)
![Status](https://img.shields.io/badge/Status-Completed-success)
![Laravel](https://img.shields.io/badge/Backend-Laravel-FF2D20?logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Admin-FilamentPHP-F28D1A?logo=filament&logoColor=white)
![Deployment](https://img.shields.io/badge/Deployment-VPS-blue)

## 📖 Overview

This system was developed as my **Bachelor's Graduation Project**. It represents a comprehensive **backend system and digital platform** designed to digitize the visitor experience for the **Grand Egyptian Museum (GEM)**.

As the **Lead Backend Developer** for this capstone project, I architected the entire server-side infrastructure. The system manages artifacts, events, and ticketing via a robust RESTful API that serves both Mobile (Flutter) and Web applications. Additionally, I handled the production deployment on a VPS .
and this project integrated immersive 3D experiences.

---

## 📦 Core Modules

### 🏺 Artifacts Management System
A centralized system to manage the museum's vast collection.
- **Detailed Records:** CRUD operations for artifacts including Title, Historical Era, Description, and Location within the museum.
- **Media & 3D Integration:** Capabilities to upload high-quality images and link interactive **3D Models (Three.js)** for a virtual tour experience.
- **Data Automation:** Utilized **Postman Runner** to automate the seeding of thousands of artifact records, simulating a real production environment saving days of manual entry.

### 📅 Events & Ticketing System
- **Event Scheduling:** Full management of museum events, including start/end dates, guest capacity, and special details.
- **Real-Time Booking:** Integrated with the mobile app to handle ticket reservations and check availability instantly.
- **Payment Integration:** Secure checkout process using **Paymob** API.

---

## ✨ Technical Key Features

### 🛠️ Backend & API Architecture
- **RESTful API:** Developed high-performance APIs consuming JSON for the Flutter mobile team and Frontend web team.
- **Authentication:** Complete Auth system (Sign up, Login, Email Verification, Reset/Forget Password) using Laravel Sanctum.


### 📱 Admin Dashboard (FilamentPHP)
- A modern, reactive admin panel created with **Filament** to control the entire ecosystem.
- Provides visual insights on ticket sales, user registrations, and system health.

### 🚀 DevOps & Deployment
- **VPS Deployment:** Successfully configured a Linux VPS (Ubuntu/Nginx), set up the MySQL database, and deployed the application for live production.
- **CI/CD Basics:** Managed version control and server updates.

### 🎨 The "Extra Mile" (3D Experience)
- **On-Site Digitization:** Personally photographed museum artifacts on-site at the GEM.
- **3D Modeling:** Processed images and rendered interactive **3D models using Three.js**, allowing users to rotate and view artifacts from all angles within the app.

---

## 🛠️ Tech Stack

| Domain | Technologies |
| :--- | :--- |
| **Backend Framework** | Laravel 11, PHP 8+ |
| **Admin Panel** | FilamentPHP |
| **Database** | MySQL |
| **Payments** | Paymob API Integration |
| **Visualization** | Three.js (JavaScript) |
| **Testing & Automation** | Postman, Postman Runner |
| **Server/Deployment** |  VPS, Nginx |

---

## 📸 Screenshots



### Admin Dashboard (Filament)
> *Manage Artifacts, Events, and Payments seamlessly.*

<img src="./artifacts/Screenshot%20(357).png" width="400" height="400" alt="Admin Dashboard"> 


### 3D Artifact Viewer
> *Interactive 3D models of real museum artifacts.*

<img src="./artifacts/Screenshot%20(356).png" width="300" height="300" alt="3D Viewer">
<img src="./artifacts/Screenshot%20(346).png" width="300" height="300" alt="3D Viewer">
<img src="./artifacts/Screenshot%20(355).png" width="400" height="400" alt="3D Viewer">

### Other features from the GEM platform 

<img src="./artifacts/Screenshot%20(347).png" width="400" height="400" alt="3D Viewer">
<img src="./artifacts/Screenshot%20(353).png" width="400" height="400" alt="3D Viewer">
<img src="./artifacts/Screenshot%20(354).png" width="400" height="400" alt="3D Viewer">
<img src="./artifacts/Screenshot%20(352).png" width="400" height="400" alt="3D Viewer">

---

### ⚙️ Installation & Setup

1. **Clone the repository**
```bash
git clone https://github.com/NadaReda22/GrandEgyptianMuseum.git
cd backend
```
Install Dependencies


```bash
composer install
npm install
```


### Environment Setup

1. **Setup Environment Variables**
```bash
cp .env.example .env
php artisan key:generate
```

Configure your .env file with your Database and Paymob credentials.

2. Database Migration

  ```bash
  php artisan migrate --seed
   ```
3. Run the Server

```Bash
 php artisan serve
```
🔐 Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail via [nadoarmando22@gmail.com].

👨‍💻 Author

Nada Reda Backend Developer | Problem Solving |  This project was submitted as a Graduation Project (2024/2025)

[LinkedIn](https://www.linkedin.com/in/nada-reda22) | [Email](mailto:nadoarmando22@gmail.com) | [Youtube](https://www.youtube.com/watch?v=zIOJBvSx5Jc)
