<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Error 403</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #1d4ed8 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Main Error Container -->
    <div class="max-w-md w-full">
        <!-- Error Card -->
        <div class="bg-white rounded-2xl p-8 text-center shadow-xl">
            <!-- Error Icon -->
            <div class="mb-6 flex justify-center">
                <div class="w-24 h-24 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="ph ph-shield-slash text-white text-5xl"></i>
                </div>
            </div>

            <!-- Error Content -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold mb-3 text-gray-800">
                    Akses Ditolak
                </h1>
                <p class="text-gray-600 mb-2">
                    Anda tidak memiliki hak akses untuk melihat halaman ini.
                </p>
                <p class="text-sm text-gray-500 font-mono bg-gray-100 py-2 px-4 rounded-lg inline-block">
                    HTTP ERROR 403
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <!-- Primary Button - Dashboard -->
                <button 
                    onclick="goToDashboard()" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-3"
                >
                    <i class="ph ph-house text-xl"></i>
                    <span>Kembali ke Dashboard</span>
                </button>

                <!-- Secondary Buttons Row -->
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        onclick="goBack()" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="ph ph-arrow-left text-lg"></i>
                        <span class="hidden sm:inline">Kembali</span>
                    </button>
                    
                    <button 
                        onclick="refreshPage()" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="ph ph-arrow-clockwise text-lg"></i>
                        <span class="hidden sm:inline">Refresh</span>
                    </button>
                </div>
            </div>

            <!-- Help Text -->
            <div class="mt-6 text-xs text-gray-500">
                <p class="flex items-center justify-center gap-1">
                    <i class="ph ph-keyboard"></i>
                    Tekan <kbd class="bg-gray-200 px-2 py-1 rounded text-gray-700 font-mono">Enter</kbd> untuk Dashboard
                </p>
            </div>
        </div>
    </div>

    <!-- Simple Countdown Display -->
    <div class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg text-gray-700 px-4 py-2 text-sm" id="countdown">
        <i class="ph ph-timer mr-2"></i>
        <span id="countdown-text">Auto redirect in 30s</span>
    </div>

    <script>
        function goToDashboard() {
            window.location.href = '/dashboard';
        }

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/dashboard';
            }
        }

        function refreshPage() {
            window.location.reload();
        }

        // Simple countdown functionality
        let countdown = 30;
        const countdownElement = document.getElementById('countdown-text');
        
        const countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = `Auto redirect in ${countdown}s`;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                countdownElement.textContent = 'Redirecting...';
                goToDashboard();
            }
        }, 1000);

        // Basic keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            switch(event.key) {
                case 'Enter':
                    goToDashboard();
                    break;
                case 'Escape':
                    goBack();
                    break;
                case 'F5':
                    event.preventDefault();
                    refreshPage();
                    break;
            }
        });
    </script>
</body>
</html>