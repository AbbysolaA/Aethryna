<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | Skills Co-op</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F5F5;
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body class="bg-gradient-to-br py-20 from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    
    <!-- Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#055860] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-[#E8B647] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- Error Content -->
    <div class="relative z-10 max-w-4xl w-full">
        <div class="text-center">
            
            <!-- 500 with Warning Icon -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center">
                    <div class="relative shake">
                        <div class="w-40 h-40 bg-gradient-to-br from-[#055860] to-[#E8B647] rounded-full flex items-center justify-center shadow-2xl">
                            <i class="fas fa-exclamation-triangle text-white text-6xl"></i>
                        </div>
                        <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 bg-white px-6 py-2 rounded-full shadow-lg border-4 border-[#055860]">
                            <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#055860] to-[#E8B647]">500</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4 mt-12">
                Something Went Wrong
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                We're experiencing technical difficulties. Our team has been notified and is working to fix the issue.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <button onclick="location.reload()" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#055860] to-[#E8B647] text-white font-bold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <i class="fas fa-redo mr-2"></i>
                    Try Again
                    <div class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
                </button>
                
                <a href="/" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105 border-2 border-gray-200">
                    <i class="fas fa-home mr-2"></i>
                    Go Home
                </a>
            </div>

            <!-- Support Box -->
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-headset text-[#055860] mr-2"></i>
                    Need Help?
                </h2>
                <p class="text-gray-600 mb-6">
                    If this problem persists, please contact our support team with the error details.
                </p>
                <a href="mailto:hello@skillscoop.org" class="inline-flex items-center justify-center px-6 py-3 bg-[#055860] text-white font-semibold rounded-lg hover:bg-[#E8B647] transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Support
                </a>
            </div>
        </div>
    </div>

</body>
</html>
