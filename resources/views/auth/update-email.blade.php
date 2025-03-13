@extends('layouts.app') {{-- Change to your layout file --}}

@section('content')

<div class="container">
    <h2>Update Your Work Email</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    


    <form method="POST" action="{{ route('email.update') }}" onsubmit="return confirmUpdate()">
        @csrf

        <div class="mb-3">
            <label for="work_email" class="form-label">New Work Email</label>
            <input type="email" class="form-control" id="work_email" name="work_email" placeholder="xxx@company.com" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Email</button>
    </form>
</div>
<script>
    function confirmUpdate() {
        return confirm("⚠️ Important: Updating your email will require verification. This action cannot be undone. Are you sure you want to proceed?");
    }
</script>
@endsection
