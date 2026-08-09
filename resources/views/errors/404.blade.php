<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.gtm-head')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Skills Co-op</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F5F5;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .ath-gradient-text {
            background: linear-gradient(135deg, #055860, #E8B647);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .ath-gradient-bg {
            background: linear-gradient(135deg, #055860, #E8B647);
        }
    </style>
</head>
<body class="bg-gradient-to-br py-20 from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    @include('partials.gtm-body')
    
    <!-- Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-64 h-64 bg-[#055860] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-[#E8B647] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- Error Content -->
    <div class="relative z-10 max-w-4xl w-full">
        <div class="text-center">
            
            <!-- 404 Number with Icon -->
            <div class="mb-8 floating">
                <div class="inline-flex items-center justify-center">
                    <span class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#055860] to-[#E8B647]">4</span>
                    <div class="mx-4 relative">
                        <div class="w-32 h-32 bg-gradient-to-br from-[#055860] to-[#E8B647] rounded-full flex items-center justify-center shadow-2xl pulse-slow">
                            <i class="fas fa-compass text-white text-5xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-[#E8B647] rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation text-[#055860] text-sm"></i>
                        </div>
                    </div>
                    <span class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#E8B647] to-[#055860]">4</span>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Oops! Page Not Found
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                The page you're looking for seems to have wandered off the path. Let's get you back on track to your learning journey.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <a href="/" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#055860] to-[#E8B647] text-white font-bold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <i class="fas fa-home mr-2"></i>
                    Back to Home
                    <div class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
                </a>
                
                <button onclick="window.history.back()" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105 border-2 border-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Go Back
                </button>
            </div>

            <!-- Helpful Links -->
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center justify-center">
                    <i class="fas fa-lightbulb text-[#E8B647] mr-2"></i>
                    Helpful Links
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="/assessment" class="group p-4 rounded-xl hover:bg-teal-50 transition-colors border border-gray-100">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-[#055860] group-hover:text-white transition-colors">
                            <i class="fas fa-clipboard-list text-[#055860] text-xl group-hover:text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1">Take Assessment</h3>
                        <p class="text-sm text-gray-500">Find your pathway</p>
                    </a>
                    
                    <a href="/pathway" class="group p-4 rounded-xl hover:bg-orange-50 transition-colors border border-gray-100">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-[#E8B647] group-hover:text-white transition-colors">
                            <i class="fas fa-route text-[#E8B647] text-xl group-hover:text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1">View Pathways</h3>
                        <p class="text-sm text-gray-500">Explore options</p>
                    </a>
                    
                    <a href="/login" class="group p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-gray-800 group-hover:text-white transition-colors">
                            <i class="fas fa-sign-in-alt text-gray-600 text-xl group-hover:text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1">Login</h3>
                        <p class="text-sm text-gray-500">Access your account</p>
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-gray-500 text-sm">
                <p>Need help? <a href="mailto:hello@skillscoop.org" class="text-[#055860] hover:text-[#E8B647] font-semibold">Contact Support</a></p>
            </div>
        </div>
    </div>

</body>
</html>
