<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpecFirst Admin - Requirements Management Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #0f172a;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-ai: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            --glass: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark);
            color: white;
        }
        .hero { 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%236366f1" stop-opacity="0.1"/><stop offset="100%" stop-color="%236366f1" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23a)"/><circle cx="800" cy="300" r="200" fill="url(%23a)"/><circle cx="400" cy="700" r="250" fill="url(%23a)"/></svg>');
            opacity: 0.3;
        }
        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
        }
        .feature-card { 
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .feature-card:hover { 
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
        }
        .floating { animation: float 6s ease-in-out infinite; }
        .pulse { animation: pulse 2s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(5deg); } }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        .ai-glow {
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.3);
        }
        .text-gradient {
            background: var(--gradient-ai);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-ai {
            background: var(--gradient-ai);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-ai:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
            color: white;
        }
        .neural-network {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            background-image: 
                radial-gradient(circle at 20% 20%, #6366f1 2px, transparent 2px),
                radial-gradient(circle at 80% 80%, #10b981 2px, transparent 2px),
                radial-gradient(circle at 40% 60%, #f59e0b 2px, transparent 2px);
            background-size: 100px 100px, 150px 150px, 200px 200px;
            animation: neuralMove 20s linear infinite;
        }
        @keyframes neuralMove {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(-100px) translateY(-100px); }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="#">
                <i class="fas fa-brain me-2 text-gradient"></i>SpecFirst Admin
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon text-white"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="#features">AI Features</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#analytics">Analytics</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero d-flex align-items-center text-white position-relative">
        <div class="neural-network"></div>
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <span class="badge glass-card px-3 py-2 text-white border-0">
                            <i class="fas fa-robot me-2"></i>AI-Powered
                        </span>
                    </div>
                    <h1 class="display-3 fw-bold mb-4">
                        <span class="text-gradient">Intelligent</span> Requirements Management
                    </h1>
                    <p class="lead mb-4 text-light">Advanced AI-driven admin panel for comprehensive project requirements analysis, intelligent insights, and automated workflow management.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#features" class="btn btn-ai btn-lg rounded-pill px-5">
                            <i class="fas fa-cog me-2"></i>Explore AI Features
                        </a>
                        <a href="#dashboard" class="btn btn-outline-light btn-lg rounded-pill px-5">
                            <i class="fas fa-tachometer-alt me-2"></i>View Dashboard
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="floating">
                        <div class="glass-card p-5 d-inline-block ai-glow">
                            <div class="pulse">
                                <i class="fas fa-brain text-gradient" style="font-size: 5rem;"></i>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                                    <div class="bg-secondary rounded-circle" style="width: 8px; height: 8px;"></div>
                                    <div class="bg-accent rounded-circle" style="width: 8px; height: 8px;"></div>
                                </div>
                                <small class="text-light">Neural Network Active</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3 text-white">AI-Powered Features</h2>
                <p class="lead text-light">Advanced artificial intelligence capabilities for comprehensive project requirements management and analysis.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="bg-primary rounded-3 p-3 text-white d-inline-block mb-3">
                            <i class="fas fa-brain fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Neural Interview Engine</h4>
                        <p class="text-light">Advanced AI algorithms that conduct intelligent interviews, extracting comprehensive project requirements through natural language understanding.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="bg-secondary rounded-3 p-3 text-white d-inline-block mb-3">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Smart Analytics Dashboard</h4>
                        <p class="text-light">Real-time AI-driven insights and predictive analytics for project requirements, team performance, and resource optimization.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="bg-accent rounded-3 p-3 text-white d-inline-block mb-3">
                            <i class="fas fa-robot fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Automated Workflow</h4>
                        <p class="text-light">Intelligent automation of requirement gathering processes, document generation, and stakeholder communication workflows.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="bg-primary rounded-3 p-3 text-white d-inline-block mb-3">
                            <i class="fas fa-database fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Intelligent Data Processing</h4>
                        <p class="text-light">AI-powered data analysis and pattern recognition to identify requirement gaps, conflicts, and optimization opportunities.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="bg-secondary rounded-3 p-3 text-white d-inline-block mb-3">
                            <i class="fas fa-shield-virus fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Security & Compliance AI</h4>
                        <p class="text-light">Automated security scanning, compliance checking, and risk assessment using machine learning algorithms.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="bg-accent rounded-3 p-3 text-white d-inline-block mb-3">
                            <i class="fas fa-microchip fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Machine Learning Models</h4>
                        <p class="text-light">Custom ML models trained on project data to predict outcomes, suggest improvements, and optimize requirement processes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Admin Dashboard Section -->
    <section id="dashboard" class="py-5" style="background: var(--dark);">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3 text-white">Admin Dashboard Overview</h2>
                <p class="lead text-light">Comprehensive administrative control panel for managing AI-powered requirements gathering operations.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="text-center mb-3">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-cogs fa-lg"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-white text-center">System Configuration</h4>
                        <p class="text-light text-center flex-grow-1">Configure AI models, set up interview templates, and manage system parameters for optimal performance.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="text-center mb-3">
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-users-cog fa-lg"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-white text-center">User Management</h4>
                        <p class="text-light text-center flex-grow-1">Manage user accounts, roles, permissions, and access controls for the requirements platform.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="text-center mb-3">
                            <div class="bg-accent rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-bar fa-lg"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-white text-center">Analytics & Reports</h4>
                        <p class="text-light text-center flex-grow-1">Monitor system performance, generate reports, and analyze AI effectiveness across all projects.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="text-center mb-3">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-database fa-lg"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-white text-center">Data Management</h4>
                        <p class="text-light text-center flex-grow-1">Manage project data, export requirements, and maintain data integrity across the platform.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- System Status Section -->
    <section id="analytics" class="py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="container py-5 text-center">
            <div class="glass-card p-5 rounded-4">
                <h2 class="display-5 fw-bold mb-3 text-white">System Status & Analytics</h2>
                <p class="lead text-light mb-4">Real-time monitoring and analytics for the SpecFirst AI-powered requirements management system.</p>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-check fa-lg"></i>
                            </div>
                            <h5 class="text-white">System Online</h5>
                            <p class="text-light">All AI services operational</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-brain fa-lg"></i>
                            </div>
                            <h5 class="text-white">AI Processing</h5>
                            <p class="text-light">Neural networks active</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-database fa-lg"></i>
                            </div>
                            <h5 class="text-white">Data Sync</h5>
                            <p class="text-light">Real-time synchronization</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5" style="background: var(--dark); border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <h5 class="fw-bold mb-3 text-white"><i class="fas fa-brain me-2 text-gradient"></i>SpecFirst Admin</h5>
                    <p class="text-light mb-3">Advanced AI-powered requirements management system with intelligent automation and comprehensive administrative controls.</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold mb-3 text-white">Admin Features</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-light text-decoration-none">AI Features</a></li>
                        <li><a href="#dashboard" class="text-light text-decoration-none">Dashboard</a></li>
                        <li><a href="#analytics" class="text-light text-decoration-none">System Status</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.1);">
            <div class="text-center text-light">
                <p>&copy; 2024 SpecFirst Admin Panel. Powered by AI for intelligent requirements management.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Smooth scrolling
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 80
                    }, 1000);
                }
            });
        });
    </script>
</body>
</html>
