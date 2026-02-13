# Quran Database Web Application

A beautiful, responsive PHP web application for browsing the Holy Quran database.

## Features

- 📱 **Mobile-Responsive Design** - Works perfectly on smartphones, tablets, and desktops
- 🔍 **Search Functionality** - Search chapters by Arabic or English names
- 📖 **Translation Support** - View English translations and transliterations alongside Arabic text
- 🎨 **Beautiful UI** - Modern, clean interface with Islamic-themed colors
- ⚡ **Fast & Lightweight** - Minimal dependencies, pure PHP and vanilla JavaScript
- 🌐 **RTL Support** - Proper right-to-left display for Arabic text
- ⌨️ **Keyboard Navigation** - Navigate between chapters using arrow keys
- 📝 **Easy Reading** - Clear typography optimized for Quranic text

## Available Translations

The application includes support for XML translation files:

- **Pickthall Translation** - English translation by Mohammed Marmaduke Pickthall
- **English Transliteration** - Romanized Arabic transliteration

Additional translations can be added by placing XML files in the root directory.

## Requirements

- PHP 7.0 or higher
- SQLite extension (usually enabled by default)
- Web server (Apache, Nginx, or PHP built-in server)

## Installation

### Option 1: Using PHP Built-in Server (Quickest)

```bash
cd web
php -S localhost:8000
```

Then open your browser to: `http://localhost:8000`

### Option 2: Using Apache

1. Copy the `web` folder to your Apache web root (e.g., `/var/www/html/quran`)
2. Ensure the database file is accessible (adjust path in `includes/db.php` if needed)
3. Access via: `http://localhost/quran/`

### Option 3: Using Nginx

1. Copy the `web` folder to your Nginx web root
2. Configure Nginx to serve PHP files
3. Access via your configured domain/path

## Configuration

The database configuration is in `web/includes/db.php`. By default, it uses the SQLite database from the parent directory.

To use MySQL or PostgreSQL instead:

1. Import the appropriate SQL file to your database:
   ```bash
   # For MySQL
   mysql -u username -p database_name < ../quran_mysql.sql
   
   # For PostgreSQL
   psql -U username -d database_name -f ../quran_postgresql.sql
   ```

2. Edit `web/includes/db.php` and change:
   ```php
   define('DB_TYPE', 'mysql'); // or 'postgresql'
   ```

3. Update the connection credentials in the same file.

## File Structure

```
web/
├── index.php           # Main page - lists all chapters
├── chapter.php         # Chapter view - displays verses with translations
├── css/
│   └── style.css      # Responsive stylesheet
├── js/
│   └── app.js         # JavaScript for interactivity
└── includes/
    ├── db.php         # Database configuration and functions
    └── translations.php  # Translation loading and caching
```

## Features Explained

### Search
- Type in Arabic or English to search for chapters
- Real-time filtering of chapters
- Clear button to reset search

### Translation Support
- Select from available translations using the dropdown menu
- View Arabic text alongside English translation or transliteration
- Translation selection is preserved in your session
- Automatic caching for better performance

### Chapter View
- Beautiful display of verses with proper Arabic typography
- Optional translation display below each verse
- Verse numbers displayed clearly
- Navigation to previous/next chapters
- Keyboard shortcuts:
  - `→` or `n` - Next chapter
  - `←` or `p` - Previous chapter
  - `h` - Home (chapter list)

### Responsive Design
- Mobile-first approach
- Optimized for all screen sizes
- Touch-friendly interface on mobile devices
- Smooth animations and transitions

## Customization

### Colors
Edit the CSS variables in `web/css/style.css`:

```css
:root {
    --primary-color: #2c5f2d;      /* Main green color */
    --secondary-color: #d4af37;     /* Gold accent */
    --text-dark: #1a1a1a;          /* Dark text */
    /* ... more variables */
}
```

### Layout
All styling is in `web/css/style.css` - modify as needed.

### Functionality
Database functions are in `web/includes/db.php` - add more as needed.

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

This web application is free to use, just like the database itself.

**May Allah accept this effort and benefit the Muslim Ummah 🤲**

## Credits

- Database: Compiled by Bilal Bentoumi
- Web Application: Built with PHP, HTML5, CSS3, and JavaScript
