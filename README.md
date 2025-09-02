# Google reCAPTCHA v3 Extension for OpenCart

OpenCart 4 no longer ships with Google reCAPTCHA as a core module. This extension provides Google reCAPTCHA v3 integration for OpenCart 4. Unlike traditional CAPTCHAs, reCAPTCHA v3 runs in the background and provides a score based on user behavior, making it invisible to users while still protecting against bots.

## Features

- **Invisible Protection**: No user interaction required - runs in the background
- **Score-based Verification**: Provides a score from 0.0 (likely bot) to 1.0 (likely human)
- **Configurable Threshold**: Adjustable score threshold for different security levels
- **Easy Integration**: Drop-in replacement for existing captcha systems

Tested with OpenCart version: 4.1.0.3. (This extension was developed for my project on OpenCart 4.1.0.3. Please report any issues or confirm compatibility with other versions)

## Installation

1. Upload the extension files to your OpenCart extension folder
2. Go to Admin → Extensions → Extensions
3. Filter by "Captcha" type
4. Find "Google reCAPTCHA v3" and click "Install"
5. Click "Edit" to configure the extension

## Configuration

### Prerequisites

1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Create a new site registration
3. **IMPORTANT**: Choose **"reCAPTCHA v3"** as the type (NOT v2)
4. Add your domain(s) (e.g., `yourdomain.com`, `www.yourdomain.com`)
5. Accept the terms of service
6. Note down your **Site Key** and **Secret Key**
7. Make sure to select **"reCAPTCHA v3"** - this is crucial to avoid "Invalid key type" errors

### Settings

- **Status**: Enable/disable the extension
- **Site Key**: Your Google reCAPTCHA v3 site key
- **Secret Key**: Your Google reCAPTCHA v3 secret key
- **Score Threshold**: Minimum score required to pass verification (0.0-1.0, default: 0.5)

### Score Threshold Guidelines

- **0.0-0.3**: Very strict (may block legitimate users)
- **0.3-0.7**: Balanced (recommended range)
- **0.7-1.0**: Very lenient (may allow some bots)

## Usage

After installation and configuration, the reCAPTCHA will automatically be used in forms that have captcha enabled, such as:

- Contact forms
- Registration forms
- Comment forms
- Any form with captcha validation

## Technical Details

### How it Works

1. The extension loads the Google reCAPTCHA v3 JavaScript library
2. When a form is submitted, reCAPTCHA runs in the background
3. A token is generated and sent with the form data
4. The server validates the token with Google's API
5. The response includes a score that determines if the submission is allowed
6. The extension is built based on the structure of the built-in basic Captcha extension, no core files are edited

### Files Structure

```
extension/opencart/
├── admin/
│   ├── controller/captcha/recaptcha.php
│   ├── language/en-gb/captcha/recaptcha.php
│   └── view/template/captcha/recaptcha.twig
└── catalog/
    ├── controller/captcha/recaptcha.php
    ├── language/en-gb/captcha/recaptcha.php
    └── view/template/captcha/recaptcha.twig
```

## Troubleshooting

### Common Issues

1. **"ERROR for site owner: Invalid key type"**
   - **Cause**: You're using reCAPTCHA v2 keys instead of v3 keys
   - **Solution**: Go back to Google reCAPTCHA Admin Console and create a new site registration specifically for **reCAPTCHA v3**
   - **Note**: reCAPTCHA v2 and v3 use different key types and are not interchangeable

2. **"reCAPTCHA is not properly configured"**
   - Ensure both Site Key and Secret Key are entered correctly
   - Verify the keys are for reCAPTCHA v3, not v2
   - Check that your domain is correctly configured in Google reCAPTCHA console

3. **"Security verification failed"**
   - Check if the score threshold is set too high
   - Verify the domain is correctly configured in Google reCAPTCHA console
   - Ensure you're using reCAPTCHA v3 keys, not v2

4. **reCAPTCHA not loading**
   - Check if JavaScript is enabled in the browser
   - Verify the site key is correct
   - Check browser console for any JavaScript errors


