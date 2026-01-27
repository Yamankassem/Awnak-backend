<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - منصة تطوع</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            direction: rtl;
            text-align: right;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #4a6fa5, #166088);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .email-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .email-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .email-logo {
            max-width: 120px;
            margin-bottom: 15px;
        }
        
        .email-body {
            padding: 30px;
        }
        
        .email-content {
            margin-bottom: 25px;
        }
        
        .email-content h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        
        .email-content p {
            margin-bottom: 15px;
            font-size: 16px;
            color: #555;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-right: 4px solid #4a6fa5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .info-item {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .info-value {
            color: #555;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 0 5px;
        }
        
        .status-pending { background-color: #ffc107; color: #333; }
        .status-approved { background-color: #28a745; color: white; }
        .status-rejected { background-color: #dc3545; color: white; }
        .status-completed { background-color: #17a2b8; color: white; }
        
        .btn-primary {
            display: inline-block;
            background-color: #4a6fa5;
            color: white !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #166088;
        }
        
        .btn-secondary {
            display: inline-block;
            background-color: #6c757d;
            color: white !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        
        .footer-links {
            margin-top: 15px;
        }
        
        .footer-links a {
            color: #4a6fa5;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .social-icons {
            margin-top: 15px;
        }
        
        .social-icons a {
            display: inline-block;
            margin: 0 5px;
            color: #666;
            font-size: 18px;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .email-body {
                padding: 20px;
            }
            
            .email-header {
                padding: 20px 15px;
            }
            
            .btn-primary, .btn-secondary {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            @if(isset($logo))
                <img src="{{ $logo }}" alt="منصة تطوع" class="email-logo">
            @else
                <h1> منصة تطوع</h1>
            @endif
            <p>نحو مجتمع متطوع وفعال</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p>© {{ date('Y') }} منصة تطوع. جميع الحقوق محفوظة.</p>
            
            <div class="footer-links">
                <a href="{{ url('/') }}">الرئيسية</a> |
                <a href="{{ url('/about') }}">عن المنصة</a> |
                <a href="{{ url('/contact') }}">اتصل بنا</a> |
                <a href="{{ url('/privacy') }}">الخصوصية</a>
            </div>
            
            
            
            <p style="margin-top: 15px; font-size: 12px; color: #888;">
                هذا البريد الإلكتروني أرسل تلقائياً من النظام، يرجى عدم الرد عليه.
            </p>
        </div>
    </div>
</body>
</html>