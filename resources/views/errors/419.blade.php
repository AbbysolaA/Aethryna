<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired | Skills Co-op</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F5F5;
        }
        
        .rotate-slow {
            animation: rotate 4s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br py-20 from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    
    <!-- Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-20 right-10 w-72 h-72 bg-[#E8B647] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-[#055860] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- Error Content -->
    <div class="relative z-10 max-w-4xl w-full fade-in">
        <div class="text-center">
            
            <!-- Clock Icon with 419 -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center">
                    <div class="relative">
                        <div class="w-40 h-40 bg-gradient-to-br from-[#055860] to-[#E8B647] rounded-full flex items-center justify-center shadow-2xl">
                            <i class="fas fa-clock text-white text-6xl"></i>
                        </div>
                        <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 bg-white px-6 py-2 rounded-full shadow-lg border-4 border-[#E8B647]">
                            <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#055860] to-[#E8B647]">419</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4 mt-12">
                Session Expired
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Your session has timed out for security reasons. This usually happens when you've been inactive for a while or your form took too long to submit.
            </p>

            <!-- Info Box -->
            <div class="bg-white border-l-4 border-[#055860] shadow-sm p-6 rounded-r-xl max-w-2xl mx-auto mb-8">
                <div class="flex items-start text-left">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-[#055860] text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Why did this happen?</h3>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li class="flex items-start">
                                <i class="fas fa-check text-[#055860] mr-2 mt-1"></i>
                                <span>Your session expired due to inactivity</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-[#055860] mr-2 mt-1"></i>
                                <span>The security token (CSRF) has expired</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-[#055860] mr-2 mt-1"></i>
                                <span>You may have opened the page in multiple tabs</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <button onclick="location.reload()" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#055860] to-[#E8B647] text-white font-bold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <i class="fas fa-redo mr-2"></i>
                    Refresh Page
                    <div class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
                </button>
                
                <a href="/" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105 border-2 border-gray-200">
                    <i class="fas fa-home mr-2"></i>
                    Go to Homepage
                </a>
            </div>

            <!-- Help Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center justify-center">
                    <i class="fas fa-question-circle text-[#055860] mr-2"></i>
                    What should I do?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center">
                                <span class="text-[#055860] font-bold text-lg">1</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1">Refresh the Page</h3>
                            <p class="text-sm text-gray-600">Click the "Refresh Page" button above or press F5 on your keyboard.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                                <span class="text-[#E8B647] font-bold text-lg">2</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1">Try Again</h3>
                            <p class="text-sm text-gray-600">Re-enter your information and submit the form again.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-gray-500 text-sm">
                <p>Need immediate assistance? <a href="mailto:hello@skillscoop.org" class="text-[#055860] hover:text-[#E8B647] font-semibold">Email Support</a></p>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh after 20 seconds with countdown
        let countdown = 20;
        const countdownElement = document.createElement('div');
        countdownElement.className = 'fixed bottom-8 right-8 bg-white px-6 py-3 rounded-full shadow-lg border-2 border-[#055860]';
        countdownElement.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-clock text-[#055860]"></i>
                <span class="text-sm text-gray-600">Auto-refresh in <strong class="text-[#055860]" id="countdown">${countdown}</strong>s</span>
            </div>
        `;
        document.body.appendChild(countdownElement);

        const interval = setInterval(() => {
            countdown--;
            document.getElementById('countdown').textContent = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                location.reload();
            }
        }, 1000);
    </script>

</body>
</html>
