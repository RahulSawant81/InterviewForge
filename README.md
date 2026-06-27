# 🚀 InterviewForge Backend

InterviewForge is an AI-powered interview preparation platform that helps job seekers improve their resumes, practice technical interviews, and receive personalized AI-driven feedback.

This repository contains the **Laravel 12 REST API backend** powering the InterviewForge platform.

---

## ✨ Features

### 🔐 Authentication

* Laravel Sanctum Authentication
* User Registration & Login
* Protected API Routes
* Profile Management

### 👤 Profile Module

* User Profile CRUD
* Country, State & City Support
* Profile Image Support
* Clean Resource APIs

### 📄 Resume Management

* Upload Resume (PDF)
* Download Resume
* Resume Metadata Management
* Resume Storage

### 🤖 AI Resume Analysis

* Google Gemini AI Integration
* Resume Text Extraction
* Executive Summary Generation
* ATS Compatibility Score
* Skills Detection
* Missing Skills Identification
* Strengths Analysis
* Weaknesses Analysis
* Career Recommendations
* Re-analysis Support

### 📑 PDF Report

* Professional Resume Analysis Report
* ATS Score
* Executive Summary
* Skills
* Missing Skills
* Strengths
* Weaknesses
* Recommendations

---

## 🛠 Tech Stack

* Laravel 12
* PHP 8.2+
* MySQL
* Laravel Sanctum
* Google Gemini API
* DomPDF
* Laravel Pint
* PHPStan (Larastan)
* Scramble API Documentation

---

## 📁 Project Structure

```text
app/
 ├── Http/
 ├── Models/
 ├── Services/
 │    ├── AI/
 │    └── Resume/
 ├── Resources/
 ├── Enums/
 ├── Traits/
 └── Helpers/
```

---

## ⚙️ Installation

Clone the repository

```bash
git clone https://github.com/RahulSawant81/InterviewForge.git
```

Go to the project directory

```bash
cd InterviewForge/backend
```

Install dependencies

```bash
composer install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure your database inside `.env`

Run migrations

```bash
php artisan migrate
```

Start the development server

```bash
php artisan serve
```

---

## 🔑 Environment Variables

Configure the following variables:

```env
APP_NAME=InterviewForge
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_DATABASE=interviewforge

GEMINI_API_KEY=YOUR_API_KEY
```

---

## 📚 API Documentation

Generate API documentation using Scramble:

```bash
php artisan scramble:export
```

---

## 🧪 Code Quality

Run formatter

```bash
composer format
```

Static Analysis

```bash
composer analyse
```

Run Tests

```bash
composer test
```

Run all quality checks

```bash
composer quality
```

---

## 🚧 Roadmap

### ✅ Completed

* Authentication
* Profile Module
* Resume Upload
* Resume Download
* AI Resume Analysis
* PDF Report Generation

### 🚀 In Progress

* AI Mock Interview
* AI Answer Evaluation
* Interview Feedback
* Dashboard & Analytics

### 🔮 Planned

* Career Coach
* Job Recommendation Engine
* Resume ATS Optimization
* AI Interview Voice Support

---

## 🤝 Contributing

Contributions are welcome. Feel free to open issues or submit pull requests.

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

**Rahul Sawant**

Backend Developer | Full Stack Developer | AI Enthusiast

GitHub: https://github.com/RahulSawant81
