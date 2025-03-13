<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination Example</title>
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
</head>
<body>
    @if ($paginator->hasPages())
 <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-wrapper">
    <div class="pagination-container">
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-prev">Previous «</a>
        @endif
        
        @php $previousWasNumber = false; @endphp

        @foreach ($elements as $element)
            @if (is_array($element))
                @php $dots = false; @endphp
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage()) 
                        <span class="pagination-number active">{{ $page }}</span> <!-- Highlight current page -->
                        @php $dots = false; $previousWasNumber = true; @endphp
                    @elseif ($page == 1 || $page == $paginator->lastPage() || abs($page - $paginator->currentPage()) <= 2)
                        <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @php $dots = false; $previousWasNumber = true; @endphp
                    @elseif (!$dots && $previousWasNumber)
                        <span class="pagination-dots">...</span>
                        @php $dots = true; $previousWasNumber = false; @endphp
                    @endif
                @endforeach
            @endif
        @endforeach


        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next">Next »</a>
        @endif
    </div>

    <p class="pagination-info">
        Showing <span class="font-medium">{{ $paginator->firstItem() }}</span> to 
        <span class="font-medium">{{ $paginator->lastItem() }}</span> of 
        <span class="font-medium">{{ $paginator->total() }}</span> results
    </p>
</nav>
    @endif
</body>
</html>
