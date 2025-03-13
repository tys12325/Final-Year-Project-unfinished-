<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Background Video -->
    
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
        <video autoplay loop muted playsinline class="w-full h-full object-cover">
            <source src="{{ asset('videos/register.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <!-- Form Container -->
    <div class="relative w-[450px] bg-white/20 backdrop-blur-md shadow-xl rounded-2xl p-8 z-10 border border-white/30">

        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Sign Up</h2>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <label class="block text-black">Full Name</label>
            <input type="text" name="name" class="w-full p-2 border border-gray-300 rounded mb-4" required>

            <label class="block text-black">Email</label>
            <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded mb-4" required>

            <label class="block text-black">Password</label>
            <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded mb-4" required>

            <label class="block text-black">Re-enter Password</label>
            <input type="password" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded mb-6" required>
             <label class="block text-black">Phone Number</label>
            <input type="text" name="phone" class="w-full p-2 border border-gray-300 rounded mb-6" required>
            

            <button type="submit" class="w-full bg-black text-white border border-black py-2 rounded mb-4 hover:bg-gray-900 transition">
                Register
            </button>

            <!-- Login Link -->
            <p class="text-center text-gray-200 text-sm mt-4 drop-shadow-md">
                Already have an account?  
                <a href="{{ url('/login') }}" class="text-[#38BDF8] font-medium hover:underline">Login</a>
            </p>
        </form>
    </div>
</body>
</html>
