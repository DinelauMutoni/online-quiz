<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Online Quiz System project</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #f8f9fa;
            }
            .hero-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 100px 0;
                margin-bottom: 50px;
            }
            .feature-card {
                border: none;
                border-radius: 15px;
                transition: transform 0.3s ease;
                margin-bottom: 30px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            .feature-card:hover {
                transform: translateY(-10px);
            }
            .feature-icon {
                font-size: 2.5rem;
                color: #667eea;
                margin-bottom: 20px;
            }
            .navbar {
                background: rgba(255, 255, 255, 0.95);
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .navbar-brand {
                font-weight: 600;
                color: #667eea;
            }
            .nav-link {
                font-weight: 500;
            }
            .btn-primary {
                background: #667eea;
                border: none;
                padding: 10px 25px;
                border-radius: 25px;
            }
            .btn-primary:hover {
                background: #764ba2;
            }
            .footer {
                background: #2d3748;
                color: white;
                padding: 50px 0;
                margin-top: 100px;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-graduation-cap me-2"></i>Online Quiz System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @if (Route::has('login'))
                            @auth
                                @if(auth()->user()->is_admin)
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/quizzes') }}" class="nav-link">Admin Dashboard</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a href="{{ url('/student/quizzes') }}" class="nav-link">My Quizzes</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="btn btn-primary ms-2">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold mb-4">Welcome to Online Quiz System</h1>
                        <p class="lead mb-4">Test your knowledge, track your progress, and learn with our interactive quiz platform.</p>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Get Started</a>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/online-exam-3462295-2895977.png" alt="Quiz Illustration" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="container">
            <h2 class="text-center mb-5">Why Choose Our Platform?</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-clock feature-icon"></i>
                            <h3>Timed Quizzes</h3>
                            <p>Challenge yourself with time-limited quizzes to test your knowledge under pressure.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-chart-line feature-icon"></i>
                            <h3>Track Progress</h3>
                            <p>Monitor your performance and see your improvement over time with detailed analytics.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-mobile-alt feature-icon"></i>
                            <h3>Mobile Friendly</h3>
                            <p>Take quizzes on any device with our responsive and user-friendly design.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="container text-center py-5">
            <h2 class="mb-4">Ready to Test Your Knowledge?</h2>
            <p class="lead mb-4">Join thousands of students who are already improving their skills with our quizzes.</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Sign Up Now</a>
            @endif
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <h5>About Us</h5>
                        <p>We provide an interactive platform for students to test their knowledge through online quizzes.</p>
                    </div>
                    <div class="col-lg-4">
                        <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white">Home</a></li>
                            <li><a href="#" class="text-white">About</a></li>
                            <li><a href="#" class="text-white">Contact</a></li>
                            <li><a href="#" class="text-white">Terms of Service</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h5>Contact Us</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-envelope me-2"></i> support@onlinequiz.com</li>
                            <li><i class="fas fa-phone me-2"></i> +1 234 567 890</li>
                        </ul>
                    </div>
                </div>
                <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">
                <div class="text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Online Quiz System. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
