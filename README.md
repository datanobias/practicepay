# Dental Practice Pay Survey

A comprehensive web application for collecting and analyzing dental practice compensation data across the United States and Canada.

## Features

- **Survey Form**: Collect compensation data from dental professionals
- **Real-time Validation**: Client-side and server-side validation
- **Data Visualization**: Interactive charts showing average pay by location
- **Responsive Design**: Works on desktop and mobile devices
- **Security**: PDO prepared statements, input sanitization, and CSRF protection
- **Analytics**: Summary statistics and trend analysis

## Technology Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript, Chart.js
- **Backend**: PHP 7.4+ with PDO
- **Database**: MySQL 5.7+
- **Hosting**: Optimized for Hostinger Shared Hosting

## Quick Start

### Production Deployment (Hostinger)

**📋 See [DEPLOY.md](DEPLOY.md) for detailed deployment instructions**

#### Quick Steps:
1. **Push to Git** → Connect repository to Hostinger Git deployment
2. **Create MySQL Database** → Note credentials  
3. **Create config.php** → Add your database credentials
4. **Import database.sql** → Create tables via phpMyAdmin
5. **Test application** → Submit survey and view charts

#### Example Git Commands:
```bash
git remote add origin https://github.com/yourusername/dental-pay-survey.git
git push -u origin main
```

#### Example config.php for Hostinger:
```php
define('DB_HOST', 'mysql.hostinger.com');
define('DB_NAME', 'u395558639_dentalpay');
define('DB_USER', 'admin');  
define('DB_PASS', 'your_secure_password');
define('APP_ENV', 'production');
define('APP_DEBUG', false);
```

## Database Configuration

### Production Database Settings

```php
// config.php - Production settings
define('DB_HOST', 'mysql.hostinger.com');
define('DB_NAME', 'u123456_dental_survey');
define('DB_USER', 'u123456_user');
define('DB_PASS', 'your_secure_password');
define('APP_ENV', 'production');
define('APP_DEBUG', false);
```

### Database Schema

The application uses a single table `pay_surveys` with the following structure:

- `id`: Primary key (auto-increment)
- `name`: Full name (VARCHAR 100)
- `email`: Email address (VARCHAR 150)
- `role`: Job role (ENUM)
- `state`: State/Province (VARCHAR 50)
- `country`: Country (VARCHAR 50)
- `practice`: Practice name (VARCHAR 150, optional)
- `hourly_rate`: Hourly rate (DECIMAL 6,2)
- `submitted_at`: Timestamp (auto-generated)

## API Endpoints

### POST /submit.php
Submit survey data

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "role": "Dentist",
  "state": "CA",
  "country": "USA",
  "practice": "Smile Dental",
  "hourly_rate": "75.50"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Survey submitted successfully!",
  "id": 123
}
```

### GET /averages.php
Get average pay data by location

**Response:**
```json
{
  "data": [
    {
      "state": "CA",
      "country": "USA",
      "avg_rate": 75.50,
      "survey_count": 15
    }
  ],
  "summary": {
    "total_surveys": 150,
    "overall_average": 45.25,
    "min_rate": 15.00,
    "max_rate": 95.00,
    "locations_count": 25
  }
}
```

## Security Features

- **Input Validation**: Both client-side and server-side validation
- **SQL Injection Prevention**: PDO prepared statements
- **XSS Protection**: Input sanitization and output encoding
- **CSRF Protection**: Form tokens (implement if needed)
- **Rate Limiting**: Consider implementing for production
- **Email Validation**: Prevents duplicate submissions

## File Structure

```
dental-pay-survey/
├── index.html          # Main application page
├── config.php          # Database configuration
├── submit.php          # Form submission handler
├── averages.php        # Data API endpoint
├── styles.css          # Application styles
├── script.js           # Frontend JavaScript
├── database.sql        # Database schema
├── README.md           # This file
└── .gitignore          # Git ignore rules
```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Optimization

- **Caching**: Implement Redis/Memcached for production
- **Database Indexing**: Indexes on frequently queried columns
- **CDN**: Chart.js loaded from CDN
- **Minification**: Minify CSS/JS for production
- **Image Optimization**: Optimize any images used

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config.php`
   - Verify database server is running
   - Check database host and port

2. **Form Submission Fails**
   - Check PHP error logs
   - Verify all required fields are present
   - Check database table structure

3. **Chart Not Loading**
   - Ensure Chart.js CDN is accessible
   - Check console for JavaScript errors
   - Verify API endpoint returns valid JSON

4. **File Upload Issues**
   - Check file permissions (644 for files, 755 for directories)
   - Verify web server has write access to necessary directories
   - Check PHP configuration (upload_max_filesize, post_max_size)

### Debugging

Enable debug mode for development:
```php
define('APP_DEBUG', true);
```

Check PHP error logs:
```bash
tail -f /path/to/php/error.log
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For issues related to:
- **Hostinger Deployment**: Check Hostinger documentation
- **Database Issues**: Verify MySQL version compatibility
- **PHP Issues**: Ensure PHP 7.4+ with PDO extension

## Changelog

### Version 1.0.0
- Initial release
- Basic survey form and data visualization
- Hostinger deployment support
- Mobile responsive design

---

**Note**: Remember to update database credentials in `config.php` and never commit sensitive information to version control.
