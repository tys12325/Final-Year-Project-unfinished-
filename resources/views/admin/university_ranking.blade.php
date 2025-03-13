@extends('layout.adminInterface')

@section('title', 'University Ranking Report')
@section('head')
<style>
.report-container {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2.5rem;
    margin: 2rem auto;
    max-width: 1200px;
    font-family: 'Segoe UI', system-ui, sans-serif;
    animation: fadeIn 0.6s ease-out;
}


.report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2rem;
    gap: 2rem;
}

.report-title {
    color: #1a365d;
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}


.filter-container {
    position: relative;
    flex: 0 1 400px;
    
}



.university-filter{
    width: 100%;
    padding: 0.9rem 1.4rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234a90e2' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e") no-repeat right 1rem center;
    font-size: 1rem;
    color: #2d3748;
    transition: all 0.2s ease;
    appearance: none;
}

.university-filter:hover {
    border-color: #cbd5e0;
}

.zerouniversity-filter:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    outline: none;
}


.ranking-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.5rem;
    margin: 2rem 0;
}

.ranking-table thead {
    background: linear-gradient(135deg, #4a90e2 0%, #2b6cb0 100%);
    color: white;
    border-radius: 8px;
}

.ranking-table th {
    padding: 1.2rem 1.8rem;
    font-weight: 600;
    text-align: left;
    font-size: 1rem;
}

.ranking-table th:first-child {
    border-radius: 8px 0 0 8px;
}

.ranking-table th:last-child {
    border-radius: 0 8px 8px 0;
}

.ranking-table td {
    padding: 1.4rem 1.8rem;
    background: #f8fafc;
    border: none;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    color: #4a5568;
}

.ranking-table tr:hover td {
    background: #f1f5f9;
    transform: translateX(4px);
}


.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #4a90e2;
    color: white;
    border-radius: 50%;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}


.rating-value {
    color: #2b6cb0;
    font-weight: 600;
    font-size: 1.1rem;
    position: relative;
    padding-right: 1.2rem;
  
}

.rating-value::after {
    content: '/5';
    position: absolute;
    right: 50px;
    color: #cbd5e0;
    font-weight: 400;
    font-size: 0.9rem;
    
}

.review-count {
    background: #e2e8f0;
    color: #4a5568;
    padding: 0.3rem 0.8rem;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-left: 0.8rem;
}


.chart-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    margin-top: 3rem;
    border: 1px solid #e2e8f0;
    position: relative;
}

.chart-header {
    color: #2c3e50;
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.chart-header i {
    color: #4a90e2;
    font-size: 1.2rem;
}


@media (max-width: 768px) {
    .report-container {
        padding: 1.5rem;
        margin: 1rem;
    }

    .report-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1.5rem;
    }

    .filter-container {
        flex: 1 1 auto;
    }

    .ranking-table {
        display: block;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .ranking-table td {
        min-width: 150px;
    }

    .review-count {
        display: block;
        margin-left: 0;
        margin-top: 0.5rem;
    }
}

@media (max-width: 480px) {
    .report-title {
        font-size: 1.8rem;
    }

    .ranking-table th,
    .ranking-table td {
        padding: 1rem 1.2rem;
    }

    .chart-container {
        padding: 1.5rem;
    }
}


@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.ranking-table tr {
    animation: fadeIn 0.4s ease-out forwards;
    animation-delay: calc(var(--i) * 0.1s);
}
</style>
@endsection
@section('content')
<a href="#" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="report-container">
    <div class="report-header">
        <h1 class="report-title">University Ranking Report</h1>
        <div class="filter-container">
            <select class="university-filter" id="university-filter">
                <option value="all">All Universities</option>
                    @foreach ($universities as $uni)
                <option value="{{ $uni->uniID }}">{{ $uni->uniName }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <table class="ranking-table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>University Name</th>
                <th>Average Rating</th>
                <th>Total Reviews</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($universities as $index => $uni)
            <tr style="--i: {{ $index }};">
                <td><span class="rank-badge">{{ $index + 1 }}</span></td>
                <td>{{ $uni->uniName }}</td>
                <td class="rating-value">{{ number_format($uni->average_rating, 2) }}</td>
                <td>
                    <span class="review-count">{{ $uni->total_reviews }} reviews</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="chart-container">
        <div class="chart-header">
            <i class="fas fa-chart-bar"></i>
            Performance Overview
        </div>
        <canvas id="rankingChart"></canvas>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var universities = @json($universities);
    var ctx = document.getElementById('rankingChart').getContext('2d');

   
    var allLabels = universities.map(u => u.uniName);
    var allData = universities.map(u => u.average_rating);


    var rankingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: allLabels,
            datasets: [{
                label: 'Average Rating',
                data: allData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, max: 5 }
            }
        }
    });

  
    document.getElementById('university-filter').addEventListener('change', function () {
        var selectedId = this.value;

        if (selectedId === "all") {
            rankingChart.data.labels = allLabels;
            rankingChart.data.datasets[0].data = allData;
        } else {
            var filtered = universities.find(u => u.uniID == selectedId);
            rankingChart.data.labels = [filtered.uniName];
            rankingChart.data.datasets[0].data = [filtered.average_rating];
        }

        rankingChart.update();
    });
});
</script>
@endsection