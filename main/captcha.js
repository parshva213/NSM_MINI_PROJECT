// Enhanced CAPTCHA System
class CaptchaSystem {
    constructor() {
        this.canvas = null;
        this.ctx = null;
        this.currentCaptcha = '';
        this.audioContext = null;
        this.init();
    }

    init() {
        this.setupCanvas();
        this.setupAudio();
        this.bindEvents();
    }

    setupCanvas() {
        // Create canvas for image captcha
        this.canvas = document.createElement('canvas');
        this.canvas.width = 200;
        this.canvas.height = 60;
        this.ctx = this.canvas.getContext('2d');
    }

    setupAudio() {
        // Initialize audio context for audio captcha
        try {
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        } catch (e) {
            console.log('Audio context not supported');
        }
    }

    bindEvents() {
        // Bind reload buttons
        $('#reloadLoginCaptcha').click(() => this.generateCaptcha('login'));
        $('#reloadRegisterCaptcha').click(() => this.generateCaptcha('register'));
        $('#reloadForgotCaptcha').click(() => this.generateCaptcha('forgot'));
        $('#reloadResetCaptcha').click(() => this.generateCaptcha('reset'));

        // Bind audio buttons
        $('#audioLoginCaptcha').click(() => this.playAudioCaptcha('login'));
        $('#audioRegisterCaptcha').click(() => this.playAudioCaptcha('register'));
        $('#audioForgotCaptcha').click(() => this.playAudioCaptcha('forgot'));
        $('#audioResetCaptcha').click(() => this.playAudioCaptcha('reset'));
    }

    generateCaptcha(formType) {
        // Generate random captcha text
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let code = '';
        for (let i = 0; i < 6; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        // Store the captcha
        this.currentCaptcha = code;
        $(`#${formType}CaptchaSet`).val(code);

        // Generate image captcha
        this.generateImageCaptcha(code, formType);

        // Generate audio captcha
        this.generateAudioCaptcha(code, formType);
    }

    generateImageCaptcha(text, formType) {
        const canvas = this.canvas;
        const ctx = this.ctx;

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Set background
        ctx.fillStyle = '#f0f0f0';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Add noise
        this.addNoise(ctx, canvas.width, canvas.height);

        // Add lines
        this.addLines(ctx, canvas.width, canvas.height);

        // Add text
        this.addText(ctx, text, canvas.width, canvas.height);

        // Convert to image and display
        const imageUrl = canvas.toDataURL();
        $(`#${formType}CaptchaDisplay`).html(`<img src="${imageUrl}" alt="CAPTCHA" style="width: 200px; height: 60px; border-radius: 8px;">`);
    }

    addNoise(ctx, width, height) {
        // Add random dots
        for (let i = 0; i < 100; i++) {
            ctx.fillStyle = `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.3)`;
            ctx.fillRect(Math.random() * width, Math.random() * height, 2, 2);
        }
    }

    addLines(ctx, width, height) {
        // Add random lines
        for (let i = 0; i < 5; i++) {
            ctx.strokeStyle = `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.5)`;
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(Math.random() * width, Math.random() * height);
            ctx.lineTo(Math.random() * width, Math.random() * height);
            ctx.stroke();
        }
    }

    addText(ctx, text, width, height) {
        const fontSize = 24;
        const letterSpacing = 25;
        const startX = 20;
        const startY = height / 2 + fontSize / 3;

        for (let i = 0; i < text.length; i++) {
            const char = text[i];
            const x = startX + i * letterSpacing;
            const y = startY + (Math.random() - 0.5) * 10;

            // Random color for each character
            ctx.fillStyle = `rgb(${Math.random() * 100 + 50}, ${Math.random() * 100 + 50}, ${Math.random() * 100 + 50})`;
            ctx.font = `${fontSize}px Arial`;
            ctx.textAlign = 'center';

            // Add rotation
            ctx.save();
            ctx.translate(x, y);
            ctx.rotate((Math.random() - 0.5) * 0.3);
            ctx.fillText(char, 0, 0);
            ctx.restore();
        }
    }

    generateAudioCaptcha(text, formType) {
        if (!this.audioContext) return;

        // Create audio sequence for each character
        const audioSequence = text.split('').map(char => {
            const charCode = char.charCodeAt(0);
            const frequency = 440 + (charCode % 26) * 50; // Map to frequency
            return this.createTone(frequency, 0.3);
        });

        // Store audio sequence
        this[`${formType}AudioSequence`] = audioSequence;
    }

    createTone(frequency, duration) {
        const oscillator = this.audioContext.createOscillator();
        const gainNode = this.audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(this.audioContext.destination);

        oscillator.frequency.value = frequency;
        oscillator.type = 'sine';

        gainNode.gain.setValueAtTime(0.1, this.audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + duration);

        return { oscillator, gainNode, duration };
    }

    playAudioCaptcha(formType) {
        if (!this.audioContext || !this[`${formType}AudioSequence`]) return;

        const sequence = this[`${formType}AudioSequence`];
        let currentTime = this.audioContext.currentTime;

        sequence.forEach((tone, index) => {
            tone.oscillator.start(currentTime + index * 0.4);
            tone.oscillator.stop(currentTime + index * 0.4 + tone.duration);
        });
    }

    validateCaptcha(input, formType) {
        const expected = $(`#${formType}CaptchaSet`).val();
        return input === expected; // Case-sensitive validation
    }

    // Additional security features
    addHoneypot(formType) {
        // Honeypot disabled - no alerts will be shown
        // This feature can be re-enabled by uncommenting the code below
        return; // Early return to disable honeypot

        /*
        // Add hidden field to catch bots
        const honeypot = $(`<input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">`);
        $(`#${formType}Form`).append(honeypot);

        // If honeypot is filled, it's likely a bot
        $(`#${formType}Form`).on('submit', function (e) {
            if ($(this).find('input[name="website"]').val()) {
                e.preventDefault();
                alert('Form submission blocked - potential bot detected');
                return false;
            }
        });
        */
    }

    addTimeValidation(formType) {
        // Time validation disabled - no alert will be shown
        // This feature can be re-enabled by uncommenting the code below
        return; // Early return to disable time validation

        /*
        let startTime = Date.now();

        $(`#${formType}Form`).on('submit', function (e) {
            const timeElapsed = Date.now() - startTime;

            // If form is submitted too quickly (less than 3 seconds), it might be a bot
            if (timeElapsed < 3000) {
                e.preventDefault();
                alert('Please take your time to fill out the form');
                return false;
            }
        });
        */
    }

    addRateLimiting(formType) {
        // Rate limiting disabled - no alerts will be shown
        // This feature can be re-enabled by uncommenting the code below
        return; // Early return to disable rate limiting

        /*
        let attempts = 0;
        const maxAttempts = 5;
        const lockoutTime = 300000; // 5 minutes
        let lockoutUntil = 0;

        $(`#${formType}Form`).on('submit', function (e) {
            const now = Date.now();

            if (now < lockoutUntil) {
                e.preventDefault();
                const remainingTime = Math.ceil((lockoutUntil - now) / 1000 / 60);
                alert(`Too many attempts. Please wait ${remainingTime} minutes before trying again.`);
                return false;
            }

            attempts++;

            if (attempts >= maxAttempts) {
                lockoutUntil = now + lockoutTime;
                attempts = 0;
                e.preventDefault();
                alert('Too many failed attempts. Please wait 5 minutes before trying again.');
                return false;
            }
        });
        */
    }
}

// Success popup function
function showSuccessPopup(message, duration = 10000) {
    // Create popup element
    const popup = $(`
        <div id="successPopup" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            font-weight: 600;
            text-align: center;
            min-width: 300px;
            animation: popupSlideIn 0.5s ease-out;
        ">
            <div style="font-size: 18px; margin-bottom: 10px;">✅</div>
            <div style="font-size: 16px;">${message}</div>
            <div style="font-size: 12px; margin-top: 10px; opacity: 0.8;">This popup will close automatically</div>
        </div>
    `);

    // Add CSS animation
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            @keyframes popupSlideIn {
                from {
                    opacity: 0;
                    transform: translate(-50%, -60%);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }
            @keyframes popupSlideOut {
                from {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
                to {
                    opacity: 0;
                    transform: translate(-50%, -60%);
                }
            }
        `)
        .appendTo('head');

    // Add popup to body
    $('body').append(popup);

    // Auto remove after duration
    setTimeout(function () {
        popup.css('animation', 'popupSlideOut 0.5s ease-in');
        setTimeout(function () {
            popup.remove();
        }, 500);
    }, duration);
}

// Initialize captcha system when document is ready
$(document).ready(function () {
    window.captchaSystem = new CaptchaSystem();

    // Generate initial captchas based on current page
    const currentPage = window.location.pathname.split('/').pop().replace('.php', '');

    if (currentPage === 'login') {
        captchaSystem.generateCaptcha('login');
    } else if (currentPage === 'register') {
        captchaSystem.generateCaptcha('register');
    } else if (currentPage === 'forgot-password') {
        // Check if we're in reset mode or forgot mode
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');

        if (token) {
            captchaSystem.generateCaptcha('reset');
        } else {
            captchaSystem.generateCaptcha('forgot');
        }
    }

    // Add security features
    captchaSystem.addHoneypot('login');
    captchaSystem.addHoneypot('register');
    captchaSystem.addHoneypot('forgot');
    captchaSystem.addHoneypot('reset');
    captchaSystem.addTimeValidation('login');
    captchaSystem.addTimeValidation('register');
    captchaSystem.addTimeValidation('forgot');
    captchaSystem.addTimeValidation('reset');
    captchaSystem.addRateLimiting('login');
    captchaSystem.addRateLimiting('register');
    captchaSystem.addRateLimiting('forgot');
    captchaSystem.addRateLimiting('reset');

    // Form validation with captcha
    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 12
            },
            captcha: {
                required: true,
                customCaptcha: function () {
                    const input = $('#loginCaptchaInput').val();
                    const expected = $('#loginCaptchaSet').val();
                    return input === expected; // Case-sensitive validation
                }
            }
        },
        messages: {
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 6 characters",
                maxlength: "Password must not exceed 12 characters"
            },
            captcha: {
                required: "Please enter the captcha",
                customCaptcha: "Captcha code is incorrect"
            }
        },
        errorClass: "error",
        highlight: function (element) {
            $(element).addClass('error');
        },
        unhighlight: function (element) {
            $(element).removeClass('error');
        },
        submitHandler: function (form) {
            // Show success popup for 10 seconds
            showSuccessPopup('Login form submitted successfully!', 10000);
            return true;
        }
    });

    $('#registerForm').validate({
        rules: {
            fullName: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 12
            },
            confirmPassword: {
                required: true,
                equalTo: '#registerPassword'
            },
            captcha: {
                required: true,
                customCaptcha: function () {
                    const input = $('#registerCaptchaInput').val();
                    const expected = $('#registerCaptchaSet').val();
                    return input === expected; // Case-sensitive validation
                }
            }
        },
        messages: {
            fullName: {
                required: "Please enter your full name",
                minlength: "Name must be at least 2 characters"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters",
                maxlength: "Password must not exceed 12 characters"
            },
            confirmPassword: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
            },
            captcha: {
                required: "Please enter the captcha",
                customCaptcha: "Captcha code is incorrect"
            }
        },
        errorClass: "error",
        highlight: function (element) {
            $(element).addClass('error');
        },
        unhighlight: function (element) {
            $(element).removeClass('error');
        },
        submitHandler: function (form) {
            // Show success popup for 10 seconds
            showSuccessPopup('Registration form submitted successfully!', 10000);
            return true;
        }
    });

    // Add custom validation method
    $.validator.addMethod('customCaptcha', function (value, element) {
        const formType = element.id.includes('login') ? 'login' : 'register';
        const expected = $(`#${formType}CaptchaSet`).val();
        return value === expected; // Case-sensitive validation
    }, 'Captcha code is incorrect');
});

// Manual captcha generation as backup
function generateManualCaptcha(formType) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    $(`#${formType}CaptchaSet`).val(code);
    $(`#${formType}CaptchaDisplay`).text(code);

    return code;
}
