<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-1/3 bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Forgot Password</h2>

        @if(session('status'))
            <p class="text-green-500">{{ session('status') }}</p>
        @endif

        @if($errors->any())
            <p class="text-red-500 mb-4">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-black">Email</label>
                <input type="email" name="email" required placeholder="Enter your email"
                       class="w-full p-2 border border-gray-300 rounded">
            </div>

        <button type="submit" class="w-full bg-red-500 text-white py-2 rounded">Send Reset Link</button>

            
        </form>

        <p class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Back to Login</a>
        </p>
    </div>
</body>
</html>
