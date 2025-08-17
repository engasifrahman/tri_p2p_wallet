<h1 align="center">TRI P2P Wallet</h1>

A simple **peer-to-peer (P2P) wallet system** built with **Laravel 12 (backend)** and **Vue 3 (frontend)**.  
It provides user authentication, wallet statistics, transaction management, and money transfer functionality via a RESTful API with **Sanctum token-based authentication**.

---

## 🚀 Features

- **User Authentication:** Registration, login, and logout using Sanctum tokens. 🔑  
- **Wallet Statistics:** Real-time wallet statistics for users. 📊  
- **Transactions:** View all transactions, sent money, and received money. 💸  
- **Send Money:** Initiate and complete money transfers with authorization checks. 🔄  
- **Role-Based Access:** Protects routes via `auth:sanctum` middleware. 🛡️  
- **Frontend:** Single-page application built with **Vue 3**, Vue Router, and dynamic components. 🎨  
- **Queue Processing:** Asynchronous transaction handling with Laravel queues. ⚡  
- **Postman Collection:** Ready-to-use API collection for testing endpoints. 📬  

---

## 📂 Backend API Routes (Laravel)

All API routes are prefixed with `/v1`.

### 🔓 Public Routes

| Method | Path | Description |
| :---: | :--- | :--- |
| `POST` | `/v1/registration` | Register a new user. 📝 |
| `POST` | `/v1/login` | Login and receive a Sanctum token. 🔑 |

### 🔒 Protected Routes (`auth:sanctum`)

| Method | Path | Description |
| :---: | :--- | :--- |
| `GET` | `/v1/statistics` | View wallet statistics. 📊 |
| `GET` | `/v1/transactions` | View all transactions. 💼 |
| `GET` | `/v1/sent-money-transactions` | View sent money transactions. ➡️ |
| `GET` | `/v1/received-money-transactions` | View received money transactions. ⬅️ |
| `POST` | `/v1/initiate-send-money` | Initiate a money transfer. 💸 |
| `PUT` | `/v1/complete-send-money/{transaction}` | Complete a money transfer. 🔒 (Requires permission) |
| `GET` | `/v1/logout` | Logout and revoke the token. 🚪 |

---

## 📂 Frontend Routes (Vue 3)

Uses **Vue Router** with `createWebHistory` and dynamic components.

| Path | Name | Description | Component |
| :---: | :--- | :--- | :--- |
| `/` | `home` | Homepage | `Home.vue` |
| `/signup` | `signup` | Signup form | `Signup.vue` |
| `/login` | `login` | Login form | `Login.vue` |
| `/dashboard` | `dashboard` | Main dashboard | `Dashboard.vue` |
| `/dashboard/statistics` | `dashboard.statistics` | Wallet statistics | `Statistics.vue` |
| `/dashboard/transactions` | `dashboard.transactions` | Transaction list | `Transactions.vue` |

- Dashboard routes are **nested**, with a default redirect to `dashboard.statistics`.  
- Meta titles are dynamically updated per route.  

---

## 🛠️ Installation Instructions

### 1️⃣ Clone Repository

```bash
git clone [repository-url]
cd [project-directory]
````

### 2️⃣ Install Backend Dependencies

```bash
cd backend
composer install
composer dump-autoload
```

### 3️⃣ Install Frontend Dependencies

```bash
cd frontend
npm install
```

### 4️⃣ Environment Setup

* Copy `.env.example` to `.env` for backend:

```bash
cp backend/.env.example backend/.env
php artisan key:generate
```

* Configure database and email (SMTP) settings.

### 5️⃣ Database Setup

```bash
php artisan migrate --seed
# OR fresh setup
php artisan migrate:fresh --seed
```

### 6️⃣ Run Backend & Frontend

Backend (Laravel):

```bash
php artisan serve
```

Frontend (Vue 3):

```bash
npm run dev
```

Browse at [http://localhost:8000/](http://localhost:8000/)

### 7️⃣ Queue Worker

```bash
php artisan queue:work
```

### 8️⃣ Run Tests

```bash
php artisan test
```

---

## 📬 Postman API Collection

* [TRI P2P Wallet API Collection](https://asifs-team.postman.co/workspace/My-Workspace~f1158975-2e9e-4979-8c40-9bdf2695c4c2/collection/22819528-bd816e34-3a0f-4622-a7c3-854ee26177a1?action=share&creator=22819528)
* Or find `TRI P2P Wallet.postman_collection.json` in the project root.

---

## 🎬 Demo Video

[TRI P2P Wallet Demo](https://screencast-o-matic.com/watch/c3XfQwVu5Tb)

---

## 👍 Notes

* Ensure **queues** are running for transaction processing.
* Set correct **SMTP** settings to enable email notifications.
* Protected routes require **Sanctum token** in `Authorization: Bearer {token}` header.
* Vue 3 frontend interacts with backend APIs via the defined routes.

---

Made with ❤️ using **Laravel 9** & **Vue 3**.

```
