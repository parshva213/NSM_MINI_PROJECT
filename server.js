require('dotenv').config();
const express = require('express');
const session = require('express-session');
const pgSession = require('connect-pg-simple')(session);
const { Pool } = require('pg');
const path = require('path');
const bodyParser = require('body-parser');

const app = express();
const PORT = process.env.PORT || 3000;

// PostgreSQL pool
const pool = new Pool({
    connectionString: process.env.DATABASE_URL,
});

// Session store
app.use(session({
    store: new pgSession({ pool }),
    secret: process.env.SESSION_SECRET || 'default_secret',
    resave: false,
    saveUninitialized: false,
    cookie: { maxAge: 30 * 24 * 60 * 60 * 1000 }, // 30 days
}));

// Body parser
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

// Set EJS as templating engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Static files
app.use(express.static(path.join(__dirname)));

// Serve static JS files for captcha and popup
app.use('/captcha.js', express.static(path.join(__dirname, 'captcha.js')));
app.use('/aswl.js', express.static(path.join(__dirname, 'aswl.js')));

// Helper: hash password (use md5 for compatibility, but recommend bcrypt in production)
const crypto = require('crypto');
function md5(str) {
    return crypto.createHash('md5').update(str).digest('hex');
}

// Login route (GET)
app.get('/login', (req, res) => {
    res.render('login', { error: null, form: {} });
});

// Login route (POST)
app.post('/login', async (req, res) => {
    const { email, password, captcha, captchaSet } = req.body;
    let error = {};
    let form = { email, password, captcha };

    if (!email) error.email = 'Email is required';
    else if (!/^\S+@\S+\.\S+$/.test(email)) error.email = 'Please enter a valid email address';
    if (!password) error.password = 'Password is required';
    else if (password.length < 6) error.password = 'Password must be at least 6 characters long';
    else if (password.length > 12) error.password = 'Password must be at most 12 characters long';
    if (!captcha) error.captcha = 'Captcha is required';
    else if (captcha !== captchaSet) error.captcha = 'Captcha is incorrect';

    let user = null;
    if (Object.keys(error).length === 0) {
        try {
            const result = await pool.query('SELECT * FROM users WHERE email = $1', [email]);
            if (result.rows.length === 0) {
                error.email = 'No account found with this email.';
            } else {
                user = result.rows[0];
                if (md5(password) !== user.password) {
                    error.password = 'Incorrect password.';
                }
            }
        } catch (err) {
            error.database = 'Database error.';
        }
    }

    // Log error
    const dberror = Object.values(error).join(', ');
    await pool.query(
        'INSERT INTO login_errors(email, password, captcha, error_message) VALUES ($1, $2, $3, $4)',
        [email, password ? md5(password) : '', captcha, dberror]
    );

    if (Object.keys(error).length === 0 && user) {
        req.session.user = {
            id: user.id,
            email: user.email,
            full_name: user.full_name
        };
        return res.redirect('/');
    }
    res.render('login', { error, form });
});

// Register route (GET)
app.get('/register', (req, res) => {
    res.render('register', { error: null, form: {} });
});

// Register route (POST)
app.post('/register', async (req, res) => {
    const { fullName, email, password, confirmPassword, captcha, captchaSet } = req.body;
    let error = {};
    let form = { fullName, email, captcha };

    if (!fullName) error.fullName = 'Name is required';
    if (!email) error.email = 'Email is required';
    else if (!/^\S+@\S+\.\S+$/.test(email)) error.email = 'Please enter a valid email address';
    if (!password) error.password = 'Password is required';
    else if (password.length < 6) error.password = 'Password must be at least 6 characters long';
    else if (password.length > 12) error.password = 'Password must be at most 12 characters long';
    if (!confirmPassword) error.confirmPassword = 'Confirm Password is required';
    else if (password !== confirmPassword) error.confirmPassword = 'Passwords do not match';
    if (!captcha) error.captcha = 'Captcha is required';
    else if (captcha !== captchaSet) error.captcha = 'Captcha is incorrect';

    // Log error
    const dberror = Object.values(error).join(', ');
    await pool.query(
        'INSERT INTO register_errors(email, full_name, password, confirm_password, captcha, error_message) VALUES ($1, $2, $3, $4, $5, $6)',
        [email, fullName, password ? md5(password) : '', confirmPassword ? md5(confirmPassword) : '', captcha, dberror]
    );

    if (Object.keys(error).length === 0) {
        // Check if user exists
        const userResult = await pool.query('SELECT * FROM users WHERE email = $1', [email]);
        if (userResult.rows.length > 0) {
            error.email = 'You are already registered! Please log in.';
            return res.render('register', { error, form });
        }
        // Register user
        await pool.query(
            'INSERT INTO users (email, full_name, password) VALUES ($1, $2, $3)',
            [email, fullName, md5(password)]
        );
        return res.redirect('/login');
    }
    res.render('register', { error, form });
});

// Logout route
app.post('/logout', (req, res) => {
    req.session.destroy(() => {
        res.redirect('/');
    });
});

// Home route
app.get('/', (req, res) => {
    res.render('dashboard', { user: req.session.user || null });
});

// Placeholder for auth routes
// ...

app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
