<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token Protection -->
    <x-csrf-protection />

    <title>@yield('title') - {{ config('app.school_name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- DNS Prefetch & Preconnect for Fast Asset Delivery -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://code.jquery.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}?v=1.0.1">

    <style>
        :root {
            --nav-height: 80px;
        }

        body {
            font-family: 'Inter', sans-serif;
            padding-top:
                {{ request()->routeIs('home') ? '0' : 'var(--nav-height)' }}
            ;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        /* Institutional Navbar */
        .navbar-premium {
            height: var(--nav-height);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 1050;
        }

        .navbar-premium.scrolled {
            height: 70px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .navbar-transparent {
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        .navbar-transparent .nav-link-custom {
            color: var(--oxford-blue) !important;
        }

        .navbar-transparent .navbar-brand .brand-name {
            color: var(--oxford-blue) !important;
        }

        .navbar-brand .brand-subtext {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: #64748b !important;
            /* Default muted color */
            transition: color 0.3s ease;
        }

        .navbar-transparent .navbar-brand .brand-subtext {
            color: #64748b !important;
        }

        .navbar-brand img {
            height: 55px;
            width: auto;
            transition: all 0.3s ease;
        }

        .navbar-premium.scrolled .navbar-brand img {
            height: 45px;
        }

        .nav-link-custom {
            font-weight: 600;
            color: var(--oxford-blue);
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            position: relative;
            text-decoration: none !important;
        }

        .nav-link-custom:hover {
            color: var(--gold-accent) !important;
        }

        .nav-link-custom,
        .nav-link-custom:hover,
        .nav-link-custom:focus,
        .nav-link-custom.active,
        .nav-link-custom::after,
        .nav-link-custom::before {
            text-decoration: none !important;
            border: none !important;
            box-shadow: none !important;
        }



        nav a,
        nav a:hover,
        nav a:focus,
        nav a.active {
            text-decoration: none !important;
        }

        @media (max-width: 991.98px) {
            .navbar-premium {
                height: auto !important;
                padding: 12px 0;
                background: #fff !important;
            }

            .navbar-transparent {
                background: #fff !important;
            }

            .navbar-transparent .nav-link-custom,
            .navbar-transparent .navbar-brand .brand-name {
                color: var(--oxford-blue) !important;
            }

            .navbar-brand img {
                height: 40px !important;
            }

            .brand-name {
                font-size: 0.9rem !important;
                line-height: 1.2 !important;
                margin-bottom: 0 !important;
            }

            .brand-subtext {
                font-size: 0.6rem !important;
                display: block !important;
                line-height: 1.2 !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .navbar-collapse {
                margin-top: 15px;
                background: #fff;
                border-radius: 15px;
                padding: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
        }



        /* Floating Flash Messages */
        .flash-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1060;
            max-width: 400px;
            width: calc(100% - 40px);
        }

        .premium-alert {
            background: white;
            border: none;
            border-radius: 1.25rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-xl);
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            position: relative;
            overflow: hidden;
        }

        .premium-alert::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 6px;
        }

        .premium-alert.alert-success::before {
            background: var(--secondary-color);
        }

        .premium-alert.alert-danger::before {
            background: #ef4444;
        }

        .premium-alert.alert-info::before {
            background: var(--primary-color);
        }

        .alert-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .alert-success .alert-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
        }

        .alert-danger .alert-icon {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .alert-info .alert-icon {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }

        .alert-content h6 {
            margin: 0;
            font-weight: 700;
            color: var(--text-main);
        }

        .alert-content p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ==========================================================
           ULTRA-PREMIUM INSTITUTIONAL FOOTER REDESIGN
           ========================================================== */
        .premium-footer {
            flex-shrink: 0 !important;
            margin-top: 60px;
            background: linear-gradient(180deg, #091322 0%, #030712 100%);
            color: #f1f5f9;
            position: relative;
            z-index: 10;
            overflow: hidden;
            border-top: 1px solid rgba(212, 175, 55, 0.25);
            box-shadow: 0 -15px 40px rgba(0, 0, 0, 0.45);
        }

        /* Ambient Glow & Accent Bar */
        .premium-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                    transparent 0%,
                    rgba(212, 175, 55, 0.3) 20%,
                    rgba(255, 215, 0, 0.9) 50%,
                    rgba(212, 175, 55, 0.3) 80%,
                    transparent 100%);
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.4);
        }

        .footer-ambient-glow {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 850px;
            height: 250px;
            background: radial-gradient(ellipse at 50% 0%, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Pre-Footer Action Strip */
        .footer-action-strip {
            padding: 36px 0 32px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(255, 255, 255, 0.015);
            position: relative;
        }

        .footer-action-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.01) 100%);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 16px;
            padding: 20px 28px;
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .action-strip-content h5 {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.2rem;
            margin-bottom: 4px;
            font-family: var(--font-outfit, sans-serif);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-live-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #34d399;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .action-live-pill .pulse-dot {
            width: 7px;
            height: 7px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #10b981;
            animation: footerPulse 2s infinite;
        }

        @keyframes footerPulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .action-strip-content p {
            color: rgba(226, 232, 240, 0.7);
            font-size: 0.88rem;
            margin: 0;
        }

        .action-strip-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-footer-cta {
            background: linear-gradient(135deg, #c9933e 0%, #b8822d 100%);
            color: #030712 !important;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 10px 22px;
            border-radius: 10px;
            text-decoration: none !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(201, 147, 62, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-footer-cta:hover {
            background: linear-gradient(135deg, #dfa952 0%, #c9933e 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 147, 62, 0.45);
        }

        .btn-footer-outline {
            background: rgba(255, 255, 255, 0.05);
            color: #f1f5f9 !important;
            border: 1px solid rgba(255, 255, 255, 0.15);
            font-weight: 600;
            font-size: 0.88rem;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none !important;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-footer-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(212, 175, 55, 0.4);
            color: var(--gold-accent, #c9933e) !important;
            transform: translateY(-2px);
        }

        /* Main Footer Body */
        .footer-main-body {
            padding: 55px 0 45px;
            position: relative;
        }

        /* Brand Column */
        .footer-brand-crest-wrap {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
        }

        .footer-crest-img {
            height: 60px;
            width: auto;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.5));
            transition: transform 0.3s ease;
        }

        .footer-crest-img:hover {
            transform: scale(1.06);
        }

        .footer-school-title {
            font-family: var(--font-outfit, sans-serif);
            font-size: 1.15rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .footer-school-sub {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--gold-accent, #c9933e);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-top: 3px;
        }

        .footer-council-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201, 147, 62, 0.1);
            border: 1px solid rgba(201, 147, 62, 0.3);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fce7b2;
            letter-spacing: 0.5px;
            margin-bottom: 18px;
        }

        .footer-brand-desc {
            color: rgba(203, 213, 225, 0.72);
            font-size: 0.88rem;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        /* Social Hub */
        .footer-social-hub {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-social-circle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: rgba(241, 245, 249, 0.8);
            font-size: 0.95rem;
            text-decoration: none !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .footer-social-circle:hover {
            background: var(--gold-accent, #c9933e);
            border-color: var(--gold-accent, #c9933e);
            color: #030712 !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 18px rgba(201, 147, 62, 0.35);
        }

        /* Footer Column Headings */
        .footer-col-header {
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 22px;
            position: relative;
            padding-bottom: 10px;
            font-family: var(--font-outfit, sans-serif);
        }

        .footer-col-header::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 32px;
            height: 2px;
            background: var(--gold-accent, #c9933e);
            border-radius: 2px;
        }

        /* Navigation Links */
        .footer-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-nav-item {
            margin-bottom: 10px;
        }

        .footer-nav-link {
            color: rgba(203, 213, 225, 0.65);
            font-size: 0.88rem;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            position: relative;
        }

        .footer-nav-link .nav-arrow {
            opacity: 0;
            transform: translateX(-6px);
            transition: all 0.25s ease;
            color: var(--gold-accent, #c9933e);
            font-size: 0.75rem;
        }

        .footer-nav-link:hover {
            color: #ffffff !important;
            transform: translateX(4px);
        }

        .footer-nav-link:hover .nav-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .footer-link-badge {
            background: rgba(201, 147, 62, 0.2);
            color: var(--gold-accent, #c9933e);
            border: 1px solid rgba(201, 147, 62, 0.35);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 6px;
            margin-left: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Contact & Directory Cards */
        .footer-contact-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-contact-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid rgba(255, 255, 255, 0.06);
            text-decoration: none !important;
            transition: all 0.3s ease;
        }

        .footer-contact-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(201, 147, 62, 0.3);
            transform: translateY(-2px);
        }

        .footer-contact-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(201, 147, 62, 0.12);
            border: 1px solid rgba(201, 147, 62, 0.25);
            color: var(--gold-accent, #c9933e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .footer-contact-card:hover .footer-contact-icon-box {
            background: var(--gold-accent, #c9933e);
            color: #030712;
            border-color: var(--gold-accent, #c9933e);
        }

        .footer-contact-meta {
            flex-grow: 1;
        }

        .footer-contact-meta .meta-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--gold-accent, #c9933e);
            margin-bottom: 2px;
        }

        .footer-contact-meta .meta-val {
            font-size: 0.85rem;
            color: rgba(241, 245, 249, 0.9);
            line-height: 1.45;
            margin: 0;
            font-weight: 500;
        }

        .footer-hours-banner {
            margin-top: 14px;
            padding: 10px 14px;
            border-radius: 10px;
            background: rgba(201, 147, 62, 0.05);
            border: 1px dashed rgba(201, 147, 62, 0.25);
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(226, 232, 240, 0.8);
            font-size: 0.78rem;
        }

        .footer-hours-banner i,
        .footer-hours-banner svg {
            color: var(--gold-accent, #c9933e);
            flex-shrink: 0;
        }

        /* Bottom Copyright Bar */
        .footer-bottom-bar {
            background: #02050b;
            padding: 22px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 0.82rem;
            color: rgba(148, 163, 184, 0.8);
            position: relative;
        }

        .footer-bottom-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-copyright-text {
            margin: 0;
            color: rgba(148, 163, 184, 0.8);
        }

        .footer-legal-links {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin-left: 12px;
        }

        .footer-legal-link {
            color: rgba(148, 163, 184, 0.65);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-legal-link:hover {
            color: var(--gold-accent, #c9933e);
        }

        .footer-live-counter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 0.78rem;
            color: #e2e8f0;
            font-weight: 500;
        }

        .footer-live-counter .counter-num {
            font-weight: 700;
            color: var(--gold-accent, #c9933e);
        }

        .footer-credit-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-credit-wrap a img {
            height: 18px;
            opacity: 0.85;
            transition: all 0.3s ease;
        }

        .footer-credit-wrap a:hover img {
            opacity: 1;
            transform: scale(1.05);
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
        }

        @media (max-width: 991.98px) {
            .footer-action-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-action-strip {
                padding: 20px 0;
            }

            .footer-main-body {
                padding: 40px 0 30px;
            }

            .footer-bottom-flex {
                flex-direction: column;
                text-align: center;
                justify-content: center;
            }

            .footer-legal-links {
                margin-left: 0;
                margin-top: 8px;
            }
        }

        /* Institutional Card Redesign */
        .institutional-card {
            background: #fff;
            border-radius: 12px !important;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .institutional-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: rgba(176, 141, 87, 0.08);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--gold-accent);
        }

        .institutional-card.gold-themed {
            border-top: 3px solid var(--gold-accent) !important;
        }

        .uppercase-tracking-1 {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
        }

        .bg-primary-subtle {
            background-color: rgba(12, 33, 58, 0.1) !important;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-gold {
            background-color: var(--gold-accent) !important;
        }

        .text-gold {
            color: var(--gold-accent) !important;
        }

        .bg-gold-subtle {
            background-color: rgba(176, 141, 87, 0.1) !important;
        }

        .btn-gold {
            background-color: var(--gold-accent);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: #967645;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(176, 141, 87, 0.3);
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            transition: all 0.3s;
            margin-right: 0.75rem;
        }

        .social-btn:hover {
            background: var(--gold-accent);
            color: white;
            transform: translateY(-3px);
        }

        /* Main Container Animation */
        .page-transition {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-premium-primary {
            background: var(--primary-gradient) !important;
            color: white !important;
            border: 2px solid var(--oxford-blue) !important;
        }

        .btn-premium-primary:hover:not(:disabled) {
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-premium-outline {
            background: #ffffff !important;
            color: var(--oxford-blue) !important;
            border: 2px solid var(--oxford-blue) !important;
        }

        .btn-premium-outline:hover:not(:disabled) {
            background: var(--oxford-blue) !important;
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-premium-secondary,
        .btn-premium:disabled,
        .btn-premium[disabled] {
            background: #f1f5f9 !important;
            color: #94a3b8 !important;
            border: 2px solid #e2e8f0 !important;
            box-shadow: none !important;
            cursor: not-allowed !important;
            transform: none !important;
        }

        .required::after {
            content: " *";
            color: #ef4444;
            font-weight: bold;
        }

        .premium-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .scale-hover {
            transition: transform 0.3s ease;
        }

        .btn:hover .scale-hover {
            transform: translateX(5px);
        }

        .tracking-widest {
            letter-spacing: 0.2em;
        }

        /* Scroll Animation Utility */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
            will-change: transform, opacity;
        }

        .animate-on-scroll.active {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-1 {
            transition-delay: 0.1s;
        }

        .delay-2 {
            transition-delay: 0.2s;
        }

        .delay-3 {
            transition-delay: 0.3s;
        }

        .delay-4 {
            transition-delay: 0.4s;
        }
    </style>

    @yield('styles')
</head>

<body class="d-flex flex-column h-100">
    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg fixed-top navbar-premium {{ request()->routeIs('home') ? 'navbar-transparent' : '' }}">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <!-- Logo Options -->
                <img src="{{ asset('images/logo1.png') }}" alt="School Logo" class="brand-logo">
                <div class="brand-text">
                    <span
                        class="brand-name d-block fw-bold text-main lh-1 transition-base">{{ config('app.school_name') }}</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 p-2 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-3 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('home') }}"
                            class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="btn-royal py-2 px-4 shadow-sm">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="nav-link-custom text-danger border-0 bg-transparent p-0">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('admission.select') }}" class="btn-royal py-2 px-4 shadow-sm">Apply Now</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>


    <!-- Flash Messages Container -->
    <div class="flash-container">
        @error('csrf_error')
            <div class="alert premium-alert alert-danger shadow-lg" role="alert">
                <div class="alert-icon"><x-fas-history /></div>
                <div class="alert-content">
                    <h6>Session Expired</h6>
                    <p>{{ $message }}</p>
                </div>
                <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert"></button>
            </div>
        @enderror

        @if(session('success'))
            <div class="alert premium-alert alert-success shadow-lg" role="alert">
                <div class="alert-icon"><x-fas-check-circle /></div>
                <div class="alert-content">
                    <h6>Success!</h6>
                    <p>{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert premium-alert alert-danger shadow-lg" role="alert">
                <div class="alert-icon"><x-fas-exclamation-circle /></div>
                <div class="alert-content">
                    <h6>Error Occurred</h6>
                    <p>{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="flex-shrink-0 page-transition">
        @yield('content')
    </main>

    <!-- Footer -->
    <!-- Institutional Footer Redesign -->
    <!-- Ultra-Premium Institutional Footer Redesign -->
    <footer class="premium-footer">
        <div class="footer-ambient-glow"></div>

        <!-- Top Admissions Action Strip -->
        <div class="footer-action-strip">
            <div class="container">
                <div class="footer-action-card">
                    <div class="action-strip-content">
                        <h5>
                            <span>Admissions Open {{ date('Y') }}–{{ date('Y') + 1 }}</span>
                        </h5>
                        <p>Enroll your ward into our Lower Academy, General Education, or Higher Secondary wings today.
                        </p>
                    </div>
                    <div class="action-strip-buttons">
                        <a href="{{ route('admission.select') }}" class="btn-footer-cta">
                            <span>Apply Online Now</span>
                            <x-fas-arrow-right style="width: 12px; height: 12px;" />
                        </a>
                        <a href="tel:{{ config('app.school_phone') }}" class="btn-footer-outline">
                            <x-fas-phone-alt style="width: 12px; height: 12px;" />
                            <span>Contact Desk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer Columns -->
        <div class="footer-main-body">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    <!-- Column 1: Institutional Brand & Affiliation (Col 4) -->
                    <div class="col-lg-4 col-md-12">
                        <div class="footer-brand-crest-wrap">
                            <img src="{{ asset('images/logo1.png') }}" alt="{{ config('app.school_name') }} Crest"
                                class="footer-crest-img">
                            <div>
                                <h4 class="footer-school-title">{{ config('app.school_name') }}</h4>
                            </div>
                        </div>

                        <p class="footer-brand-desc">
                            Committed to academic rigor, character building, and moral empowerment. Part of the
                            prestigious YES INDIA FOUNDATION academic council, nurturing future scholars and leaders.
                        </p>

                        <div class="footer-social-hub">
                            <a href="www.facebook.com/" class="footer-social-circle" title="Facebook" target="_blank"
                                rel="noopener">
                                <x-fab-facebook-f style="width: 14px; height: 14px;" />
                            </a>
                            <a href="www.instagram.com/" class="footer-social-circle" title="Instagram" target="_blank"
                                rel="noopener">
                                <x-fab-instagram style="width: 14px; height: 14px;" />
                            </a>
                            <a href="www.twitter.com/" class="footer-social-circle" title="Twitter" target="_blank"
                                rel="noopener">
                                <x-fab-twitter style="width: 14px; height: 14px;" />
                            </a>
                            <a href="www.youtube.com/" class="footer-social-circle" title="YouTube" target="_blank"
                                rel="noopener">
                                <x-fab-youtube style="width: 14px; height: 14px;" />
                            </a>
                        </div>
                    </div>

                    <!-- Column 2: Academic Admissions (Col 2) -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <h6 class="footer-col-header">Admissions</h6>
                        <ul class="footer-nav-list">
                            @if(\App\Models\SiteSetting::isFormEnabled('form1'))
                                <li class="footer-nav-item">
                                    <a href="{{ route('admission.form1') }}" class="footer-nav-link">
                                        <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                        <span>Lower Academy</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Models\SiteSetting::isFormEnabled('form2'))
                                <li class="footer-nav-item">
                                    <a href="{{ route('admission.form2') }}" class="footer-nav-link">
                                        <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                        <span>General Education</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Models\SiteSetting::isFormEnabled('form3'))
                                <li class="footer-nav-item">
                                    <a href="{{ route('admission.form3') }}" class="footer-nav-link">
                                        <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                        <span>Higher Secondary</span>
                                    </a>
                                </li>
                            @endif
                            <li class="footer-nav-item">
                                <a href="{{ route('admission.select') }}" class="footer-nav-link">
                                    <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                    <span>How to Apply</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 3: Quick Navigation (Col 2) -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <h6 class="footer-col-header">Quick Links</h6>
                        <ul class="footer-nav-list">
                            <li class="footer-nav-item">
                                <a href="{{ route('home') }}" class="footer-nav-link">
                                    <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                    <span>Home Portal</span>
                                </a>
                            </li>
                            <li class="footer-nav-item">
                                <a href="{{ route('admission.select') }}" class="footer-nav-link">
                                    <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                    <span>Select Program</span>
                                </a>
                            </li>
                            <li class="footer-nav-item">
                                <a href="{{ route('home') }}#programs" class="footer-nav-link">
                                    <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                    <span>Wings & Streams</span>
                                </a>
                            </li>
                            <li class="footer-nav-item">
                                <a href="{{ route('admin.login') }}" class="footer-nav-link">
                                    <x-fas-chevron-right class="nav-arrow" style="width: 10px; height: 10px;" />
                                    <span>Staff & Admin</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 4: Campus Directory & Office Hours (Col 4) -->
                    <div class="col-lg-4 col-md-4 col-12">
                        <h6 class="footer-col-header">Campus Directory</h6>
                        <div class="footer-contact-stack">
                            <!-- Location -->
                            <div class="footer-contact-card">
                                <div class="footer-contact-icon-box">
                                    <x-fas-map-marker-alt style="width: 14px; height: 14px;" />
                                </div>
                                <div class="footer-contact-meta">
                                    <p class="meta-val">
                                        <strong>{{ config('app.school_name') }}</strong><br>
                                        <span
                                            style="opacity: 0.8; font-size: 0.82rem;">{!! nl2br(e(config('app.school_location'))) !!}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <a href="tel:{{ config('app.school_phone') }}" class="footer-contact-card">
                                <div class="footer-contact-icon-box">
                                    <x-fas-phone-alt style="width: 14px; height: 14px;" />
                                </div>
                                <div class="footer-contact-meta">
                                    <p class="meta-val">{{ config('app.school_phone') }}</p>
                                </div>
                            </a>

                            <!-- Email -->
                            <a href="mailto:{{ config('app.school_email') }}" class="footer-contact-card">
                                <div class="footer-contact-icon-box">
                                    <x-fas-envelope style="width: 14px; height: 14px;" />
                                </div>
                                <div class="footer-contact-meta">
                                    <p class="meta-val">{{ config('app.school_email') }}</p>
                                </div>
                            </a>
                        </div>

                        <!-- Hours -->
                        <div class="footer-hours-banner">
                            <x-fas-clock style="width: 14px; height: 14px;" />
                            <span><strong>Office Hours:</strong> Mon – Sat: 9:00 AM – 3:00 PM (Closed Sundays)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom-bar">
            <div class="container">
                <div class="footer-bottom-flex">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <p class="footer-copyright-text">
                            &copy; {{ date('Y') }} {{ config('app.school_name') }}. All rights reserved.
                        </p>
                        <div class="footer-legal-links">
                            <span style="opacity: 0.3;">&bull;</span>
                            <a href="#" class="footer-legal-link">Privacy Policy</a>
                            <span style="opacity: 0.3;">&bull;</span>
                            <a href="#" class="footer-legal-link">Terms of Admission</a>
                        </div>
                    </div>

                    <!-- Live Visitor Counter -->
                    <div class="footer-live-counter">
                        <span class="pulse-dot"
                            style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; box-shadow: 0 0 6px #10b981;"></span>
                        <span>Live Portal</span>
                        <span style="opacity: 0.3;">|</span>
                        <x-fas-eye class="text-gold" style="width: 12px; height: 12px;" />
                        <span>Visitors: <strong
                                class="counter-num">{{ number_format(\App\Models\SiteSetting::get('visitor_count', 0)) }}</strong></span>
                    </div>

                    <!-- Cyberduce Credit -->
                    <div class="footer-credit-wrap">
                        <span style="opacity: 0.65; font-size: 0.8rem;">Designed & Developed by</span>
                        <a href="https://cyberduce.com" target="_blank" rel="noopener" title="Cyberduce Technologies">
                            <img src="{{ asset('images/cyberduce-white.png') }}" alt="Cyberduce Logo">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const nav = document.querySelector('.navbar-premium');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
                nav.classList.remove('navbar-transparent');
            } else {
                nav.classList.remove('scrolled');
                if (window.location.pathname === '/' || window.location.pathname === '') {
                    nav.classList.add('navbar-transparent');
                }
            }
        });

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const alerts = document.querySelectorAll('.premium-alert');
                alerts.forEach(function (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 8000);
        });

        // CSRF Token Keep-Alive
        function refreshCsrfToken() {
            fetch('/refresh-csrf')
                .then(response => response.json())
                .then(data => {
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.csrf_token;
                    });
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) metaTag.content = data.csrf_token;
                })
                .catch(error => console.error('Token refresh failed:', error));
        }
        setInterval(refreshCsrfToken, 15 * 60 * 1000);

        // Global Intersection Observer for animations
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                threshold: 0.05,
                rootMargin: '0px 0px 50px 0px'
            };

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            animatedElements.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    el.classList.add('active');
                } else {
                    revealObserver.observe(el);
                }
            });
        });
    </script>
    <noscript>
        <style>
            .animate-on-scroll {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
    </noscript>

    @yield('scripts')
</body>

</html>