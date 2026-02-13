# Quick Start Guide - Quran Database Web Application

## Installation & Setup

### 1. Prerequisites
- PHP 7.0 or higher (with SQLite support)
- Web browser (Chrome, Firefox, Safari, Edge)

### 2. Quick Start (Fastest Method)

**Step 1:** Navigate to the web directory
```bash
cd web
```

**Step 2:** Start the PHP built-in server
```bash
php -S localhost:8000
```

**Step 3:** Open your browser
```
http://localhost:8000
```

That's it! You're ready to browse the Quran database.

## Using Apache or Nginx

### Apache Setup
1. Copy the `web` folder to your Apache web root (e.g., `/var/www/html/quran`)
2. Ensure mod_rewrite is enabled (optional, for cleaner URLs)
3. Access via: `http://localhost/quran/`

### Nginx Setup
1. Copy the `web` folder to your Nginx web root
2. Configure Nginx to serve PHP files via PHP-FPM
3. Access via your configured domain

Example Nginx configuration:
```nginx
server {
    listen 80;
    server_name quran.local;
    root /path/to/web;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Using MySQL or PostgreSQL

By default, the application uses SQLite. To use MySQL or PostgreSQL:

### For MySQL

**Step 1:** Import the database
```bash
mysql -u username -p database_name < ../quran_mysql.sql
```

**Step 2:** Update `web/includes/db.php`
```php
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'quran_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### For PostgreSQL

**Step 1:** Import the database
```bash
psql -U username -d database_name -f ../quran_postgresql.sql
```

**Step 2:** Update `web/includes/db.php`
```php
define('DB_TYPE', 'postgresql');
define('PG_HOST', 'localhost');
define('PG_NAME', 'quran_db');
define('PG_USER', 'your_username');
define('PG_PASS', 'your_password');
```

## Features Overview

### Main Features
- ✅ Browse all 114 chapters of the Quran
- ✅ Search by Arabic or English names
- ✅ Read complete chapter texts with verse numbers
- ✅ Mobile-responsive design (works on phones, tablets, desktops)
- ✅ Beautiful Arabic typography
- ✅ Fast and lightweight

### Keyboard Shortcuts (on chapter pages)
- `→` or `n` - Next chapter
- `←` or `p` - Previous chapter
- `h` - Return to home/chapter list

## Customization

### Changing Colors
Edit `web/css/style.css` and modify the CSS variables:

```css
:root {
    --primary-color: #2c5f2d;      /* Main green */
    --secondary-color: #d4af37;     /* Gold accent */
    --text-dark: #1a1a1a;          /* Text color */
    /* ... other variables */
}
```

### Adding Features
All database functions are in `web/includes/db.php`. You can add:
- Bookmarking functionality
- Translation support
- Audio recitation links
- Tafsir (commentary) integration
- And more...

## Troubleshooting

### Issue: Database not found
**Solution:** Ensure the `quran.sqlite` file is in the parent directory of `web/`, or update the path in `web/includes/db.php`

### Issue: PHP errors
**Solution:** Check that PHP SQLite extension is enabled:
```bash
php -m | grep -i sqlite
```

### Issue: Blank page
**Solution:** Check PHP error logs:
```bash
php -S localhost:8000 2>&1 | tee server.log
```

### Issue: Arabic text not displaying correctly
**Solution:** Ensure your browser supports UTF-8 encoding and has Arabic fonts installed.

## Production Deployment

### Security Checklist
- [ ] Change default database credentials (if using MySQL/PostgreSQL)
- [ ] Enable HTTPS (SSL/TLS certificate)
- [ ] Set proper file permissions (644 for files, 755 for directories)
- [ ] Disable PHP error display in production
- [ ] Enable PHP-FPM for better performance
- [ ] Configure web server caching
- [ ] Use CDN for static assets (optional)

### Performance Tips
1. Enable OpCache in PHP configuration
2. Use HTTP/2 for faster loading
3. Enable gzip compression
4. Set proper cache headers for static files
5. Consider using a CDN for images

## Support & Credits

**Database:** Compiled by Bilal Bentoumi
**Web Application:** Built with PHP, HTML5, CSS3, JavaScript

**License:** Free to use - May Allah accept this effort 🤲

For issues or questions, please refer to the repository's issue tracker.

---

**May this application benefit the Muslim Ummah and bring you closer to the Quran! 📖**
