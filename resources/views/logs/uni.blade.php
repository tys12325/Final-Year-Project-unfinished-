@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .header-section {
            background-color: white;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
            height: 90px;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .header-section h1 {
            margin: 0;
            font-weight: bold;
            font-size: 1.8em;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            white-space: nowrap;
            text-align: center;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
            position: absolute;
            right: 30px;
        }

        .header-buttons button {
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .download-btn {
            background-color: orange;
            color: white;
            border: none;
        }

        .print-btn {
            background-color: white;
            color: black;
            border: 1px solid gray;
        }

        .download-btn:hover, .print-btn:hover {
            opacity: 0.7;
        }

        /* Log Styles */
        .log-added {
            background-color: #f0fff4; /* Light green */
        }
        .log-updated {
            background-color: #fffaf0; /* Light orange */
        }
        .log-deleted {
            background-color: #fff5f5; /* Light red */
        }
        .course-log-link {
            position: absolute;
            left: 30px; /* Adjust as needed */
            top: 50%;
            transform: translateY(-50%);
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            color: white;
            background-color: black; /* Blue color */
            border-radius: 15px;
            text-decoration: none;
            transition: 0.3s;
        }

        .course-log-link:hover {
            opacity: 0.75;
        }
        .header-section a:hover{
            text-decoration: underline;
        }
        .refresh-logs{
            margin-bottom: 100px;
        }
        .hidden-for-pdf {
            display: none !important; /* Hide elements with this class */
        }

@media print {
        body {
            width: 420mm; /* A2 width */
            height: 594mm; /* A2 height */
            margin: 0;
            padding: 10mm; /* Add some padding */
            font-size: 12pt; /* Adjust font size for readability */
        }
        /* Hide buttons when printing */
        #refresh-logs, #clear-logs {
            display: none;
        }
    }
    </style>
    <!-- Main Content -->
<div id="body">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="header-section">
            <!-- Course Log Link on the Top Left -->
            <a href="{{ url('course-logs') }}" class="course-log-link">
                Programme Logs
            </a>

            <!-- Centered Heading -->
            <strong><h1><a href="{{ route('adminDashboard') }}">University Logs</a></h1></strong>

            <!-- Buttons on the Top Right -->
            <div class="header-buttons">
                <button class="download-btn" id="downloadPdf">Download Report</button>
                <button class="print-btn" id="printReport">Print Report</button>
            </div>
        </div>

        <!-- Logs Container -->
        <div id="logs-container" class="bg-white p-6 rounded-lg shadow-md mb-6">
            <table id="logs-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody id="logs-body" class="bg-white divide-y divide-gray-200">
                    <!-- Log rows will be dynamically inserted here -->
                </tbody>
            </table>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 mb-9">
            <button id="refresh-logs" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                Refresh Logs
            </button>
            <button id="clear-logs" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200">
                Clear Logs
            </button>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function () {
            // Fetch logs on page load
            fetchLogs();

            // Refresh logs button
            $('#refresh-logs').click(function () {
                fetchLogs();
            });

            // Clear logs button
            $('#clear-logs').click(function () {
                if (confirm('Are you sure you want to clear all logs?')) {
                    $.ajax({
                        url: '/clear-uni-logs', // Ensure this endpoint is correct
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token for security
                        },
                        success: function (response) {
                            alert(response.message);
                            fetchLogs(); // Refresh logs after clearing
                        },
                        error: function (xhr, status, error) {
                            alert('Failed to clear logs. Error: ' + error); // Show detailed error message
                            console.error(xhr.responseText); // Log the error for debugging
                        }
                    });
                }
            });

            // Function to fetch logs
            function fetchLogs() {
                $.ajax({
                    url: '/fetch-uni-logs', // Updated endpoint
                    method: 'GET',
                    success: function (response) {
                        displayLogs(response.logs); // Display logs
                    },
                    error: function () {
                        $('#logs-body').html('<tr><td colspan="3" class="text-center text-red-500 py-4">Failed to load logs.</td></tr>');
                    }
                });
            }

            // Function to display logs in a table
            function displayLogs(logs) {
                const logBody = $('#logs-body');
                logBody.empty(); // Clear existing logs

                if (!logs) {
                    logBody.html('<tr><td colspan="3" class="text-center text-gray-500 py-4">No logs found.</td></tr>');
                    return;
                }

                // Split logs by newline and process each line
                logs.split('\n').forEach(log => {
                    if (!log.trim()) return; // Skip empty lines

                    // Extract timestamp
                    const timestamp = log.match(/\[(.*?)\]/)?.[1] || 'N/A';

                    // Determine action and row class
                    let action = 'Other';
                    let rowClass = '';
                    if (log.includes('New University Added') || log.includes('University Created')) {
                        action = 'Added';
                        rowClass = 'log-added';
                    } else if (log.includes('University Update')) {
                        action = 'Updated';
                        rowClass = 'log-updated';
                    } else if (log.includes('University deleted')) {
                        action = 'Deleted';
                        rowClass = 'log-deleted';
                    }

                    // Extract the rest of the log details
                    let details = log.replace(/\[.*?\]/, '').trim(); // Remove timestamp
                    details = details.replace(/local\.INFO:\s*/, ''); // Remove "local.INFO:"

                    // Bold specific keywords
                    const keywords = [
                        'New University Added:',
                        'University Created',
                        'University Update - Before Changes',
                        'University Update - After Changes',
                        'University deleted successfully'
                    ];
                    keywords.forEach(keyword => {
                        details = details.replace(new RegExp(keyword, 'g'), `<strong>${keyword}</strong>`);
                    });

                    // Append log row to the table
                    logBody.append(`
                        <tr class="${rowClass}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${timestamp}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">${action}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${details}</td>
                        </tr>
                    `);
                });
            }
        });
        // Print Report button
        document.getElementById('printReport').addEventListener('click', function () {
            window.print(); // Opens the browser's print dialog
        });

        // Download PDF button
        document.getElementById('downloadPdf').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape'); // Set to landscape mode

            // Hide buttons
            const buttons = document.querySelectorAll('.header-buttons,.course-log-link,#refresh-logs,#clear-logs');
            buttons.forEach(button => button.classList.add('hidden-for-pdf'));

            const element = document.getElementById('body'); // The container with the logs

            // Use jsPDF's html method to generate the PDF
            doc.html(element, {
                callback: function (doc) {
                    // Save the PDF
                    doc.save('University_Logs_Report.pdf');

                    // Restore buttons after the PDF is saved
                    buttons.forEach(button => button.classList.remove('hidden-for-pdf'));
                },
                x: 10,
                y: 10,
                width: 280,
                windowWidth: 1200,
            });
        });
    </script>
@endsection