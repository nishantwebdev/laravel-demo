<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel API Demo</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            body {
                font-family: 'Figtree', sans-serif;
                margin: 0;
                padding: 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .container {
                background: white;
                border-radius: 20px;
                padding: 3rem;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 600px;
                margin: 2rem;
            }
            .logo {
                font-size: 3rem;
                font-weight: 600;
                color: #667eea;
                margin-bottom: 1rem;
            }
            .subtitle {
                font-size: 1.2rem;
                color: #666;
                margin-bottom: 2rem;
            }
            .api-info {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 1.5rem;
                margin: 2rem 0;
                text-align: left;
            }
            .endpoint {
                background: #e3f2fd;
                padding: 0.5rem 1rem;
                border-radius: 5px;
                font-family: 'Courier New', monospace;
                margin: 0.5rem 0;
                border-left: 4px solid #2196f3;
            }
            .method {
                display: inline-block;
                background: #4caf50;
                color: white;
                padding: 0.2rem 0.5rem;
                border-radius: 3px;
                font-size: 0.8rem;
                margin-right: 0.5rem;
            }
            .method.post { background: #ff9800; }
            .method.put { background: #9c27b0; }
            .method.delete { background: #f44336; }
            .status {
                color: #4caf50;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">🚀 Laravel API Demo</div>
            <div class="subtitle">Job Proposal Showcase</div>
            
            <div class="status">✅ API is running successfully!</div>
            
            <div class="api-info">
                <h3>Available API Endpoints:</h3>
                
                <div class="endpoint">
                    <span class="method">GET</span> /api/health - Health check
                </div>
                
                <div class="endpoint">
                    <span class="method post">POST</span> /api/register - User registration
                </div>
                
                <div class="endpoint">
                    <span class="method post">POST</span> /api/login - User login
                </div>
                
                <div class="endpoint">
                    <span class="method">GET</span> /api/users - List users (Auth required)
                </div>
                
                <div class="endpoint">
                    <span class="method post">POST</span> /api/users - Create user (Auth required)
                </div>
                
                <div class="endpoint">
                    <span class="method">GET</span> /api/posts - List posts with eager loading
                </div>
                
                <div class="endpoint">
                    <span class="method post">POST</span> /api/stripe/webhook - Stripe webhook handler
                </div>
            </div>
            
            <p><strong>Laravel Version:</strong> 11.x</p>
            <p><strong>Features:</strong> Sanctum Auth, Stripe Webhooks, Eloquent Eager Loading</p>
            
            <p style="margin-top: 2rem; color: #666;">
                Check the <code>API_EXAMPLES.md</code> file for detailed usage examples.
            </p>
        </div>
    </body>
</html>
