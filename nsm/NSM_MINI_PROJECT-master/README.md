# Enhanced CAPTCHA System for Login & Registration Forms

This project implements a comprehensive CAPTCHA system with multiple security features to prevent bot attacks on your login and registration forms.

## Features

### 🛡️ Security Features

1. **Image-based CAPTCHA**
   - Dynamically generated using HTML5 Canvas
   - Random colors, rotation, and positioning
   - Noise and line interference to prevent OCR
   - 6-character alphanumeric codes

2. **Audio CAPTCHA**
   - Audio playback of the CAPTCHA code
   - Accessible for visually impaired users
   - Uses Web Audio API for tone generation

3. **Honeypot Protection**
   - Hidden form fields to catch automated bots
   - Blocks submissions if honeypot fields are filled

4. **Time-based Validation**
   - Prevents rapid form submissions (minimum 3 seconds)
   - Helps identify automated form filling

5. **Rate Limiting**
   - Maximum 5 attempts before 5-minute lockout
   - Prevents brute force attacks

### 🎨 UI/UX Features

- **Modern Design**: Bootstrap 5 with custom styling
- **Responsive Layout**: Works on desktop and mobile devices
- **Visual Feedback**: Hover effects and animations
- **Accessibility**: Audio CAPTCHA support
- **User-Friendly**: Clear error messages and validation

## File Structure

```
NSM_MINI_PROJECT/
├── login.html          # Main HTML file with forms
├── login.css           # Custom styling
├── captcha.js          # Enhanced CAPTCHA system
└── README.md           # This file
```

## How to Use

### 1. Basic Setup

1. Open `login.html` in a web browser
2. The page displays both login and registration forms side by side
3. Each form has its own CAPTCHA system

### 2. CAPTCHA Features

#### Image CAPTCHA
- A 6-character code is displayed as an image
- Click the refresh button (↻) to generate a new code
- Enter the code in the input field

#### Audio CAPTCHA
- Click the audio button (🔊) to hear the code
- Useful for accessibility and when image is unclear
- Each character is played as a different tone

### 3. Form Validation

The system includes comprehensive validation:

- **Email**: Must be a valid email format
- **Password**: Minimum 6 characters
- **Full Name**: Minimum 2 characters (registration only)
- **Confirm Password**: Must match password (registration only)
- **CAPTCHA**: Must match the displayed code exactly

### 4. Security Measures

- **Honeypot**: Hidden fields that bots might fill
- **Time Validation**: Forms must take at least 3 seconds to complete
- **Rate Limiting**: 5 attempts maximum before lockout
- **Case Insensitive**: CAPTCHA validation ignores case

## Technical Implementation

### CAPTCHA Generation

```javascript
// Generate random 6-character code
function generateCaptcha() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return code;
}
```

### Image CAPTCHA Creation

The system uses HTML5 Canvas to create:
- Random background colors
- Rotated and positioned text
- Noise dots and interference lines
- Different colors for each character

### Audio CAPTCHA

Uses Web Audio API to:
- Generate different frequencies for each character
- Play tones in sequence
- Create accessible audio version

### Security Validation

```javascript
// Validate CAPTCHA input
validateCaptcha(input, formType) {
    const expected = $(`#${formType}CaptchaSet`).val();
    return input.toLowerCase() === expected.toLowerCase();
}
```

## Browser Compatibility

- **Modern Browsers**: Chrome, Firefox, Safari, Edge
- **Audio Support**: Requires Web Audio API support
- **Canvas Support**: Requires HTML5 Canvas support
- **JavaScript**: Requires ES6+ support

## Customization

### Styling

Modify `login.css` to customize:
- Colors and gradients
- Button styles
- Form layout
- CAPTCHA appearance

### CAPTCHA Settings

In `captcha.js`, you can adjust:
- Code length (currently 6 characters)
- Character set
- Image dimensions
- Audio frequency mapping
- Security timeouts

### Security Levels

Adjust security parameters:
- Minimum form completion time
- Maximum attempts before lockout
- Lockout duration
- Honeypot field names

## Server-Side Integration

To integrate with your backend:

1. **Remove demo alerts**: Uncomment `form.submit()` in the submit handlers
2. **Server validation**: Always validate CAPTCHA on the server side
3. **Session management**: Store CAPTCHA codes in server sessions
4. **Rate limiting**: Implement server-side rate limiting
5. **Logging**: Log failed attempts for security monitoring

### Example Server-Side Validation (PHP)

```php
<?php
session_start();

if ($_POST['captcha'] !== $_SESSION['captcha_code']) {
    die('Invalid CAPTCHA');
}

// Process form data
?>
```

## Security Best Practices

1. **Never trust client-side validation alone**
2. **Always validate CAPTCHA on the server**
3. **Use HTTPS in production**
4. **Implement proper session management**
5. **Log security events**
6. **Regularly update dependencies**
7. **Monitor for unusual activity**

## Troubleshooting

### Common Issues

1. **Audio not working**: Check browser permissions and Web Audio API support
2. **Canvas not displaying**: Ensure HTML5 Canvas is supported
3. **Validation errors**: Check jQuery and validation plugin loading
4. **Styling issues**: Verify CSS file path and Bootstrap CDN

### Debug Mode

Add this to enable debug logging:

```javascript
// In captcha.js
const DEBUG = true;

if (DEBUG) {
    console.log('CAPTCHA generated:', code);
}
```

## License

This project is open source and available under the MIT License.

## Contributing

Feel free to submit issues and enhancement requests!

---

**Note**: This is a demonstration implementation. For production use, ensure proper server-side validation and security measures are in place.