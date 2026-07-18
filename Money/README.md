# Nilepet Website Backend Setup

## Requirements
- PHP 8+
- MySQL 8+
- Web server with PHP support (Apache/Nginx/XAMPP/WAMP)

## Database setup
1. Start MySQL and create a database user if needed.
2. Open the following URL in your browser after placing the files in the web root:
   - http://localhost/php/setup_db.php
3. If your MySQL credentials differ, create environment variables or edit the values in php/db.php.

## Security notes
- Uploaded files are stored outside the web root in the php/uploads folder.
- The site uses server-side validation and prepared statements.
- The .htaccess file adds basic hardening headers.
