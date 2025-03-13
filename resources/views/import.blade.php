@extends('layouts.adminLayout')

@section('content')
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .container {
            margin-top:50px;
            background: #fff;
            padding: 30px; /* More padding */
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 650px; /* Increase width */
            text-align: center;
            font-size: 18px; /* Increase overall text size */
        }

        .container h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px; /* Increase heading size */
        }

        /* Dropdown and File Input Styling */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            font-size: 16px; /* Make labels bigger */
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #444;
        }

        select, input[type="file"] {
            width: 100%;
            padding: 12px; /* Bigger input fields */
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }

        /* Button Styling */
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }

        /* Log Box Styling */
        .log-box {
            margin-top: 20px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            height: 150px;
            overflow-y: auto;
            text-align: left;
            font-size: 14px;
            color: #333;
        }

        .log-box span {
            display: block;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 700px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <div class="container">
        <h2>Import CSV File</h2>
        <form id="importForm" action="{{ route('courses.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Dropdown to Select Data Type -->
            <div class="form-group">
                <label for="type">Select Data Type:</label>
                <select name="type" id="type" required>
                    <option value="uni">Universities</option>
                    <option value="course">Programme</option>
                </select>
            </div>

            <!-- File Upload Input -->
            <div class="form-group" id="fileInputContainer">
                <label for="file">Choose File:</label>
                <input type="file" name="file" id="file" required>
            </div>

            <!-- Hidden input for selected rows -->
            <input type="hidden" name="selectedRows" id="selectedRows">

            <!-- Submit Button -->
            <button type="submit" class="btn">Upload & Import</button>
        </form>

        <!-- Logging Box -->
        <h3 style="margin-top: 15px; font-size: 18px; color: #333;">Import Logs</h3>
        <div class="log-box">
            @if(session('logMessage'))
                {!! session('logMessage') !!}
            @else
                No logs yet. Upload a file to see logs.
            @endif
        </div>
    </div>

    <!-- Modal for CSV Data Review -->
    <div id="csvReviewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Review CSV Data</h2>
            <div id="csvDataContainer"></div>
            <button id="confirmImport" class="btn">Confirm Import</button>
        </div>
    </div>

<script>
    document.getElementById('importForm').addEventListener('submit', function(event) {
        event.preventDefault();

        if (confirm("Do you want to review the data manually before importing? Click 'OK' to review or 'Cancel' to directly import.")) {
            const file = document.getElementById('file').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const text = e.target.result;
                    const rows = text.split('\n').map(row => row.trim().split(','));

                    // Remove empty rows
                    const filteredRows = rows.filter(row => row.length > 1 && row.some(cell => cell.trim() !== ""));

                    // Remove header row
                    if (filteredRows.length > 0) filteredRows.shift();

                    displayCSVData(filteredRows);
                };
                reader.readAsText(file);
            }
        } else {
            document.getElementById('selectedRows').value = JSON.stringify([]);
            document.getElementById('importForm').submit();
        }
    });

    function displayCSVData(rows) {
        const container = document.getElementById('csvDataContainer');
        container.innerHTML = '';

        rows.forEach((row, index) => {
            const rowDiv = document.createElement('div');
            rowDiv.innerHTML = `
                <input type="checkbox" id="row${index}" name="row${index}" checked data-row='${JSON.stringify(row)}'>
                <label for="row${index}">${row.join(', ')}</label>
            `;
            container.appendChild(rowDiv);
        });

        document.getElementById('csvReviewModal').style.display = 'block';
    }

    document.getElementById('confirmImport').addEventListener('click', function() {
       const selectedRows = [];
       const rows = document.querySelectorAll('#csvDataContainer input[type="checkbox"]');

       rows.forEach((row, index) => {
           if (row.checked) { // Only push indices of checked rows
               selectedRows.push(index);
           }
       });

       if (selectedRows.length === 0) {
           alert("No data selected. Nothing will be stored in the database.");
           document.getElementById('csvReviewModal').style.display = 'none';
           return; // Prevent form submission if nothing is selected
       }

       // Set selected rows in hidden input
       document.getElementById('selectedRows').value = JSON.stringify(selectedRows);

       document.getElementById('csvReviewModal').style.display = 'none';
       document.getElementById('importForm').submit();
   });

    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('csvReviewModal').style.display = 'none';
    });
    document.getElementById('import-button').addEventListener('click', function (e) {
        e.preventDefault();

        const selectedRows = [];
        document.querySelectorAll('.checkbox-row').forEach(checkbox => {
            if (checkbox.checked) {
                selectedRows.push(checkbox.value);  // Assuming the value is the uni_id from CSV
            }
        });

        const formData = new FormData(document.getElementById('import-form'));
        formData.append('selectedRows', JSON.stringify(selectedRows));

        fetch('/import', {  // Update this URL to match your route
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => response.json())
          .then(data => console.log(data))
          .catch(error => console.error('Error:', error));
    });

</script>


@endsection