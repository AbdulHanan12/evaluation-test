# Centralized Company Search & Reports System

A Laravel-based application that provides unified search and reporting functionality across multiple country-specific company databases (Singapore and Mexico).

## 🎯 Project Overview

This system allows users to:
- **Search companies** across multiple databases simultaneously
- **View company details** with country-specific information
- **Browse available reports** based on country-specific pricing logic
- **Add reports to cart** with proper pricing calculations
- **Manage shopping cart** across different countries

## 🏗️ Architecture & Design

### Multi-Database Architecture
The system is designed to handle multiple country-specific databases with different schemas:

- **Singapore (SG)**: `companies_house_sg` database
  - Tables: `companies`, `reports`
  - Logic: All companies have access to all reports
  - Pricing: Direct from reports table

- **Mexico (MX)**: `companies_house_mx` database
  - Tables: `companies`, `states`, `reports`, `report_state`
  - Logic: Reports available based on company's state
  - Pricing: From `report_state.amount` table

### Key Features
1. **Unified Search**: Fast search across millions of records in both databases
2. **Country-Specific Logic**: Proper handling of different pricing and availability rules
3. **Cart System**: Session-based cart handling mixed reports from different countries
4. **Responsive UI**: Modern interface built with TailwindCSS v4

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js & NPM

### 1. Clone the Repository
```bash
git clone <repository-url>
cd evaluation-test
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the `.env.example` file and configure your database connections:

```env

# Singapore Database
DB_SG_HOST=127.0.0.1
DB_SG_PORT=3306
DB_SG_DATABASE=companies_house_sg
DB_SG_USERNAME=root
DB_SG_PASSWORD=

# Mexico Database
DB_MX_HOST=127.0.0.1
DB_MX_PORT=3306
DB_MX_DATABASE=companies_house_mx
DB_MX_USERNAME=root
DB_MX_PASSWORD=
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Build Assets
```bash
npm run build
```

### 7. Start the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🔧 Technical Implementation

### Models
- **Company**: Handles company data with country-specific logic
- **Report**: Manages report information and pricing
- **State**: Handles state information for MX companies

### Controllers
- **CompanySearchController**: Unified search across databases
- **CartController**: Cart management and report addition

### Database Connections
The system uses multiple database connections:
- `companies_house_sg` for Singapore data
- `companies_house_mx` for Mexico data
- Default connection for Laravel system tables

## 📱 User Interface

### Search Page (`/search`)
- Clean search form with placeholder text
- Results showing company name, country, registration number
- Visual indicators for different countries (SG/MX)
- Direct links to company details

### Company Details (`/company/{database}/{id}`)
- Company information display
- Available reports with pricing
- Country-specific logic explanation
- Add to cart functionality

### Shopping Cart (`/cart`)
- List of selected reports
- Country and database information
- Total price calculation
- Remove items and clear cart options

## 🎨 UI/UX Features

- **Responsive Design**: Mobile-first approach with TailwindCSS
- **Visual Feedback**: Success/error messages, loading states
- **Intuitive Navigation**: Clear breadcrumbs and navigation
- **Country Indicators**: Color-coded badges for SG/MX companies
- **Modern Icons**: SVG icons for better visual appeal


## 📊 Performance Considerations

- Efficient database queries with proper indexing
- Limited search results (50 per database)
- Optimized database connections
- Minimal data transfer between databases

## 🚀 Deployment


## 🔧 Adding New Countries

The system is designed to be easily extensible:

1. **Add new database connection** in `config/database.php`
2. **Update Company model** with new country logic
3. **Modify search controller** to include new database
4. **Update views** to handle new country codes


## 📝 API Endpoints

- `GET /search` - Search page
- `GET /search/results` - Search results
- `GET /company/{database}/{id}` - Company details
- `GET /cart` - Cart view
- `POST /cart/add` - Add to cart
- `DELETE /cart/{itemId}` - Remove from cart
- `POST /cart/clear` - Clear cart

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For questions or issues:
1. Check the documentation
2. Review existing issues
3. Create a new issue with detailed information

---

**Built using Laravel, Blade, and TailwindCSS**
