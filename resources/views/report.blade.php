<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course and University Overview Report</title>
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<div class="body">
    <div class="header-section">
        <strong><h1><a href="{{  route('adminDashboard') }}">University Overview Report</a></h1></strong>

        <div class="header-buttons">
            <button class="download-btn" id="downloadPdf">Download Report</button>
            <button class="print-btn" id="printReport">Print Report</button>
        </div>
    </div>
    <div class="container">
        <div class="header-filters">
            <div class="header">
                <p><strong>Total Universities:</strong> {{ $totalUniversities }}</p>
                <p><strong>Total Courses:</strong> {{ $totalCourses }}</p>
                <p><strong>Last Updated:</strong> {{ $lastUpdated }}</p>
            </div>
            <div class="hide-options">
                <label><input type="checkbox" id="hideRanking" onchange="toggleRanking()"> Hide Ranking</label>
                <label><input type="checkbox" id="hideLocation" onchange="toggleLocation()"> Hide Location</label>
                <label><input type="checkbox" id="hideCategory" onchange="toggleCategory()"> Hide Category</label>
                <label><input type="checkbox" id="hideCourses" onchange="toggleCoursesCount()"> Hide Courses Count</label>
                <label style="font-weight: bold;"><input type="checkbox" id="hideButtons" onchange="toggleButtons()"> Hide Buttons & Course List</label>
            </div>
            <div class="filters">     

                <button id="filterButton">Filter by University</button> 
                <input type="text" placeholder="Search...">
            </div>

            <div id="uniModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Universities</h2>
                    <input type="text" id="modalSearch" placeholder="Search universities..." oninput="filterModalUniversities()">
                    <div id="uniList">
                        <!-- University checkboxes will be dynamically added here -->
                    </div>
                    <button id="saveButton">Save</button>
                </div>
            </div>
        </div>
        <!-- Universities Section -->    
        <div class="universities-section">
            <div class="university-info">
                <p>
                    <strong>
                        <span id="sortByName">University Name</span>
                        <span class="sort-arrow" id="nameArrow"></span>
                    </strong>
                </p>
                <span class="uni-rank">
                    <p>
                        <strong>
                            <span id="sortByRanking">Ranking</span>
                            <span class="sort-arrow" id="rankingArrow"></span>
                        </strong>
                    </p>
                </span>
                <span class="uni-city">
                    <p>
                       <strong>
                           <span id="sortByCity">Location</span>
                           <span class="sort-arrow" id="cityArrow"></span>
                       </strong>
                   </p>                   
                </span>

                <span class="uni-category">
                    <p><strong>Category</strong></p> 
                </span>
                
                
            </div>
            <div class ="line"></div>
            @foreach ($universities as $university)


            <div class="university">

                <div class="university-header">
                    <h2>
                        <span class="uni-name">{{ $university->uniName }}</span>
                        <span class="uni-rank">#{{ $university->Ranking }}</span>
                        <span class="uni-city">{{ $university->city }}</span>
                        <span class="uni-category">{{ $university->Category }}</span>
                    </h2>
    <!--                    <span class="meta">
                        <strong style="color:red;">Created At:</strong> {{ $university->created_at }} |
                        <strong style="color:red;">Last Updated:</strong> {{ $university->updated_at }}
                    </span>-->
                </div>     
                <div class="info-row">
                    <p class="meta">
                        <span class="courses-count">[  <strong>Courses:</strong> {{ $university->courses->count() }} ]</span>


                    </p>
                    @if ($university->courses->count() > 10)
                        <div class="course-controls">
                            <div class="hide-courses" id="hide-courses-{{ $university->uniID }}" 
                                onclick="hideCourses('{{ $university->uniID }}')">
                                Hide 
                            </div>
                            <div class="expand-all" onclick="expandAllCourses('{{ $university->uniID }}')">
                                Expand
                            </div>
                        </div>
                    @endif
                </div>
                <ul class="courses-list" id="courses-{{ $university->uniID }}">
                    @foreach ($university->courses as $index => $course)
                        <li class="{{ $index >= 3 ? 'hidden' : '' }}">
                            {{ $course->courseName }}
                        </li>
                    @endforeach
                    @if ($university->courses->count() > 10)
                        <div class="course-toggle-container">
                            <div class="show-more" onclick="loadMoreCourses('{{ $university->uniID }}')">
                                Show More
                            </div>
                        </div>
                    @endif
                </ul>
            </div>  
        @endforeach
        </div>

        <!-- Footer Section -->
        <div class="footer">
            Report generated on {{ $lastUpdated }}. All data is up-to-date as of the last refresh.
        </div>
    </div>
</div>

<script>
// Global variable to store selected universities
let selectedUniversities = [];

// Get DOM elements
const modal = document.getElementById("uniModal");
const filterButton = document.getElementById("filterButton");
const closeButton = document.querySelector(".close");
const saveButton = document.getElementById("saveButton");
const uniList = document.getElementById("uniList");
const universityElements = document.querySelectorAll(".university");

// Open modal
filterButton.addEventListener("click", () => {
    modal.style.display = "block";
    renderUniList();
});

// Close modal
closeButton.addEventListener("click", () => {
    modal.style.display = "none";
});

// Render university list with checkboxes
function renderUniList() {
    // Fetch universities from your database (replace with your actual data)
    const universities = Array.from(new Set(
        Array.from(document.querySelectorAll(".uni-name")).map(uni => uni.textContent.trim())
    ));

    // Render universities with checkboxes
    uniList.innerHTML = universities
        .map(
            (uni) => `
            <div>
                <input type="checkbox" id="${uni}" value="${uni}" 
                    ${selectedUniversities.includes(uni) ? "checked" : ""}>
                <label for="${uni}">${uni}</label>
            </div>
        `
        )
        .join("");
}

// Save selected universities and filter data
saveButton.addEventListener("click", () => {
    // Update the selectedUniversities list
    selectedUniversities = [];
    document.querySelectorAll("#uniList input:checked").forEach((checkbox) => {
        selectedUniversities.push(checkbox.value);
    });

    // Close the modal and filter the main list
    modal.style.display = "none";
    filterData(selectedUniversities);
});

// Function to filter displayed universities based on selected universities
function filterData(selectedUnis) {
    const universityElements = document.querySelectorAll(".university");
    universityElements.forEach((university) => {
        const uniName = university.querySelector(".uni-name").textContent.trim();
        if (selectedUnis.length === 0 || selectedUnis.includes(uniName)) {
            university.style.display = "block"; // Show university
        } else {
            university.style.display = "none"; // Hide university
        }
    });
}

// Function to filter universities in the modal based on search input
function filterModalUniversities() {
    const searchText = document.getElementById("modalSearch").value.toLowerCase();
    const uniCheckboxes = document.querySelectorAll("#uniList div");

    uniCheckboxes.forEach((uni) => {
        const uniName = uni.querySelector("label").textContent.toLowerCase();
        if (uniName.includes(searchText)) {
            uni.style.display = "block"; // Show university
        } else {
            uni.style.display = "none"; // Hide university
        }
    });
}

// Initial render (show all universities by default)
filterData([]);

// Add event listener for the modal search input
document.getElementById("modalSearch").addEventListener("input", filterModalUniversities);

    function loadMoreCourses(uniID) {
        const coursesList = document.getElementById(`courses-${uniID}`);
        const hiddenCourses = coursesList.querySelectorAll('.hidden');
        const coursesToShow = Math.min(10, hiddenCourses.length);

        // Show the next 10 hidden courses
        for (let i = 0; i < coursesToShow; i++) {
            hiddenCourses[i].classList.remove('hidden');
        }

        // Hide "Show More Courses" button if all courses are visible
        if (coursesList.querySelectorAll('.hidden').length === 0) {
            document.querySelector(`.show-more[onclick*="${uniID}"]`).style.display = 'none';
        }

        // ✅ Show the "Hide Courses" button
        document.getElementById(`hide-courses-${uniID}`).style.display = 'block';
    }

    function expandAllCourses(uniID) {
        const coursesList = document.getElementById(`courses-${uniID}`);
        const hiddenCourses = coursesList.querySelectorAll('.hidden');

        // Show all hidden courses
        hiddenCourses.forEach(course => {
            course.classList.remove('hidden');
        });

        // Hide "Show More Courses" and "Expand All" buttons
        document.querySelector(`.show-more[onclick*="${uniID}"]`).style.display = 'none';
        document.querySelector(`.expand-all[onclick*="${uniID}"]`).style.display = 'none';

        // ✅ Show the "Hide Courses" button
        document.getElementById(`hide-courses-${uniID}`).style.display = 'block';
    }

    function hideCourses(uniID) {
        const coursesList = document.getElementById(`courses-${uniID}`);
        const courses = coursesList.querySelectorAll('li:not(.hidden)');
        const hideButton = document.getElementById(`hide-courses-${uniID}`);
        const showMoreButton = document.querySelector(`.show-more[onclick*="${uniID}"]`);
        const expandButton = document.querySelector(`.expand-all[onclick*="${uniID}"]`);

        // If more than 3 courses are visible, hide until only 3 remain
        if (courses.length > 3) {
            for (let i = courses.length - 1; i >= 3; i--) {
                courses[i].classList.add('hidden');
            }
        } else {
            // If already at 3, hide all courses
            courses.forEach(course => course.classList.add('hidden'));
            hideButton.style.display = 'none'; // Hide the "Hide Courses" button when all are hidden
        }

        // ✅ Show "Show More" and "Expand All" buttons
        showMoreButton.style.display = 'block';
        expandButton.style.display = 'block';
    }
    
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector(".filters input"); // Select the search box
        const universityList = document.querySelectorAll(".university"); // Select all university elements

        searchInput.addEventListener("input", function () {
            const searchText = searchInput.value.toLowerCase(); // Get search text in lowercase

            universityList.forEach(university => {
                const uniName = university.querySelector("h2").textContent.toLowerCase(); // Get university name
                if (uniName.includes(searchText)) {
                    university.style.display = "block"; // Show university
                } else {
                    university.style.display = "none"; // Hide university
                }
            });
        });
    });
// Function to hide/show Location
function toggleLocation() {
    let isChecked = document.getElementById("hideLocation").checked;
    let locationElements = document.querySelectorAll(".uni-city");

    locationElements.forEach(el => {
        el.style.display = isChecked ? "none" : "inline";
    });
}

// Function to hide/show Category
function toggleCategory() {
    let isChecked = document.getElementById("hideCategory").checked;
    let categoryElements = document.querySelectorAll(".uni-category");

    categoryElements.forEach(el => {
        el.style.display = isChecked ? "none" : "inline";
    });
}
// Hide/Show Courses Count
function toggleCoursesCount() {
    let isChecked = document.getElementById("hideCourses").checked;
    let coursesCountElements = document.querySelectorAll(".courses-count");

    coursesCountElements.forEach(el => {
        el.style.display = isChecked ? "none" : "inline";
    });
}

// Hide/Show Ranking
function toggleRanking() {
    let isChecked = document.getElementById("hideRanking").checked;
    let rankingElements = document.querySelectorAll(".uni-rank");

    rankingElements.forEach(el => {
        el.style.display = isChecked ? "none" : "inline";
    });
}

// Function to toggle buttons and course list visibility
function toggleButtons() {
    let isChecked = document.getElementById("hideButtons").checked;
    let buttonElements = document.querySelectorAll(".course-controls, .courses-list");

    buttonElements.forEach(el => {
        if (el.classList.contains("course-controls")) {
            // Ensure buttons retain their original flex display
            el.style.display = isChecked ? "none" : "flex";
        } else {
            // Handle course list visibility
            el.style.display = isChecked ? "none" : "block";
        }
    });
}

// Function to generate PDF
document.getElementById("downloadPdf").addEventListener("click", () => {
    const confirmDownload = confirm("Click 'OK' to download the University Report as a PDF. 😊");
    
    if (!confirmDownload) {
        return; // Stop execution if user cancels
    }
    // Hide course-controls buttons before generating PDF
    const buttonsToHide = document.querySelectorAll(".course-controls, .hide-options");
    buttonsToHide.forEach(button => button.classList.add("hidden-for-export"));

    const element = document.querySelector(".body");
    const opt = {
        margin: 10,
        filename: "University_Report.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" }, // Set to landscape
    };

    // Generate PDF
    html2pdf()
        .from(element)
        .set(opt)
        .save()
        .then(() => {
            // Restore buttons after generating PDF
            buttonsToHide.forEach(button => button.classList.remove("hidden-for-export"));

        });
});

// Function to print the page
document.getElementById("printReport").addEventListener("click", () => {
    // Hide course-controls buttons before printing
    const buttonsToHide = document.querySelectorAll(".course-controls");
    buttonsToHide.forEach(button => button.classList.add("hidden-for-export"));

    // Trigger the browser's print dialog
    window.print();

    // Restore buttons after printing (if the user cancels the print dialog)
    setTimeout(() => {
        buttonsToHide.forEach(button => button.classList.remove("hidden-for-export"));
    }, 500); // Restore after a short delay
});

const sortByName = document.getElementById("sortByName");
const sortByCity = document.getElementById("sortByCity");
const sortByRanking = document.getElementById("sortByRanking");
const nameArrow = document.getElementById("nameArrow");
const cityArrow = document.getElementById("cityArrow");
const rankingArrow = document.getElementById("rankingArrow");

let originalOrder = Array.from(document.querySelectorAll(".university"));
let currentSort = { by: "name", order: "asc" }; // Default: sort by name in ascending order

// Function to sort universities by name
function sortUniversitiesByName(order) {
    const universities = Array.from(document.querySelectorAll(".university"));
    universities.sort((a, b) => {
        const nameA = a.querySelector(".uni-name").textContent.trim().toLowerCase();
        const nameB = b.querySelector(".uni-name").textContent.trim().toLowerCase();
        return order === "asc" ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
    });
    return universities;
}

// Function to sort universities by city
function sortUniversitiesByCity(order) {
    const universities = Array.from(document.querySelectorAll(".university"));
    universities.sort((a, b) => {
        const cityA = a.querySelector(".uni-city").textContent.trim().toLowerCase();
        const cityB = b.querySelector(".uni-city").textContent.trim().toLowerCase();
        return order === "asc" ? cityA.localeCompare(cityB) : cityB.localeCompare(cityA);
    });
    return universities;
}

// Function to sort universities by ranking
function sortUniversitiesByRanking(order) {
    const universities = Array.from(document.querySelectorAll(".university"));
    universities.sort((a, b) => {
        const rankingA = parseInt(a.querySelector(".ranking").textContent.match(/\d+/)[0]); // Extract ranking number
        const rankingB = parseInt(b.querySelector(".ranking").textContent.match(/\d+/)[0]); // Extract ranking number
        return order === "asc" ? rankingA - rankingB : rankingB - rankingA;
    });
    return universities;
}

// Function to update the university list
function updateUniversityList(universities) {
    const universitiesSection = document.querySelector(".universities-section");
    const header = document.querySelector(".university-info"); // Preserve the header
    const lines = document.querySelectorAll(".line"); // Preserve the lines

    // Clear the current list (except the header and lines)
    universitiesSection.innerHTML = "";
    universitiesSection.appendChild(header); // Re-add the header
    universitiesSection.appendChild(lines[0]); // Re-add the first line (after header)

    // Append the sorted universities and lines
    universities.forEach((uni, index) => {
        universitiesSection.appendChild(uni);
        if (lines[index + 1]) {
            universitiesSection.appendChild(lines[index + 1]); // Re-add the line after each university
        }
    });
}

// Function to reset sorting to the default (ascending by name)
function resetSorting() {
    const sortedUniversities = sortUniversitiesByName("asc");
    updateUniversityList(sortedUniversities);
    nameArrow.textContent = "▲"; // Set ascending arrow for name
    cityArrow.textContent = ""; // Clear city arrow
    rankingArrow.textContent = ""; // Clear ranking arrow
    currentSort = { by: "name", order: "asc" }; // Reset to default sorting
}

// Function to handle sorting by name
function sortByNameHandler() {
    if (currentSort.by === "name" && currentSort.order === "asc") {
        // Switch to descending order
        const sortedUniversities = sortUniversitiesByName("desc");
        updateUniversityList(sortedUniversities);
        nameArrow.textContent = "▼"; // Set descending arrow
        currentSort = { by: "name", order: "desc" };
    } else if (currentSort.by === "name" && currentSort.order === "desc") {
        // Reset to default order (ascending by name)
        resetSorting();
    } else {
        // Switch to ascending order
        const sortedUniversities = sortUniversitiesByName("asc");
        updateUniversityList(sortedUniversities);
        nameArrow.textContent = "▲"; // Set ascending arrow
        currentSort = { by: "name", order: "asc" };
    }
}

// Function to handle sorting by city
function sortByCityHandler() {
    if (currentSort.by === "city" && currentSort.order === "asc") {
        // Switch to descending order
        const sortedUniversities = sortUniversitiesByCity("desc");
        updateUniversityList(sortedUniversities);
        cityArrow.textContent = "▼"; // Set descending arrow
        currentSort = { by: "city", order: "desc" };
    } else if (currentSort.by === "city" && currentSort.order === "desc") {
        // Reset to default order (ascending by name)
        resetSorting();
    } else {
        // Switch to ascending order
        const sortedUniversities = sortUniversitiesByCity("asc");
        updateUniversityList(sortedUniversities);
        cityArrow.textContent = "▲"; // Set ascending arrow
        currentSort = { by: "city", order: "asc" };
    }
}

// Function to handle sorting by ranking
function sortByRankingHandler() {
    if (currentSort.by === "ranking" && currentSort.order === "asc") {
        // Switch to descending order
        const sortedUniversities = sortUniversitiesByRanking("desc");
        updateUniversityList(sortedUniversities);
        rankingArrow.textContent = "▼"; // Set descending arrow
        currentSort = { by: "ranking", order: "desc" };
    } else if (currentSort.by === "ranking" && currentSort.order === "desc") {
        // Reset to default order (ascending by name)
        resetSorting();
    } else {
        // Switch to ascending order
        const sortedUniversities = sortUniversitiesByRanking("asc");
        updateUniversityList(sortedUniversities);
        rankingArrow.textContent = "▲"; // Set ascending arrow
        currentSort = { by: "ranking", order: "asc" };
    }
}

// Initialize default sorting (ascending by name)
resetSorting();

// Attach event listeners
document.getElementById("sortByName").addEventListener("click", sortByNameHandler);
document.getElementById("sortByCity").addEventListener("click", sortByCityHandler);
document.getElementById("sortByRanking").addEventListener("click", sortByRankingHandler);
</script>
