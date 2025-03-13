@extends('layout.adminInterface')

@section('title', 'Feedback Chart')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<style>
        .center {
        text-align: center;
        margin: 30px auto;
        display: block;
        width: 100%;
        padding-top: 20px;
    }

    .chart-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        padding: 25px;
        max-width: 1600px;
        margin: 0 auto;
    }
    .chart-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }
    
    .chart-card:hover {
        transform: translateY(-5px);
    }
    
    .chart-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #2c3e50;
        text-align: center;
    }
    
    .chart-wrapper {
        position: relative;
        margin: 0 auto;
        width: 100%;
        max-width: 280px;
    }
    
    @media (max-width: 768px) {
        .chart-container {
            grid-template-columns: 1fr;
            padding: 15px;
        }
    }
   
</style>
@endsection

@section('content')
<a href="#" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
    <h2 class="center">Feedback Analysis Report</h2>


<div class="chart-container">

    <div class="chart-card">
        <h3 class="chart-title">Were Search Filters Useful?</h3>
        <div class="chart-wrapper">
            <canvas id="question1Chart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3 class="chart-title">Enough Options for Comparison?</h3>
        <div class="chart-wrapper">
            <canvas id="question2Chart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3 class="chart-title">Found All Information?</h3>
        <div class="chart-wrapper">
            <canvas id="question3Chart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3 class="chart-title">Want More Features?</h3>
        <div class="chart-wrapper">
            <canvas id="question4Chart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3 class="chart-title">Overall Rating</h3>
        <div class="chart-wrapper">
            <canvas id="ratingChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {

        Chart.register(ChartDataLabels);

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: {
                            size: 14,
                            family: "'Inter', sans-serif"
                        },
                        color: '#34495e'
                    }
                },
                tooltip: {
                    bodyFont: { size: 14 },
                    titleFont: { size: 16 }
                },

                datalabels: {
                    color: '#fff',
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    formatter: (value, context) => {
                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);


                    if (total === 0 || value === 0) {
                    return '';
                    }

                    const percentage = ((value / total) * 100).toFixed(1) + '%';
                    return percentage;
                    }

                }
            },
            elements: {
                arc: {
                    borderWidth: 2,
                    borderColor: '#fff'
                }
            }
        };

        function createChart(canvasId, labels, data, colors) {
        const total = data.reduce((a, b) => a + b, 0);

        if (total === 0) {

        document.getElementById(canvasId).parentNode.innerHTML = 
            `<p style="text-align:center; font-size:14px; color:#888;">No feedback available</p>`;
        return;
        }

        new Chart(document.getElementById(canvasId), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                hoverOffset: 10
            }]
        },
        options: chartOptions,
        plugins: [ChartDataLabels] 
        });
        }

   
        createChart('question1Chart', ["Yes", "No"], 
            [{{ $feedbackData['filters1']->yes }}, {{ $feedbackData['filters1']->no }}], 
            ["#10B981", "#EF4444"]
        );

        createChart('question2Chart', ["Yes", "No"], 
            [{{ $feedbackData['filters2']->yes }}, {{ $feedbackData['filters2']->no }}], 
            ["#3B82F6", "#F59E0B"]
        );

        createChart('question3Chart', ["Yes", "No"], 
            [{{ $feedbackData['filters3']->yes }}, {{ $feedbackData['filters3']->no }}], 
            ["#8B5CF6", "#EC4899"]
        );

        createChart('question4Chart', ["Yes", "No"], 
            [{{ $feedbackData['filters4']->yes }}, {{ $feedbackData['filters4']->no }}], 
            ["#14B8A6", "#F97316"]
        );

        createChart('ratingChart', ["Poor", "Fair", "Good", "Very Good", "Excellent"], 
            [
                {{ $feedbackData['rating']->poor }},
                {{ $feedbackData['rating']->fair }},
                {{ $feedbackData['rating']->good }},
                {{ $feedbackData['rating']->very_good }},
                {{ $feedbackData['rating']->excellent }}
            ], 
            ["#EF4444", "#F59E0B", "#EAB308", "#84CC16", "#10B981"]
        );
    });
</script>
@endsection