<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - OfficeBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .about-section {
            padding: 100px 0 50px;
            color: white;
        }
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
        .team-member {
            text-align: center;
            padding: 20px;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('about') }}">About</a></li>
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

    <div class="about-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">About OfficeBuddy</h1>
                    <p class="lead">Revolutionizing payroll management for modern businesses</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="content-card">
                    <h2 class="mb-4">Our Story</h2>
                    <p class="lead mb-4">OfficeBuddy was born from the need to simplify complex payroll processes that burden businesses of all sizes.</p>
                    <p>Founded in 2024, our mission is to automate tedious payroll tasks so you can focus on what matters most - growing your business and taking care of your employees.</p>
                    
                    <h3 class="mt-5 mb-4">What We Do</h3>
                    <p>We provide a comprehensive payroll management system that handles everything from attendance tracking to tax calculations and payslip generation. Our platform is designed to be intuitive yet powerful, catering to businesses ranging from startups to enterprises.</p>
                    
                    <h3 class="mt-5 mb-4">Our Values</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>💡 Innovation</h5>
                            <p>Constantly improving our platform with the latest technology.</p>
                        </div>
                        <div class="col-md-6">
                            <h5>🛡️ Reliability</h5>
                            <p>Your payroll data is secure and always accessible.</p>
                        </div>
                        <div class="col-md-6">
                            <h5>🤝 Partnership</h5>
                            <p>We work with you to understand your unique needs.</p>
                        </div>
                        <div class="col-md-6">
                            <h5>📈 Growth</h5>
                            <p>Helping businesses scale with efficient payroll solutions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light py-5 mt-5">
        <div class="container text-center">
            <h2 class="mb-4">Join Thousands of Happy Businesses</h2>
            <p class="lead mb-4">Experience the OfficeBuddy difference in your payroll management.</p>
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