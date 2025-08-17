# PHP CAPTCHA System - Login, Registration & Password Reset

A complete PHP-based authentication system with enhanced CAPTCHA protection for login, registration, and password reset functionality.

## 🚀 Features

### 🔐 Authentication Pages
- **Login Page** (`login.php`) - User authentication with CAPTCHA
- **Registration Page** (`register.php`) - New user registration with CAPTCHA
- **Forgot Password** (`forgot-password.php`) - Password reset with CAPTCHA verification

### 🛡️ Security Features
- **Image CAPTCHA** - HTML5 Canvas-generated with noise and rotation
- **Audio CAPTCHA** - Web Audio API for accessibility
- **Server-side Validation** - PHP validation for all forms
- **Honeypot Protection** - Hidden fields to catch bots
- **Rate Limiting** - Prevents brute force attacks
- **Time Validation** - Minimum form completion time
- **Session Management** - Secure session handling

### 🎨 UI/UX Features
- **Modern Design** - Bootstrap 5 with custom styling
- **Responsive Layout** - Works on all devices
- **Form Validation** - Real-time client-side validation
- **Error Handling** - Clear error messages
- **Success Feedback** - User-friendly success messages

## 📁 File Structure

```
NSM_MINI_PROJECT/
├── index.php              # Main entry point (redirects to login)
├── login.php              # Login page with CAPTCHA
├── register.php           # Registration page with CAPTCHA
├── forgot-password.php    # Password reset with CAPTCHA
├── login.css              # Custom styling
├── captcha.js             # Enhanced CAPTCHA system
├── README.md              # Original documentation
└── README_PHP.md          # This file
```

## 🛠️ Installation & Setup

### 1. Server Requirements
- **PHP 7.4+** (recommended PHP 8.0+)
- **Web Server** (Apache/Nginx)
- **JavaScript enabled** (for CAPTCHA functionality)

### 2. Installation Steps
1. **Upload files** to your web server directory
2. **Set permissions** (if needed):
   ```bash
   chmod 644 *.php *.css *.js
   ```
3. **Access the system** via your web browser:
   ```
   http://yourdomain.com/index.php
   ```

### 3. Database Setup (Optional)
For production use, you'll need to set up a database. The code includes commented examples for:
- User registration
- Password hashing
- Session management
- Email functionality

## 📖 Usage Guide

### Login Page (`login.php`)
- **Email & Password** - Standard authentication
- **CAPTCHA Verification** - Image or audio CAPTCHA
- **Links to** - Registration and Forgot Password

### Registration Page (`register.php`)
- **Full Name** - User's complete name
- **Email** - Unique email address
- **Password** - Minimum 6 characters
- **Confirm Password** - Must match
- **CAPTCHA** - Required verification

### Forgot Password (`forgot-password.php`)
- **Email Entry** - Enter registered email
- **CAPTCHA** - Verify human user
- **Reset Process** - Token-based password reset

## 🔧 Configuration

### CAPTCHA Settings
Edit `captcha.js` to customize:
```javascript
// Code length (default: 6 characters)
const codeLength = 6;

// Character set
const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

// Security timeouts
const minFormTime = 3000; // 3 seconds
const maxAttempts = 5;    // 5 attempts
const lockoutTime = 300000; // 5 minutes
```

### Styling Customization
Modify `login.css` for:
- Color schemes
- Button styles
- Form layouts
- CAPTCHA appearance

## 🗄️ Database Integration

### Sample Database Schema
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(64) NULL,
    reset_expiry DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Database Connection Example
```php
// config/database.php
<?php
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

## 🔒 Security Best Practices

### 1. Production Deployment
- **Use HTTPS** - Always use SSL/TLS
- **Database Security** - Use prepared statements
- **Password Hashing** - Use `password_hash()` and `password_verify()`
- **Session Security** - Configure secure session settings
- **Input Validation** - Validate all user inputs
- **Error Handling** - Don't expose sensitive information

### 2. Email Configuration
For password reset functionality:
```php
// Configure PHP mail settings
ini_set('SMTP', 'your_smtp_server');
ini_set('smtp_port', '587');

// Or use PHPMailer for better email handling
```

### 3. Rate Limiting
The system includes basic rate limiting. For production:
- Implement IP-based rate limiting
- Use Redis or database for tracking
- Consider using services like Cloudflare

## 🐛 Troubleshooting

### Common Issues

1. **CAPTCHA not displaying**
   - Check JavaScript console for errors
   - Verify `captcha.js` is loaded
   - Ensure HTML5 Canvas is supported

2. **Audio CAPTCHA not working**
   - Check browser permissions
   - Verify Web Audio API support
   - Test in different browsers

3. **Form validation errors**
   - Check jQuery and validation plugin loading
   - Verify form field names match
   - Check PHP error logs

4. **Styling issues**
   - Verify CSS file path
   - Check Bootstrap CDN loading
   - Clear browser cache

### Debug Mode
Enable debug logging in `captcha.js`:
```javascript
const DEBUG = true;
if (DEBUG) {
    console.log('CAPTCHA generated:', code);
}
```

## 🔄 API Integration

### AJAX Form Submission
For dynamic form handling:
```javascript
$('#loginForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'login.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // Handle success
        },
        error: function(xhr, status, error) {
            // Handle errors
        }
    });
});
```

## 📱 Mobile Responsiveness

The system is fully responsive and works on:
- **Desktop browsers** - Chrome, Firefox, Safari, Edge
- **Mobile browsers** - iOS Safari, Chrome Mobile
- **Tablet browsers** - iPad Safari, Android Chrome

## 🔄 Version History

### v2.0 (Current)
- ✅ Separate PHP files for each function
- ✅ Enhanced CAPTCHA system
- ✅ Password reset functionality
- ✅ Server-side validation
- ✅ Security features

### v1.0 (Previous)
- ✅ Basic CAPTCHA implementation
- ✅ Combined login/registration
- ✅ Client-side validation

## 📄 License

This project is open source and available under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📞 Support

For issues and questions:
- Check the troubleshooting section
- Review the code comments
- Test in different environments
- Check browser compatibility

---

**Note**: This is a demonstration implementation. For production use, ensure proper security measures, database integration, and email configuration are in place.