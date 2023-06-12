# API-NBP - Smart App for Fetching Current Values of Currencies

API-NBP is a web application that allows you to fetch the current values of currencies using the NBP API. This project is developed in PHP 
(MVC architecture) and utilizes a MySQL database for storing the fetched data.

## Getting Started

Visit https://api-nbp-app.000webhostapp.com/ or to get started with the project locally, follow the instructions below:

### Prerequisites

- PHP (version 7 or higher)
- MySQL database
- Local web server (e.g., XAMPP, WAMP, or MAMP)

### Installation

1. Clone the repository to your local machine or download the code as a ZIP file.
2. Set up a local web server environment using XAMPP (or your preferred server solution).
3. Create a new MySQL database for the project. You can use phpMyAdmin or any other database management tool.
4. Import the SQL schema provided below into your newly created database:

```sql
CREATE TABLE `currency` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `currency` VARCHAR(100) NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    `bid` VARCHAR(100) NOT NULL,
    `ask` VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
```

```sql
CREATE TABLE `conversions` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `from_currency	` VARCHAR(10) NOT NULL,
    `to_currency	` VARCHAR(10) NOT NULL,
    `result` VARCHAR(100) NOT NULL,
    `amount` VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
```

### Configuration

1. Locate the `App\Config.php` file in the project directory.
2. Open the `Config.php` file in a text editor.
3. Update the database connection details with your MySQL credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
```

### Running the Application

1. Start your local web server (e.g., XAMPP).
2. Launch your web browser and navigate to `http://localhost:8000` (or the corresponding URL for your local server).
3. The application will be up and running, and you can start fetching currency data from the NBP API.

## Usage

- On the homepage, you will see the fetched currency data displayed in a table.
- The table includes columns for currency name, currency code, and mid-value (average exchange rate).
- The table is automatically updated with the latest data fetched from the NBP API.
- Use a form that will convert the provided amount from one currency to another, using data from the database.
- Display a list of the latest conversion results, including information about the source currency, target currency, and converted amount.
- You can customize the application further to suit your needs.

## Contributing

This project is open to contributions. If you find any issues or have suggestions for improvements, feel free to submit a pull request.

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Acknowledgements

- This project utilizes the NBP API to fetch currency data.
- Special thanks to the developers and contributors of the tools and libraries used in this project.

Feel free to modify the README according to your project's specific details and requirements.








