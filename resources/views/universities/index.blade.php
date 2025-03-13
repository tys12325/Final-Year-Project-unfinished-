@extends('layouts.adminLayout')
@section('title', 'Universities')

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

    .delete-message {
        position: fixed;
        top: 50%;
        background-color: white;
        padding: 20px;
        border: 1px solid black;
        z-index: 1000;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, -55%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
    /* Add this for hidden columns */
    .hidden-column {
        display: none !important;
    }

    /* Style for the checkbox container */
    .column-toggle-container {
        margin-bottom: 20px;
    }

    .column-toggle-container label {
        margin-right: 15px;
        font-weight: normal;
    }
    #check-all-button {
        margin-bottom: 10px;
        padding: 5px 10px;
        font-size: 14px;
        cursor: pointer;
    }

.expandable-field {
    white-space: nowrap; /* Prevent text from wrapping by default */
    overflow: hidden; /* Hide overflow text */
    text-overflow: ellipsis; /* Show ellipsis for overflow text */
    cursor: pointer; /* Change cursor to indicate clickable */
    max-width: 200px; /* Set a default max width */
    transition: all 0.3s ease; /* Smooth transition for expansion */
}

.expandable-field.expanded {
    white-space: normal; /* Allow text to wrap when expanded */
    overflow: visible; /* Show full content */
    max-width: 100%; /* Expand to fit content */
    background: white; /* Add background to make it readable */
    border: 1px solid #ccc; /* Add border for better visibility */
    z-index: 1; /* Ensure it appears above other elements */
    position: relative; /* Keep it within the table cell */
}

/* Hide textarea in non-edit mode */
.expandable-field.d-none {
    display: none;
}
</style>

<div class="container">
    <h2>Universities</h2>
    @if(session('success'))
        <div style="color: green; background-color: #d4edda; padding: 10px; border-radius: 5px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    <div id="deleteMessageContainer"></div>

    <a href="{{ route('universities.create') }}" class="btn btn-success mb-3">Add New University</a>
    <a href="{{ url('uni-logs') }}" class="btn btn-danger mb-3">
        View Updated Logs
    </a>
    <div class="mb-3">
       <input type="text" id="searchInput" class="form-control" placeholder="Search by University Name...">
    </div>
 <!-- Column Toggle Checkboxes -->
    <div class="column-toggle-container mb-3" >
        <button id="check-all-button" class="btn btn-secondary mb-2">Check All</button>
        <label><input type="checkbox" name="column-toggle" value="id" checked> ID</label>
        <label><input type="checkbox" name="column-toggle" value="image" checked> Image</label>
        <label><input type="checkbox" name="column-toggle" value="uniName" checked> University Name</label>
        <label><input type="checkbox" name="column-toggle" value="Address" > Address</label>
        <label><input type="checkbox" name="column-toggle" value="ContactNumber" > Contact</label>
        <label><input type="checkbox" name="column-toggle" value="OperationHour" > Operation Hour</label>
        <label><input type="checkbox" name="column-toggle" value="DateOfOpenSchool" checked> Date Of Open School</label>
        <label><input type="checkbox" name="column-toggle" value="Category" > Category</label>
        <label><input type="checkbox" name="column-toggle" value="Description" > Description</label>
        <label><input type="checkbox" name="column-toggle" value="Founder" > Founder</label>
        <label><input type="checkbox" name="column-toggle" value="EstablishDate" > Establish Date</label>
        <label><input type="checkbox" name="column-toggle" value="Ranking" checked> Ranking</label>
        <label><input type="checkbox" name="column-toggle" value="NumOfCourses" checked> Num Of Programmes</label>
        <label><input type="checkbox" name="column-toggle" value="created_at" checked> Created At</label>
        <label><input type="checkbox" name="column-toggle" value="updated_at" checked> Last Updated</label>
        
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th data-column="id">ID</th>
                    <th data-column="image">Image</th>
                    <th data-column="uniName">University Name</th>
                    <th data-column="Address">Address</th>
                    <th data-column="ContactNumber">Contact</th>
                    <th data-column="OperationHour">Operation Hour</th>
                    <th data-column="DateOfOpenSchool">Date Of Open School</th>
                    <th data-column="Category">Category</th>
                    <th data-column="Description">Description</th>
                    <th data-column="Founder">Founder</th>
                    <th data-column="EstablishDate">Establish Date</th>
                    <th data-column="Ranking">Ranking</th>
                    <th data-column="NumOfCourses">Offered Programmes</th>
                    <th data-column="created_at">Created at</th>
                    <th data-column="updated_at">Last Updated</th>
                    <th data-column="Actions">Actions</th>
                    <th data-column="ViewCourses">View Programmes</th>
                </tr>
            </thead>
            
            <tbody id="university-table-body">
            @foreach ($universities as $university)
            
               <tr id="row-{{ $university->uniID }}">
                    <td data-column="id">{{ $university->uniID }}</td>
                    <td data-column="image"><input type="text" class="form-control text-center" value="{{ $university->image }}" id="image-{{ $university->uniID }}" disabled></td>
                    <td data-column="uniName">
                        <div class="expandable-field" id="name-{{ $university->uniID }}">{{ $university->uniName }}</div>
                        <textarea class="form-control text-center expandable-field d-none" id="edit-name-{{ $university->uniID }}">{{ $university->uniName }}</textarea>
                    </td>
                    <td data-column="Address">
                        <div class="expandable-field" id="Address-{{ $university->uniID }}">{{ $university->Address }}</div>
                        <textarea class="form-control text-center expandable-field d-none" id="edit-Address-{{ $university->uniID }}">{{ $university->Address }}</textarea>
                    </td>


                    <td data-column="ContactNumber"><input type="text" class="form-control text-center" value="{{ $university->ContactNumber }}" id="ContactNumber-{{ $university->uniID }}" disabled></td>
                    <td data-column="OperationHour"><input type="text" class="form-control text-center" value="{{ $university->OperationHour }}" id="OperationHour-{{ $university->uniID }}" disabled></td>
                    <td data-column="DateOfOpenSchool"><input type="text" class="form-control text-center" value="{{ $university->DateOfOpenSchool }}" id="DateOfOpenSchool-{{ $university->uniID }}" disabled></td>
                    <td data-column="Category"><input type="text" class="form-control text-center" value="{{ $university->Category }}" id="Category-{{ $university->uniID }}" disabled></td>
                    
                    <td data-column="Description">
                        <div class="expandable-field" id="Description-{{ $university->uniID }}">{{ $university->Description }}</div>
                        <textarea class="form-control text-center expandable-field d-none" id="edit-Description-{{ $university->uniID }}">{{ $university->Description }}</textarea>
                    </td>
                    <td data-column="Founder"><input type="text" class="form-control text-center" value="{{ $university->Founder }}" id="Founder-{{ $university->uniID }}" disabled></td>
                    <td data-column="EstablishDate"><input type="text" class="form-control text-center" value="{{ $university->EstablishDate }}" id="EstablishDate-{{ $university->uniID }}" disabled></td>
                    
                    <td data-column="Ranking"><input type="number" class="form-control text-center" value="{{ $university->Ranking }}" id="Ranking-{{ $university->uniID }}" disabled></td>
                    <td data-column="NumOfCourses">{{ $university->NumOfCourses }}</td>
                    <td data-column="created_at">{{ $university->created_at }}</td>
                    <td data-column="updated_at">{{ $university->updated_at }}</td>
                    <td data-column="Actions">
                        <button class="btn btn-primary btn-sm" onclick="editRow('{{ $university->uniID }}')">Edit</button>
                        <button class="btn btn-success btn-sm d-none" id="save-{{ $university->uniID }}" onclick="saveRow('{{ $university->uniID }}')">Save</button>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $university->uniID }})">Delete</button>
                    </td>
                    <td data-column="ViewCourses">
                        <a href="{{ route('courses.byUniversity', ['uniID' => $university->uniID]) }}" class="ms-2">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this record? Type <strong>yes</strong> to confirm.</p>
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
        <!-- Error Messages Container -->
        <div id="errorContainer" class="mt-3"></div>
    </div>
</div>

<script>
    function hideErrors() {
        const errorAlerts = document.querySelectorAll('.alert-danger');
        errorAlerts.forEach(alert => {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 8000); // 5000 milliseconds = 5 seconds
        });
    }

    // Call the function when the page loads
    document.addEventListener('DOMContentLoaded', hideErrors);

    document.getElementById("searchInput").addEventListener("input", function () {
        const searchText = this.value.trim(); // Get the search text

        // Send an AJAX request to the server
        fetch(`/universities/search?query=${encodeURIComponent(searchText)}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest", // Indicate this is an AJAX request
            },
        })
        .then(response => response.json())
        .then(data => {
            // Clear the existing table rows
            const tbody = document.getElementById("university-table-body");
            tbody.innerHTML = "";

            // Add the new rows from the search results
            data.forEach(university => {
                const row = document.createElement("tr");
                row.id = `row-${university.uniID}`; // Add row ID
                row.innerHTML = `
                    <td data-column="id">${university.uniID}</td>
                    <td data-column="image"><input type="text" class="form-control text-center" value="${university.image}" id="image-${university.uniID}" disabled></td>
                    <td data-column="uniName">
                        <div class="expandable-field" id="name-${university.uniID}">${university.uniName}</div>
                        <textarea class="form-control text-center expandable-field d-none" id="edit-name-${university.uniID}">${university.uniName}</textarea>
                    </td>
                    <td data-column="Address">
                        <div class="expandable-field" id="Address-${university.uniID}">${university.Address}</div>
                        <textarea class="form-control text-center expandable-field d-none" id="edit-Address-${university.uniID}">${university.Address}</textarea>
                    </td>
                    <td data-column="ContactNumber"><input type="text" class="form-control text-center" value="${university.ContactNumber}" id="ContactNumber-${university.uniID}" disabled></td>
                    <td data-column="OperationHour"><input type="text" class="form-control text-center" value="${university.OperationHour}" id="OperationHour-${university.uniID}" disabled></td>
                    <td data-column="DateOfOpenSchool"><input type="text" class="form-control text-center" value="${university.DateOfOpenSchool}" id="DateOfOpenSchool-${university.uniID}" disabled></td>
                    <td data-column="Category"><input type="text" class="form-control text-center" value="${university.Category}" id="Category-${university.uniID}" disabled></td>
                    <td data-column="Description">
                        <div class="expandable-field" id="Description-${university.uniID}">${university.Description}</div>
                        <textarea class="form-control text-center expandable-field d-none" id="edit-Description-${university.uniID}">${university.Description}</textarea>
                    </td>
                    <td data-column="Founder"><input type="text" class="form-control text-center" value="${university.Founder}" id="Founder-${university.uniID}" disabled></td>
                    <td data-column="EstablishDate"><input type="text" class="form-control text-center" value="${university.EstablishDate}" id="EstablishDate-${university.uniID}" disabled></td>
                    <td data-column="Ranking"><input type="number" class="form-control text-center" value="${university.Ranking}" id="Ranking-${university.uniID}" disabled></td>
                    <td data-column="NumOfCourses">${university.NumOfCourses}</td>
                    <td data-column="created_at">${university.created_at}</td>
                    <td data-column="updated_at">${university.updated_at}</td>
                    <td data-column="Actions">
                        <button class="btn btn-primary btn-sm" onclick="editRow('${university.uniID}')">Edit</button>
                        <button class="btn btn-success btn-sm d-none" id="save-${university.uniID}" onclick="saveRow('${university.uniID}')">Save</button>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(${university.uniID})">Delete</button>
                    </td>
                    <td data-column="ViewCourses">
                        <a href="/courses/byUniversity/${university.uniID}" class="ms-2">View</a>
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
    let deleteUniID = null;
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
    // Manipulate default checked/unchecked state
    const defaultCheckedColumns = ['id', 'image','uniName','DateOfOpenSchool','Category','Ranking','NumOfCourses','updated_at']; // Columns to check by default
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
    function confirmDelete(uniID) {
            deleteUniID = uniID; 
            document.getElementById("deleteConfirmationInput").value = ""; 
            document.getElementById("confirmDeleteButton").disabled = true;
            new bootstrap.Modal(document.getElementById("deleteConfirmationModal")).show();
        }

        document.getElementById("deleteConfirmationInput").addEventListener("input", function () {
            document.getElementById("confirmDeleteButton").disabled = this.value.toLowerCase() !== "yes";
        });

        document.getElementById("confirmDeleteButton").addEventListener("click", function () {
            if (deleteUniID) {
                fetch(`/universities/${deleteUniID}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        showDeleteMessage("University deleted successfully, Refresh to see changes.", "danger");
                        //Close the modal
                        let modal = bootstrap.Modal.getInstance(document.getElementById("deleteConfirmationModal"));
                        modal.hide();
                    } else {
                        alert("Error deleting record.");
                    }
                });
            }
        });
 
    function showDeleteMessage(message, type) {
        let messageContainer = document.getElementById("deleteMessageContainer");

        // Ensure the message container exists
        if (!messageContainer) {
            messageContainer = document.createElement("div");
            messageContainer.id = "deleteMessageContainer";
            document.body.appendChild(messageContainer);
        }

        // Create a new alert div
        let alertDiv = document.createElement("div");
        alertDiv.className = `alert alert-${type} position-fixed text-center shadow-lg`;
        alertDiv.style.zIndex = "1050"; // Ensures it appears above other elements
        alertDiv.style.minWidth = "400px";
        alertDiv.style.maxWidth = "600px";
        alertDiv.style.padding = "20px";
        alertDiv.style.borderRadius = "10px";

        // ✅ Fix: Centering relative to **viewport**, not document
        alertDiv.style.position = "fixed";
        alertDiv.style.left = `${window.innerWidth / 2}px`;  // Centers based on visible screen
        alertDiv.style.top = `${window.innerHeight / 2}px`;  // Centers vertically in visible screen
        alertDiv.style.transform = "translate(-50%, -50%)"; // Adjust for element's own size

        alertDiv.innerHTML = `
            ${message} 
            <button type="button" class="btn-close ms-3" onclick="this.parentElement.remove()"></button>
        `;

        // Append to message container
        messageContainer.appendChild(alertDiv);

        // Automatically remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }



function editRow(id) {
    const fields = ['image', 'name', 'Address', 'ContactNumber', 'OperationHour', 'DateOfOpenSchool', 'Category', 'Description', 'Founder', 'EstablishDate', 'Ranking'];
    fields.forEach(field => {
        const displayField = document.getElementById(`${field}-${id}`);
        const editField = document.getElementById(`edit-${field}-${id}`);

        if (displayField && editField) {
            // Hide the display field and show the edit field
            displayField.classList.add("d-none");
            editField.classList.remove("d-none");
            editField.disabled = false;

            // Expand the textarea fields during edit
            if (field === 'name' || field === 'Address' || field === 'Description') {
                editField.classList.add("expanded");
            }
        } else if (displayField) {
            // For non-textarea fields, just enable them
            displayField.disabled = false;
        }
    });
    window.enableEditMode();
    // Show the save button
    document.getElementById(`save-${id}`).classList.remove("d-none");
}
function validatePhoneNumber(phoneNumber) {
    // Regex for Malaysian phone numbers
    const phoneRegex = /^(01[0-9]-?\d{7,8}|0[3-9]-?\d{7,8})$/;
    return phoneRegex.test(phoneNumber);
}
function saveRow(id) {
    const phoneNumber = document.getElementById("ContactNumber-" + id).value;
    // Validate the phone number
    if (!validatePhoneNumber(phoneNumber)) {
        showErrorInModal("The contact number must be in a valid Malaysian phone number format.");
        return; // Stop execution if the phone number is invalid
    }
    let data = {
        image: document.getElementById("image-" + id).value,
        uniName: document.getElementById("edit-name-" + id).value, // Use textarea value
        Address: document.getElementById("edit-Address-" + id).value, // Use textarea value
        ContactNumber: phoneNumber,
        OperationHour: document.getElementById("OperationHour-" + id).value,
        DateOfOpenSchool: document.getElementById("DateOfOpenSchool-" + id).value,
        Category: document.getElementById("Category-" + id).value,
        Description: document.getElementById("edit-Description-" + id).value, // Use textarea value
        Founder: document.getElementById("Founder-" + id).value,
        EstablishDate: document.getElementById("EstablishDate-" + id).value,
        Ranking: document.getElementById("Ranking-" + id).value,
    };

    fetch(`/universities/${id}/update`, {
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

            // Update the display fields with the new values from the textarea
            document.getElementById("name-" + id).textContent = document.getElementById("edit-name-" + id).value;
            document.getElementById("Address-" + id).textContent = document.getElementById("edit-Address-" + id).value;
            document.getElementById("Description-" + id).textContent = document.getElementById("edit-Description-" + id).value;

            // Hide the textarea fields and show the display fields
            document.getElementById("edit-name-" + id).classList.add("d-none");
            document.getElementById("edit-Address-" + id).classList.add("d-none");
            document.getElementById("edit-Description-" + id).classList.add("d-none");

            document.getElementById("name-" + id).classList.remove("d-none");
            document.getElementById("Address-" + id).classList.remove("d-none");
            document.getElementById("Description-" + id).classList.remove("d-none");

            // Disable all fields
            let fields = ['image', 'name', 'Address', 'ContactNumber', 'OperationHour', 'DateOfOpenSchool', 'Category', 'Description', 'Founder', 'EstablishDate', 'Ranking'];
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

function showErrorInModal(message) {
    const errorContainer = document.getElementById("errorContainer");

    // Create error message HTML
    const errorHtml = `
        <div class="alert alert-danger">
            <ul class="mb-0">
                <li>${message}</li>
            </ul>
        </div>
    `;

    // Update the error container
    errorContainer.innerHTML = errorHtml;

    // Auto-hide errors after 5 seconds
    setTimeout(() => {
        errorContainer.innerHTML = '';
    }, 5000);
}
</script>

@endsection
