# 🇴🇲 Kandura Store – Backend (Laravel 12)

**Kandura Store** is a complete backend system built with **Laravel 12**, designed to manage a custom Gulf-traditional clothing (Kandura) e-commerce platform.
The system supports **users, addresses, measurements, designs, orders, wallet, reviews, coupons**, and a full **admin/super admin dashboard** with rich permissions and analytics.

This repository contains the full backend implementation following **clean, scalable, service-based architecture**.

---

## 🚀 Features

### **🧑‍🤝‍🧑 User System**

* User registration & login (Laravel Passport OAuth2)
* User profile management
* Manage translated fields (Spatie Translatable)
* Upload and manage profile images (Spatie Media Library)
* Soft deletes + full audit trail

---

### **📍 Address Management**

* Add/update/delete user addresses
* Translated address fields (JSON + localization)
* Search, filter, sorting, pagination
* Default address handling
* Latitude/longitude support for map/location services

---

### **📐 Measurements Module** *(coming soon)*

* Store detailed custom measurements for Kandura
* Multiple measurement profiles per user

---

### **🎨 Designs & Customization**

* User-generated design options
* Admin-managed additional design attributes
* Spatie permission-restricted editing

---

### **🛒 Orders & Checkout**

* Create new orders
* Update order status (Admin)
* View order history
* Measurement + address linking

---

### **💰 Wallet & Payments**

* User wallet balance
* Transaction logs
* Admin credit/withdraw actions

---

### **⭐ Reviews**

* Users can submit product/store reviews
* Admin moderation controls

---

## 🔐 Authentication & Authorization

### **Laravel Passport API Authentication**

* Secure OAuth2 tokens
* Bearer Token authentication

### **Role & Permission System (Spatie Permission)**

Includes 3 main roles:

* **User**
* **Admin**
* **Super Admin**

Admins and super admins access the dashboard with full role-based authorization.

---

## 🖥 Admin Dashboard (Web)

Dashboard features include:

* Authentication (web guard)
* Role/permission management
* User management
* Locations overview
* Analytics + charts
* Notification system
* System settings
* Reports & exports

The dashboard uses a clean UI built on top of Bootstrap/Sneat theme.

---

## 🧱 Tech Stack

* **Laravel 12**
* **Laravel Passport**
* **Spatie Permission**
* **Spatie Media Library**
* **Spatie Translatable**
* **MySQL**
* **RESTful API architecture**
* **Service Layer Pattern**
* **Repository-like clean structure**
* **Policies for access control**

---

## 🏗 Project Architecture

The app follows best practices:

```
app/
 ├── Http/
 │    ├── Controllers/
 │    ├── Requests/
 │    ├── Middleware/
 │    └── Resources/
 ├── Services/
 │    ├── User/
 │    ├── Admin/
 │    └── Global/
 ├── Models/
 ├── Policies/
 └── Providers/
```

* Clean, maintainable, scalable
* Logic inside **Services**, not Controllers
* Policies for permissions
* Requests for validation
* Resources for response formatting

---

## 📦 Installation & Setup

```
git clone https://github.com/your_username/kandura-store.git
cd kandura-store

composer install
cp .env.example .env

php artisan key:generate
php artisan passport:install
php artisan migrate --seed

php artisan serve
```

---

## 🧪 API Testing

Supports Postman collection with:

* Auth
* User
* Address
* Designs
* Orders
* Wallet
* Admin endpoints (web)

---

## 🤝 Contributing

Pull requests are welcome.
For major changes, please open an issue first to discuss what you’d like to change.

---

## 📄 License

MIT License.

---

If you want, I can also generate:

✅ A polished README.md
✅ API Documentation section
✅ OpenAPI/Swagger file
✅ Installation GIF preview
Just tell me.
