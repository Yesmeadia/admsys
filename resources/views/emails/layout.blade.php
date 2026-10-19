<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject')</title>
    <style>
        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            -ms-interpolation-mode: bicubic;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7fa;
            padding-bottom: 40px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #333d47;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .header {
            background-color: #ffffff;
            padding: 30px 0;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo-img {
            max-width: 180px;
            height: auto;
            display: inline-block;
        }

        /* ContentArea */
        .content {
            padding: 40px 50px;
            line-height: 1.6;
            font-size: 16px;
        }

        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #073b4c;
            margin-bottom: 20px;
        }

        .text {
            color: #4b5563;
            margin-bottom: 25px;
        }

        /* Buttons */
        .button-wrapper {
            padding: 10px 0 30px;
            text-align: center;
        }

        .button {
            display: inline-block;
            background-color: #4361ee;
            color: #ffffff !important;
            padding: 16px 45px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            transition: transform 0.2s ease;
        }

        /* Stats/Info Boxes */
        .info-card {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 4px solid #4361ee;
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            padding: 8px 0;
            font-weight: 600;
            color: #475569;
            width: 40%;
            font-size: 14px;
        }

        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #073b4c;
            font-weight: 700;
            font-size: 14px;
        }

        /* Footer */
        .footer {
            padding: 40px 0;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        .footer a {
            color: #4361ee;
            text-decoration: none;
        }

        .social-links {
            margin-bottom: 20px;
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 30px 0;
        }

        /* Helpers */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-primary {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        @media screen and (max-width: 600px) {
            .content {
                padding: 30px 25px;
            }

            .info-label {
                width: 45%;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div style="height: 20px;"></div>
        <div
            style="max-width: 600px; margin: 0 auto; background-color: #073b4c; height: 6px; border-radius: 12px 12px 0 0;">
        </div>
        <table class="main" style="border-radius: 0 0 12px 12px;">
            <tr>
                <td class="header">
                    <a href="{{ config('app.url') }}">
                        <img src="{{ $message->embed(public_path('images/logo1.png')) }}" alt="{{ config('app.name') }}"
                            width="180" class="logo-img">
                    </a>
                </td>
            </tr>
            <tr>
                <td class="content">
                    @yield('content')

                    <div class="divider"></div>

                    <p class="text" style="font-size: 14px; color: #94a3b8;">
                        Regards,<br>
                        <strong>Admissions Office</strong><br>
                        {{ config('app.school_name') }}<br>
                        <small style="color: #64748b; font-size: 11px;">Managed by: YES INDIA FOUNDATION (A KERALA BASED
                            ACADEMIC COUNCIL)</small>
                    </p>
                </td>
            </tr>
        </table>

        <table style="width: 100%; max-width: 600px; margin: 0 auto;">
            <tr>
                <td class="footer">
                    <div>
                        © {{ date('Y') }} {{ config('app.school_name') }}. All rights reserved.<br>
                        Managed by: YES INDIA FOUNDATION (A KERALA BASED ACADEMIC COUNCIL)<br>
                        {{ config('app.school_location') }} | MOB: {{ config('app.school_phone') }}
                    </div>
                    <div style="margin-top: 10px;">
                        This is an automated email. Please do not reply directly.
                    </div>
                    <div style="margin-top: 20px; font-size: 11px; opacity: 0.6;">
                        You are receiving this email because you have submitted an application on our portal.
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>