<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Meme Creator') }} - Authentication</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="auth-container">
            <!-- Animated Background -->
            <div class="auth-background"></div>
            
            <!-- Auth Card -->
            <div class="auth-card animate-scale-in">
                <!-- Logo/Title -->
                <div class="auth-header">
                    <a href="/" class="auth-logo">
                        <span class="logo-icon">🎨</span>
                        <span class="logo-text">Meme Creator</span>
                    </a>
                    <p class="auth-subtitle">{{ $title ?? 'Welcome back!' }}</p>
                </div>

                <!-- Content -->
                <div class="auth-content">
                    {{ $slot }}
                </div>
            </div>
            
            <!-- Footer -->
            <div class="auth-footer">
                <p>&copy; {{ date('Y') }} Meme Creator. All rights reserved.</p>
            </div>
        </div>

        <style>
            /* Auth Container */
            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 20px;
                position: relative;
                overflow: hidden;
            }

            /* Animated Background */
            .auth-background {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                z-index: -2;
            }

            .auth-background::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: 
                    radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.4), transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(252, 70, 107, 0.4), transparent 50%),
                    radial-gradient(circle at 40% 20%, rgba(99, 179, 237, 0.4), transparent 50%);
                animation: backgroundPulse 15s ease-in-out infinite;
            }

            @keyframes backgroundPulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.8; transform: scale(1.1); }
            }

            /* Auth Card */
            .auth-card {
                width: 100%;
                max-width: 450px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-radius: 24px;
                padding: 40px;
                box-shadow: 
                    0 20px 60px rgba(0, 0, 0, 0.3),
                    0 0 0 1px rgba(255, 255, 255, 0.3);
                position: relative;
                z-index: 1;
            }

            /* Auth Header */
            .auth-header {
                text-align: center;
                margin-bottom: 35px;
            }

            .auth-logo {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                margin-bottom: 15px;
            }

            .logo-icon {
                font-size: 3rem;
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }

            .logo-text {
                font-size: 2rem;
                font-weight: 800;
                background: var(--primary-gradient);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .auth-subtitle {
                color: #666;
                font-size: 1rem;
                margin: 10px 0 0 0;
                font-weight: 500;
            }

            /* Auth Content */
            .auth-content {
                margin-top: 30px;
            }

            /* Form Styles */
            .auth-content form {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .form-group {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                font-size: 0.9rem;
                margin-left: 4px;
            }

            .form-input {
                width: 100%;
                padding: 14px 18px;
                border: 2px solid rgba(0, 0, 0, 0.1);
                border-radius: 12px;
                font-size: 1rem;
                transition: var(--transition-smooth);
                background: rgba(255, 255, 255, 0.8);
                font-family: 'Inter', sans-serif;
            }

            .form-input:focus {
                outline: none;
                border-color: #667eea;
                background: white;
                box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
                transform: translateY(-2px);
            }

            .form-input::placeholder {
                color: #999;
            }

            .form-checkbox {
                display: flex;
                align-items: center;
                gap: 12px;
                cursor: pointer;
                user-select: none;
                padding: 8px 0;
                position: relative;
            }

            .form-checkbox input[type="checkbox"] {
                position: absolute;
                opacity: 0;
                cursor: pointer;
                width: 0;
                height: 0;
            }

            .form-checkbox label {
                color: #666;
                font-size: 0.9rem;
                cursor: pointer;
                display: inline-block;
                font-weight: 500;
                position: relative;
                padding-left: 32px;
                transition: var(--transition-smooth);
            }

            .form-checkbox label:hover {
                color: #333;
            }

            /* Custom checkbox */
            .form-checkbox label::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 22px;
                height: 22px;
                border: 2px solid rgba(0, 0, 0, 0.2);
                border-radius: 6px;
                background: rgba(255, 255, 255, 0.8);
                transition: var(--transition-smooth);
            }

            /* Checkmark */
            .form-checkbox label::after {
                content: '✓';
                position: absolute;
                left: 5px;
                top: 50%;
                transform: translateY(-50%) scale(0);
                color: white;
                font-size: 14px;
                font-weight: bold;
                transition: transform 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }

            /* Checked state */
            .form-checkbox input[type="checkbox"]:checked + label::before {
                background: var(--primary-gradient);
                border-color: #667eea;
                box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
            }

            .form-checkbox input[type="checkbox"]:checked + label::after {
                transform: translateY(-50%) scale(1);
            }

            /* Focus state */
            .form-checkbox input[type="checkbox"]:focus + label::before {
                box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            }

            /* Hover state */
            .form-checkbox:hover label::before {
                border-color: #667eea;
                background: white;
            }

            .form-link {
                color: #667eea;
                text-decoration: none;
                font-size: 0.9rem;
                font-weight: 600;
                transition: var(--transition-smooth);
            }

            .form-link:hover {
                color: #764ba2;
                text-decoration: underline;
            }

            .form-actions {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 10px;
                flex-wrap: wrap;
                gap: 15px;
            }

            .auth-button {
                padding: 14px 32px;
                background: var(--primary-gradient);
                color: white;
                border: none;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: var(--transition-smooth);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                position: relative;
                overflow: hidden;
            }

            .auth-button::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: translate(-50%, -50%);
                transition: width 0.6s, height 0.6s;
            }

            .auth-button:hover::before {
                width: 300px;
                height: 300px;
            }

            .auth-button:hover {
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
                transform: translateY(-2px);
            }

            .auth-button:active {
                transform: translateY(0);
            }

            /* Error Messages */
            .error-message {
                color: #dc3545;
                font-size: 0.85rem;
                margin-top: 5px;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .error-message::before {
                content: '⚠️';
            }

            /* Success Messages */
            .success-message {
                background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                color: #155724;
                padding: 12px 18px;
                border-radius: 12px;
                margin-bottom: 20px;
                border: 1px solid #c3e6cb;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .success-message::before {
                content: '✅';
                font-size: 1.2rem;
            }

            /* Auth Footer */
            .auth-footer {
                margin-top: 30px;
                text-align: center;
                color: rgba(255, 255, 255, 0.8);
                font-size: 0.85rem;
            }

            .auth-footer p {
                margin: 0;
            }

            /* Divider */
            .auth-divider {
                display: flex;
                align-items: center;
                gap: 15px;
                margin: 25px 0;
                color: #999;
                font-size: 0.9rem;
            }

            .auth-divider::before,
            .auth-divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: linear-gradient(90deg, transparent, #ddd, transparent);
            }

            /* Register Link */
            .register-prompt {
                text-align: center;
                margin-top: 25px;
                padding-top: 25px;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                color: #666;
                font-size: 0.95rem;
            }

            .register-prompt a {
                color: #667eea;
                font-weight: 600;
                text-decoration: none;
                transition: var(--transition-smooth);
            }

            .register-prompt a:hover {
                color: #764ba2;
                text-decoration: underline;
            }

            /* Responsive */
            @media (max-width: 480px) {
                .auth-card {
                    padding: 30px 25px;
                }

                .logo-text {
                    font-size: 1.6rem;
                }

                .logo-icon {
                    font-size: 2.5rem;
                }

                .form-actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                .auth-button {
                    width: 100%;
                }
            }
        </style>
    </body>
</html>
