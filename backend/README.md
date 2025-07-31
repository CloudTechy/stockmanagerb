
# StockManagerB

StockManagerB is an **inventory and stock management system** designed to help businesses efficiently track their stock levels, manage inventory processes, and streamline operations. Built with a **modern tech stack**, StockManagerB provides a **user-friendly interface** for managing stock data, real-time updates, and accurate reporting.

## Key Features

* **Real-time Stock Tracking**: Monitor your stock levels in real-time, ensuring you're always up-to-date with inventory counts.
* **Automated Alerts**: Set alerts for low stock levels, expiry dates, and other critical thresholds.
* **Stock History**: Track stock movements (additions, removals, and adjustments) to maintain complete transparency.
* **User Roles & Permissions**: Multiple user roles (Admin, Manager, User) with tailored permissions.
* **Reporting & Analytics**: Generate reports on stock levels, sales, and more to make data-driven decisions.
* **Cloud Sync**: Cloud-based system to ensure data availability and security across all your locations.

## Tech Stack

* **Frontend**: Vue.js, HTML5, CSS3, JavaScript (ES6+)
* **Backend**: Laravel (PHP)
* **Database**: PostgreSQL
* **Authentication**: JWT (JSON Web Tokens)
* **Cloud Hosting**: AWS, Firebase
* **Version Control**: Git & GitHub

## Installation

### Prerequisites

Before running this project, ensure you have the following installed on your machine:

* **PHP** (7.3 or higher)
* **Composer** (PHP Dependency Manager)
* **Node.js** and **npm**
* **PostgreSQL** Database

### Steps to Run Locally

1. **Clone the repository**:

   ```bash
   git clone https://github.com/CloudTechy/stockmanagerb.git
   cd stockmanagerb
   ```

2. **Install backend dependencies**:

   ```bash
   cd backend
   composer install
   ```

3. **Set up your `.env` file**:

   * Copy `.env.example` to `.env`
   * Update your database credentials and other environment variables as needed:

     ```bash
     cp .env.example .env
     ```

4. **Generate application key**:

   ```bash
   php artisan key:generate
   ```

5. **Run database migrations**:

   ```bash
   php artisan migrate
   ```

6. **Install frontend dependencies**:

   * Navigate to the frontend folder and install dependencies:

     ```bash
     cd ../frontend
     npm install
     ```

7. **Run the development server**:

   * For the backend (Laravel):

     ```bash
     php artisan serve
     ```
   * For the frontend (Vue.js):

     ```bash
     npm run dev
     ```

Now, the application should be available at `http://localhost:8000` (or the port specified by your Laravel server).

## Usage

### User Roles

* **Admin**: Full access to all features, including user management, stock history, and system settings.
* **Manager**: Can view and manage stock levels, generate reports, and set alerts.
* **User**: Can view stock levels and reports but cannot make changes.

### Stock Management

* Add, update, or delete products.
* Set stock quantities, price details, and expiry dates.
* View stock details and history for complete transparency.

### Reports & Analytics

* Generate reports on stock movements, sales trends, and product performance.
* Export reports in various formats (PDF, CSV).

## Contributing

We welcome contributions to make StockManagerB even better! Here's how you can contribute:

1. **Fork the repo**.
2. **Create a new branch** for your feature or bugfix:

   ```bash
   git checkout -b feature/your-feature
   ```
3. **Make changes** and commit them:

   ```bash
   git commit -am 'Add new feature'
   ```
4. **Push** to your fork:

   ```bash
   git push origin feature/your-feature
   ```
5. **Submit a pull request** with a description of your changes.

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Acknowledgments

* [Vue.js](https://vuejs.org/) – A progressive JavaScript framework.
* [Laravel](https://laravel.com/) – The PHP framework for web artisans.
* [PostgreSQL](https://www.postgresql.org/) – Open source object-relational database system.

