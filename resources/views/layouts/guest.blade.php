<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Authentication</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --school-blue: #0c2b5c;
            --school-dark-blue: #071f42;
            --school-blue-light: #f0f4ff;
            --accent-gold: #d4af37;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: row-reverse;
        }

        .auth-side-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #fff;
            position: relative;
            z-index: 2;
        }

        .auth-side-visual {
            flex: 1.2;
            background: linear-gradient(135deg, var(--school-blue) 0%, var(--school-dark-blue) 100%);
            position: relative;
            overflow: hidden;
            display: none;
        }

        @media (min-width: 992px) {
            .auth-side-visual {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .visual-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("{{ asset('images/hero.png') }}") no-repeat center center;
            background-size: cover;
            opacity: 0.6;
            mix-blend-mode: overlay;
        }

        .visual-content {
            position: relative;
            z-index: 3;
            text-align: center;
            padding: 60px;
            color: #fff;
            max-width: 600px;
        }

        .auth-logo-large {
            height: 120px;
            margin-bottom: 30px;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.2));
        }

        .auth-card-modern {
            width: 100%;
            max-width: 420px;
            animation: slideInDown 0.6s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-accent {
            color: var(--school-blue);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            margin-bottom: 10px;
            display: block;
        }

        .auth-title {
            font-size: 2.2rem;
            margin-bottom: 8px;
            color: var(--school-blue);
        }

        .auth-subtitle {
            color: #64748b;
            margin-bottom: 35px;
            font-size: 1rem;
        }

        /* Styling standard forms/inputs from Breeze */
        .form-control-premium {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .form-control-premium:focus {
            border-color: var(--school-blue);
            box-shadow: 0 0 0 4px rgba(12, 43, 92, 0.05);
            background-color: #fff;
        }

        .auth-btn-primary {
            background-color: var(--school-blue);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .auth-btn-primary:hover {
            background-color: var(--school-dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(12, 43, 92, 0.2);
            color: #fff;
        }

        .auth-link {
            color: var(--school-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: opacity 0.2s;
        }

        .auth-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 30px 0;
            position: relative;
        }

        .divider::after {
            content: "OR";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 0 15px;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .back-to-home {
            position: absolute;
            top: 40px;
            left: 40px;
        }

        .visual-quote-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 40px;
            text-align: left;
        }

        .quote-icon {
            font-size: 2rem;
            color: var(--accent-gold);
            margin-bottom: 15px;
            display: block;
        }

        .quote-text {
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.6;
            font-style: italic;
        }

        input {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
        }

        input:focus {
            border-color: var(--school-blue) !important;
            box-shadow: 0 0 0 4px rgba(12, 43, 92, 0.05) !important;
            outline: none;
        }

        label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }

        .btn-primary-custom {
            background-color: var(--school-blue) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 12px 24px !important;
            font-weight: 700 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <!-- Right Side: Form -->
        <div class="auth-side-form">
            <div class="auth-card-modern">
                <!-- Content slot from auth pages -->
                {{ $slot }}
            </div>
        </div>

        <!-- Left Side: Visual -->
        <div class="auth-side-visual">
            <div class="visual-overlay"></div>
            <div class="visual-content">

                <h2 class="display-6 fw-bold mb-3">Welcome to {{ config('app.school_name') }}</h2>
                <p class="fs-5 opacity-75">Join our community of excellence and shape the future of education together.
                </p>

                <div class="visual-quote-box">
                    <x-fas-quote-left class="quote-icon" />
                    <p class="quote-text">"YES India Makes an Excellent India. We are dedicated to providing holistic,
                        value-based education that empowers the catalysts of the next generation."</p>
                    <div class="mt-3 fs-6 fw-bold">
                        — YES INDIA FOUNDATION
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>

</html>