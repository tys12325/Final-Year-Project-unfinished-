<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Background Video -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
        <video autoplay loop muted playsinline class="w-full h-full object-cover">
            <source src="{{ asset('videos/login.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <!-- Login Form -->
    <div class="relative w-[450px] h-[420px] bg-white/20 backdrop-blur-md shadow-lg rounded-xl p-8 z-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Log in</h2>

        @if(session('success'))
            <p class="text-green-500">{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p class="text-red-500 mb-4">{{ session('error') }}</p>
        @endif

        @if($errors->any())
            <p class="text-red-500 mb-4">{{ $errors->first() }}</p>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <label class="block text-black">Email</label>
            <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded-lg mb-4" required>

            <label class="block text-black">Password</label>
            <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded-lg mb-4" required>


            <div class="flex justify-between items-center mb-6">
                <a href="{{ url('/forgot-gmail') }}" class="text-white text-sm font-medium ml-auto hover:underline drop-shadow-md">
                    Forgot password?
                </a>
            </div>
            <button class="w-full bg-black text-white border border-black py-2 rounded-lg hover:bg-gray-900 transition">
                Login
            </button>
            <p class="text-center text-gray-200 text-sm mt-4 drop-shadow-md">
                Don't have an account? 
                <a href="{{ url('/register') }}" class="text-[#38BDF8] font-medium hover:underline drop-shadow-md">
                    Register
                </a>
            </p>
        </form>
        
    </div>
</body>
</html>
