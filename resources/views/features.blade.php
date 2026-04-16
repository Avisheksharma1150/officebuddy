<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - OfficeBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Figtree', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    .features-section {
        padding: 100px 0 50px;
        color: white;
    }
    .feature-card {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        margin: 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
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
    .feature-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        display: block;
    }
    
    /* Consistent spacing for feature cards */
    .features-grid .row {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 1.5rem;
    }
    
    /* Typography improvements */
    .feature-card h4 {
        font-weight: 600;
        color: #2d3748;
        font-size: 1.25rem;
        line-height: 1.4;
        margin-bottom: 1rem;
    }
    
    .feature-card p {
        color: #718096;
        line-height: 1.6;
        margin-bottom: 0;
        flex-grow: 1;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .feature-card {
            padding: 2rem;
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .features-section {
            padding: 80px 0 30px;
        }
    }
    
    @media (max-width: 576px) {
        .feature-card {
            padding: 1.5rem;
        }
        
        .feature-icon {
            font-size: 2.5rem;
        }
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('features') }}">Features</a></li>
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

    <div class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Powerful Features</h1>
                    <p class="lead">Everything you need to streamline your payroll process in one platform</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
    <div class="row g-4"> <!-- Added g-4 for consistent gutter spacing -->
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">⏰</div>
                <h4 class="mb-3">Automated Attendance Tracking</h4>
                <p class="flex-grow-1">Automated check-in/check-out system with real-time tracking, late arrival monitoring, and early departure alerts.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">💰</div>
                <h4 class="mb-3">Smart Payroll Automation</h4>
                <p class="flex-grow-1">Automatic salary calculations with tax deductions, provident fund management, and allowance processing.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">📊</div>
                <h4 class="mb-3">Comprehensive Reports</h4>
                <p class="flex-grow-1">Generate detailed payroll reports, attendance summaries, and downloadable payslips in PDF format.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">🧾</div>
                <h4 class="mb-3">Tax Calculation</h4>
                <p class="flex-grow-1">Automatically calculate and deduct taxes according to current regulations and tax brackets.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">📄</div>
                <h4 class="mb-3">Payslip Generation</h4>
                <p class="flex-grow-1">Create professional, customizable payslips with company branding and automatic distribution.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">🔒</div>
                <h4 class="mb-3">Secure Data Management</h4>
                <p class="flex-grow-1">Bank-level security with encrypted data storage and role-based access control for your payroll information.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">🔄</div>
                <h4 class="mb-3">Integration Ready</h4>
                <p class="flex-grow-1">Seamlessly integrate with your existing HR systems, accounting software, and time tracking tools.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">📱</div>
                <h4 class="mb-3">Mobile Access</h4>
                <p class="flex-grow-1">Access payroll information and approve requests on-the-go with our mobile-friendly platform.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card text-center h-100 d-flex flex-column">
                <div class="feature-icon mb-3">🎯</div>
                <h4 class="mb-3">Compliance Management</h4>
                <p class="flex-grow-1">Stay updated with changing labor laws and tax regulations with automatic compliance updates.</p>
            </div>
        </div>
    </div>
    </div>
    <div class="bg-light py-5 mt-5">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Transform Your Payroll Process?</h2>
            <p class="lead mb-4">Join thousands of businesses using OfficeBuddy to automate their payroll management.</p>
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