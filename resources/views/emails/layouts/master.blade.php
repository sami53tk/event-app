<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Event App</title>
    <style>
        /* Styles de base */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eaeaea;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            text-decoration: none;
        }
        
        .content {
            padding: 30px 20px;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #eaeaea;
        }
        
        h1 {
            color: #1f2937;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        p {
            margin-bottom: 16px;
        }
        
        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .btn:hover {
            background-color: #2563eb;
        }
        
        .event-details {
            background-color: #f9fafb;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        
        .event-title {
            font-weight: bold;
            color: #1f2937;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .event-info {
            margin-bottom: 5px;
            color: #4b5563;
        }
        
        .highlight {
            color: #3b82f6;
            font-weight: bold;
        }
        
        .alert {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #b91c1c;
        }
        
        .success {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #047857;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                border-radius: 0;
            }
            
            .content {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}" class="logo">Event App</a>
        </div>
        
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Event App. Tous droits réservés.</p>
            <p>Si vous avez des questions, n'hésitez pas à nous contacter à <a href="mailto:contact@eventapp.com">contact@eventapp.com</a></p>
        </div>
    </div>
</body>
</html>
