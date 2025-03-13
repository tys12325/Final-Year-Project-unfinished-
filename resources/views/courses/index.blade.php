
@extends('layouts.adminLayout')
@section('title', 'Programmes')

@section('content')


<style>

    .container {
        max-width: 100%;
        overflow-x: auto; /* Enables horizontal scrolling */
        padding-left: 10px;
        padding-right: 10px;
    }

    .table-responsive {
        overflow-x: auto;
        white-space: nowrap; /* Ensures text does not break unexpectedly */
    }

    .table {
        width: 100%;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center;
        padding: 10px;
        white-space: normal; /* Allows text to wrap properly */
        word-break: break-word; /* Breaks long words properly */
        min-width: 150px; /* Ensures enough space for content */
    }

    .table td input {
        width: 100%;
        min-width: 150px; /* Ensures input fields are responsive */
        max-width: 300px; /* Limits width to keep readability */
    }

    @media screen and (max-width: 768px) {
        .table th, .table td {
            font-size: 14px; /* Adjusts font size for small screens */
            padding: 8px;
        }

        .table td input {
            font-size: 14px;
        }
    }
    #floatingDeleteMessage {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: none;
        min-width: 300px;
    }
    #deleteMessageContainer .alert {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .hidden-column {
        display: none !important; /* Ensure it overrides other styles */
    }

    .column-toggle-container {
        margin-bottom: 20px;
    }

    .column-toggle-container label {
        margin-right: 15px;
        font-weight: normal;
    }
.expandable-field {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    max-width: 200px;
    transition: all 0.3s ease;
}

.expandable-field.expanded {
    white-space: normal;
    overflow: visible;
    max-width: 100%;
    background: white;
    border: 1px solid #ccc;
    z-index: 1;
    position: relative;
}

.expandable-field.d-none {
    display: none;
}
</style>

<div class="container">
    <h2>Programme</h2>
    
    <!-- Dropdown for selecting number of items per page -->
    <form method="GET" action="{{ route('courses.index') }}" class="mb-4 flex space-x-2">
        <label for="per_page" class="self-center font-medium">Show:</label>
        <select name="per_page" id="per_page" class="border rounded-md px-3 py-1" onchange="this.form.submit()">
            @foreach ([10, 20, 50, 100] as $size)
                <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                    {{ $size }} per page
                </option>
            @endforeach
        </select>
    </form>

    @if (isset($university))
        <h4>Programmes offered by {{ $university->uniName }}</h4>
    @endif
        <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('delete_success'))
        <div class="alert alert-danger">
            {{ session('delete_success') }}
        </div>
    @endif
    <div id="deleteMessageContainer"></div>

    <div class="table-responsive">
        <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">Add New Programme</a>
        <a href="{{ url('course-logs') }}" class="btn btn-danger mb-3">
             View Updated Logs
        </a>

        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Programme Name..." >
        </div>
        <!-- Column Toggle Checkboxes and "Check All" Button -->
        <div class="column-toggle-container mb-3">
            <button id="check-all-button" class="btn btn-secondary mb-2">Check All</button>
            <label><input type="checkbox" name="column-toggle" value="id" checked> ID</label>
            <label><input type="checkbox" name="column-toggle" value="courseName" checked> Programme Name</label>
            <label><input type="checkbox" name="column-toggle" value="duration" checked> Duration</label>
            <label><input type="checkbox" name="column-toggle" value="feesLocal" checked> Fees (Local)</label>
            <label><input type="checkbox" name="column-toggle" value="feesInternational" checked> Fees (International)</label>
            <label><input type="checkbox" name="column-toggle" value="studyType" checked> Study Type</label>
            <label><input type="checkbox" name="column-toggle" value="studyLevel" checked> Study Level</label>
            <label><input type="checkbox" name="column-toggle" value="university" checked> University</label>
            <label><input type="checkbox" name="column-toggle" value="created_at" checked> Created At</label>
            <label><input type="checkbox" name="column-toggle" value="updated_at" checked> Last Updated</label>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th data-column="id">ID</th>
                    <th data-column="courseName">Programme Name</th>
                    <th data-column="duration">Duration</th>
                    <th data-column="feesLocal">Fees (Local)</th>
                    <th data-column="feesInternational">Fees (International)</th>
                    <th data-column="studyType">Study Type</th>
                    <th data-column="studyLevel">Study Level</th>
                    <th data-column="university">University</th>
                    <th data-column="created_at">Created at</th>
                    <th data-column="updated_at">Last Updated</th>
                    <th data-column="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
        @foreach ($courses as $course)
            <tr id="row-{{ $course->courseID }}">
                <td data-column="id">{{ $course->courseID }}</td>
                <td data-column="courseName">
                    <div class="expandable-field" id="name-{{ $course->courseID }}">{{ $course->courseName }}</div>
                    <textarea class="form-control text-center expandable-field d-none" id="edit-name-{{ $course->courseID }}">{{ $course->courseName }}</textarea>
                </td>
                <td data-column="duration"><input type="text" class="form-control text-center" value="{{ $course->duration }}" id="duration-{{ $course->courseID }}" disabled></td>
                <td data-column="feesLocal"><input type="text" class="form-control text-center" value="{{ $course->feesLocal }}" id="feesLocal-{{ $course->courseID }}" disabled></td>
                <td data-column="feesInternational"><input type="text" class="form-control text-center" value="{{ $course->feesInternational }}" id="feesInternational-{{ $course->courseID }}" disabled></td>
                <td data-column="studyType"><input type="text" class="form-control text-center" value="{{ $course->studyType }}" id="studyType-{{ $course->courseID }}" disabled></td>
                <td data-column="studyLevel"><input type="text" class="form-control text-center" value="{{ $course->studyLevel }}" id="studyLevel-{{ $course->courseID }}" disabled></td>
                <td data-column="university">{{ $course->university->uniName }}</td>
                <td data-column="created_at">{{ $course->created_at }}</td>
                <td data-column="updated_at">{{ $course->updated_at }}</td>
                <td data-column="actions">
                    <button class="btn btn-primary btn-sm" onclick="editRow('{{ $course->courseID }}')">Edit</button>
                    <button class="btn btn-success btn-sm d-none" id="save-{{ $course->courseID }}" onclick="saveRow('{{ $course->courseID }}')">Save</button>
                    <button class="btn btn-danger" onclick="confirmDeleteCourse({{ $course->courseID }})">Delete</button>
                </td>
            </tr>
        @endforeach
            </tbody>
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this course? Type <strong>yes</strong> to confirm.</p>
                            <input type="text" id="deleteConfirmationInput" class="form-control" placeholder="Type 'yes' to confirm">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteButton" disabled>Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </table>
        <!-- Pagination Links -->
       {{ $courses->appends(['per_page' => $perPage])->links() }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById("searchInput").addEventListener("input", function () {
        const searchText = this.value.trim(); // Get the search text

        // Send an AJAX request to the server
        fetch(`/courses/search?query=${encodeURIComponent(searchText)}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest", // Indicate this is an AJAX request
            },
        })
        .then(response => response.json())
        .then(data => {
            // Clear the existing table rows
            const tbody = document.querySelector("tbody");
            tbody.innerHTML = "";

            // Add the new rows from the search results
            data.forEach(course => {
                const row = document.createElement("tr");
                row.id = `row-${course.courseID}`; // Add row ID
                row.innerHTML = `
                    <td data-column="id">${course.courseID}</td>
                    <td data-column="courseName">
                        <div class="expandable-field" id="name-${course.courseID}">${course.courseName}</div>
                        <textarea class="form-control text-center expandable-field d-none" id="edit-name-${course.courseID}">${course.courseName}</textarea>
                    </td>
                    <td data-column="duration"><input type="text" class="form-control text-center" value="${course.duration}" id="duration-${course.courseID}" disabled></td>
                    <td data-column="feesLocal"><input type="text" class="form-control text-center" value="${course.feesLocal}" id="feesLocal-${course.courseID}" disabled></td>
                    <td data-column="feesInternational"><input type="text" class="form-control text-center" value="${course.feesInternational}" id="feesInternational-${course.courseID}" disabled></td>
                    <td data-column="studyType"><input type="text" class="form-control text-center" value="${course.studyType}" id="studyType-${course.courseID}" disabled></td>
                    <td data-column="studyLevel"><input type="text" class="form-control text-center" value="${course.studyLevel}" id="studyLevel-${course.courseID}" disabled></td>
                    <td data-column="university">${course.university.uniName}</td>
                    <td data-column="created_at">${course.created_at}</td>
                    <td data-column="updated_at">${course.updated_at}</td>
                    <td data-column="actions">
                        <button class="btn btn-primary btn-sm" onclick="editRow('${course.courseID}')">Edit</button>
                        <button class="btn btn-success btn-sm d-none" id="save-${course.courseID}" onclick="saveRow('${course.courseID}')">Save</button>
                        <button class="btn btn-danger" onclick="confirmDeleteCourse(${course.courseID})">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            // Reapply the column toggle functionality for the new rows
            document.querySelectorAll('.column-toggle-container input[type="checkbox"]').forEach(checkbox => {
                checkbox.dispatchEvent(new Event('change'));
            });
            reinitializeExpandableFields();
        })
        .catch(error => console.error("Error fetching search results:", error));
    });

    let deleteCourseID = null;

    // Function to update the "Check All" button text
    function updateCheckAllButton() {
        const checkboxes = document.querySelectorAll('.column-toggle-container input[type="checkbox"]');
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

        const checkAllButton = document.getElementById('check-all-button');
        checkAllButton.textContent = allChecked ? 'Uncheck All' : 'Check All';
    }

    // Check All Button Functionality
    document.getElementById('check-all-button').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.column-toggle-container input[type="checkbox"]');
        let allChecked = true;

        // Check if all checkboxes are already checked
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });

        // Toggle all checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked; // If all are checked, uncheck them; otherwise, check them
            checkbox.dispatchEvent(new Event('change')); // Trigger the change event to update column visibility
        });

        // Update button text
        updateCheckAllButton();
    });

    // Column Toggle Functionality
    document.querySelectorAll('.column-toggle-container input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const column = this.value;
            const isVisible = this.checked;

            document.querySelectorAll(`[data-column="${column}"]`).forEach(cell => {
                if (isVisible) {
                    cell.classList.remove('hidden-column');
                } else {
                    cell.classList.add('hidden-column');
                }
            });

            // Update the "Check All" button text
            updateCheckAllButton();
        });
    });

    // Set default checked/unchecked state
    const defaultCheckedColumns = ['id', 'courseName', 'duration', 'feesLocal', 'feesInternational', 'studyType', 'studyLevel', 'university', 'updated_at', 'actions'];
    document.querySelectorAll('.column-toggle-container input[type="checkbox"]').forEach(checkbox => {
        if (defaultCheckedColumns.includes(checkbox.value)) {
            checkbox.checked = true; // Check the checkbox
        } else {
            checkbox.checked = false; // Uncheck the checkbox
        }
        checkbox.dispatchEvent(new Event('change')); // Trigger the change event to update visibility
        
    });

    // Update the "Check All" button text initially
    updateCheckAllButton();



   
    function confirmDeleteCourse(courseID) {
        deleteCourseID = courseID;
        document.getElementById("deleteConfirmationInput").value = ""; 
        document.getElementById("confirmDeleteButton").disabled = true;
        new bootstrap.Modal(document.getElementById("deleteConfirmationModal")).show();

        // Enable delete button only when "yes" is typed
        document.getElementById("deleteConfirmationInput").addEventListener("input", function () {
            document.getElementById("confirmDeleteButton").disabled = this.value.toLowerCase() !== "yes";
        });
        
        document.getElementById("confirmDeleteButton").addEventListener("click", function () {
            if (deleteCourseID) {
                fetch(`/courses/${deleteCourseID}`, {
                    method: "DELETE",
                    headers: { 
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // ✅ Show success message in red
                        showDeleteMessage("Course deleted successfully, Refresh to see changes.", "danger");
                        // Close the modal
                        let modal = bootstrap.Modal.getInstance(document.getElementById("deleteConfirmationModal"));
                        modal.hide();

                    } else {
                        showDeleteMessage(data.message || "Error deleting course.", "danger");
                    }
                })
                .catch(() => showDeleteMessage("Error deleting course.", "danger"));
            }
        });
    }

    // Function to show delete message
    function showDeleteMessage(message, type) {
        let messageContainer = document.getElementById("deleteMessageContainer");

        // Create a new alert div
        let alertDiv = document.createElement("div");
        alertDiv.className = `alert alert-${type} position-fixed text-center shadow-lg`;
        alertDiv.style.zIndex = "1050"; // Ensure it appears above other elements
        alertDiv.style.minWidth = "400px";
        alertDiv.style.maxWidth = "600px";
        alertDiv.style.padding = "20px";
        alertDiv.style.left = "50%";
        alertDiv.style.top = "50%";
        alertDiv.style.transform = "translate(-50%, -50%)"; // Center horizontally & vertically
        alertDiv.style.borderRadius = "10px";
        alertDiv.innerHTML = `
            ${message} 
            <button type="button" class="btn-close ms-3" onclick="this.parentElement.remove()"></button>
        `;

        // Append to message container
        messageContainer.appendChild(alertDiv);

        // Automatically remove after 8 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }







    
function editRow(id) {
    const fields = ['name', 'duration', 'feesLocal', 'feesInternational', 'studyType', 'studyLevel'];
    fields.forEach(field => {
        const displayField = document.getElementById(`${field}-${id}`);
        const editField = document.getElementById(`edit-${field}-${id}`);

        if (displayField && editField) {
            // Hide the display field and show the edit field
            displayField.classList.add("d-none");
            editField.classList.remove("d-none");
            editField.disabled = false;

            // Expand the textarea fields during edit
            if (field === 'name') {
                editField.classList.add("expanded");
            }
        } else if (displayField) {
            // For non-textarea fields, just enable them
            displayField.disabled = false;
        }
    });

    // Show the save button
    document.getElementById(`save-${id}`).classList.remove("d-none");
}

function saveRow(id) {
    let data = {
        courseName: document.getElementById("edit-name-" + id).value, // Use textarea value
        duration: document.getElementById("duration-" + id).value,
        feesLocal: document.getElementById("feesLocal-" + id).value,
        feesInternational: document.getElementById("feesInternational-" + id).value,
        studyType: document.getElementById("studyType-" + id).value,
        studyLevel: document.getElementById("studyLevel-" + id).value
    };

    fetch(`/courses/${id}/update`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Updated successfully!");

            // Update the display fields with the new values
            document.getElementById("name-" + id).textContent = document.getElementById("edit-name-" + id).value;

            // Hide the textarea fields and show the display fields
            document.getElementById("edit-name-" + id).classList.add("d-none");
            document.getElementById("name-" + id).classList.remove("d-none");

            // Disable all fields
            let fields = ['name', 'duration', 'feesLocal', 'feesInternational', 'studyType', 'studyLevel'];
            fields.forEach(field => {
                const displayField = document.getElementById(`${field}-${id}`);
                const editField = document.getElementById(`edit-${field}-${id}`);

                if (displayField && editField) {
                    editField.disabled = true; // Disable the edit field
                } else if (displayField) {
                    displayField.disabled = true; // Disable the input field
                }
            });

            // Hide the save button
            document.getElementById("save-" + id).classList.add("d-none");
        } else {
            alert("Update failed: " + data.message);
        }
    })
    .catch(error => console.error("Fetch Error:", error));
}

let currentlyExpandedField = null; // Track the currently expanded field
let isEditMode = false; // Track whether the application is in edit mode

// Function to attach event listeners to expandable fields
function attachExpandableFieldListeners() {
    const expandableFields = document.querySelectorAll(".expandable-field");
    expandableFields.forEach(field => {
        field.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent the click from bubbling up

            // Skip expansion/collapse logic if in edit mode
            if (isEditMode) {
                return;
            }

            // Collapse the previously expanded field (if any)
            if (currentlyExpandedField && currentlyExpandedField !== this) {
                currentlyExpandedField.classList.remove("expanded");
            }

            // Toggle the expanded state of the clicked field
            this.classList.toggle("expanded");

            // Update the currently expanded field
            if (this.classList.contains("expanded")) {
                currentlyExpandedField = this;
            } else {
                currentlyExpandedField = null;
            }
        });
    });
}

// Collapse the expanded field when clicking outside
document.addEventListener("click", function (event) {
    if (isEditMode) {
        return; // Skip collapse logic if in edit mode
    }

    if (currentlyExpandedField && !currentlyExpandedField.contains(event.target)) {
        currentlyExpandedField.classList.remove("expanded");
        currentlyExpandedField = null;
    }
});

// Function to enable edit mode
window.enableEditMode = function () {
    isEditMode = true; // Set edit mode to true
};

// Function to disable edit mode
window.disableEditMode = function () {
    isEditMode = false; // Set edit mode to false
};

// Initial attachment of event listeners on page load
document.addEventListener("DOMContentLoaded", function () {
    attachExpandableFieldListeners();
});

// Re-attach event listeners after dynamic updates (e.g., search)
window.reinitializeExpandableFields = function () {
    attachExpandableFieldListeners();
};
</script>

@endsection
