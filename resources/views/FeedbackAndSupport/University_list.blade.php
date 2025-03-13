@extends('layout.app')

@section('title', 'University Rating')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    /* Custom Styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        font-size: 2.5rem;
        margin-bottom: 30px;
    }

    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 1rem;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .table th,
    .table td {
        padding: 15px;
        text-align: left;
    }

    .table th {
        background-color: #2c3e50;
        color: #fff;
        font-weight: 600;
    }

    .table tbody tr {
        transition: background-color 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .university-link {
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .university-link:hover {
        color: #2980b9;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        color: #fff;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    /* Fade-out animation for alerts */
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }

    .fade-out {
        animation: fadeOut 1s ease forwards;
    }
    .bold{
        font-weight: bold;
    }
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
 <a href="{{route('feedBackSupport')}}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="container">
    <h2>University Ratings</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">Something went wrong. Please try again.</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($university as $uni)
            <tr onclick="window.location ='{{ route('ratings.create',  $uni->uniID) }}';" style="cursor: pointer;">
                <td  class='bold'>

                    {{ $uni->uniName }}

                </td>
                <td>{{ $uni->Address }}</td>
                <td>
                    <a href="{{ route('ratings.create', $uni->uniID) }}" class="btn btn-primary">
                        Rate This University
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-container">
        {{ $university->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    setTimeout(function () {
        const successMessage = document.querySelector('.alert-success');
        const errorMessage = document.querySelector('.alert-danger');

        if (successMessage) {
            successMessage.classList.add('fade-out');
        }
        if (errorMessage) {
            errorMessage.classList.add('fade-out');
        }
    }, 4000);
</script>
@endsection