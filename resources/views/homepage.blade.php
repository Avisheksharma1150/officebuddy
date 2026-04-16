<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OfficeBuddy - Automated Payroll System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Favicon to replace Laravel icon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💼</text></svg>">
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
        }
        .text-primary {
            color: #667eea !important;
        }
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('homepage') }}">
                <span class="text-primary">Office</span><span class="text-gradient">Buddy</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('homepage') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('features') }}">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="btn btn-primary ms-2" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item"><a class="btn btn-primary ms-2" href="{{ route('home') }}">Dashboard</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="display-4 fw-bold mb-4">Simplify Your Payroll Management</h1>
                    <p class="lead mb-5">OfficeBuddy is the complete automated payroll solution that handles attendance tracking, salary calculations, tax deductions, and payslip generation—all in one platform.</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started Free</a>
                        <a href="{{ route('features') }}" class="btn btn-outline-light btn-lg">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4" style="font-size: 3rem;">⏰</div>
                    <h4>Attendance Tracking</h4>
                    <p>Automated check-in/check-out system with late arrival and early departure tracking.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4" style="font-size: 3rem;">💰</div>
                    <h4>Payroll Automation</h4>
                    <p>Automatic salary calculations with tax, provident fund, and allowance management.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4" style="font-size: 3rem;">📊</div>
                    <h4>Detailed Reports</h4>
                    <p>Comprehensive reports and downloadable payslips in PDF format.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light py-5 mt-5">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Simplify Your Payroll?</h2>
            <p class="lead mb-4">Join thousands of businesses using OfficeBuddy to automate their payroll processes.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start Your Free Trial</a>
        </div>
    </div>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 OfficeBuddy. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>