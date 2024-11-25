// JavaScript code

let list = document.querySelectorAll(".navigation li");
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");
let dashboardLink = document.querySelector("#dashboard a");  // "Dashboard" link
let instructorLink = document.querySelector("#instructor a"); 
let studentLink = document.querySelector("#student a"); 
let classLink = document.querySelector("#class a");  // "Class" link
let semesterLink = document.querySelector("#semester a");
let academicLink = document.querySelector("#academic a");
let sectionLink = document.querySelector("#section a");
let departmentLink = document.querySelector("#department a");
let questionLink = document.querySelector("#question a");
let rateLink = document.querySelector("#rate a");
let evaluationLink = document.querySelector("#evaluation a");

// Function to handle the "active" class for clicked items
function setActiveLink() {
    list.forEach((item) => {
        item.classList.remove("active");  // Remove "active" class from all items
    });
    this.parentElement.classList.add("active");  // Add "active" class to clicked item
}

// Add click event to all menu items
list.forEach((item) => {
    item.querySelector("a").addEventListener("click", setActiveLink);
});

// Toggle menu on click of the toggle button (hamburger menu)
toggle.onclick = function () {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
};

// Handle the click on "Dashboard" to minimize the sidebar
dashboardLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.toggle("active"); // Minimize the menu
    main.classList.toggle("active"); // Adjust the main content width
    setActiveLink.call(dashboardLink); // Make "Dashboard" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "Instructor" to minimize the sidebar and set it as active
instructorLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(this); // Make "Instructor" the active item
    window.location.href = this.href; // Navigate to the href destination
});


// Handle the click on "student" to minimize the sidebar and set it as active
studentLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(studentLink); // Make "student" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "Class" to minimize the sidebar and set it as active
classLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(classLink); // Make "Class" the active item
    window.location.href = this.href; // Navigate to the href destination
});
 
// Handle the click on "semester" to minimize the sidebar and set it as active
semesterLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(semesterLink); // Make "semester" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "academic" to minimize the sidebar and set it as active
academicLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(academicLink); // Make "academic" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "section" to minimize the sidebar and set it as active
sectionLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(sectionLink); // Make "section" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "department" to minimize the sidebar and set it as active
departmentLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(departmentLink); // Make "department" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "question" to minimize the sidebar and set it as active
questionLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(questionLink); // Make "question" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "rate" to minimize the sidebar and set it as active
rateLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(rateLink); // Make "rate" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Handle the click on "evaluation" to minimize the sidebar and set it as active
evaluationLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior
    navigation.classList.add("active"); // Minimize the menu
    main.classList.add("active"); // Adjust the main content width
    setActiveLink.call(evaluationLink); // Make "evaluation" the active item
    window.location.href = this.href; // Navigate to the href destination
});

// Remove the hover effect when menu is minimized
navigation.addEventListener("mouseover", function () {
    if (navigation.classList.contains("active")) {
        list.forEach((item) => {
            item.classList.remove("hovered"); // Remove hover effect when minimized
        });
    }
});

// Get the dropdown button and the dropdown content
const dropdownButton = document.querySelector('.dropdown-btn');
const dropdownContent = document.querySelector('.dropdown-content');

// Toggle dropdown visibility when the button is clicked
dropdownButton.addEventListener('click', function() {
  dropdownContent.classList.toggle('show');
});

// Close dropdown if clicked outside
window.addEventListener('click', function(event) {
    if (!event.target.matches('.dropdown-btn') && !event.target.matches('.dropdown-content') && !event.target.closest('.dropdown-btn')) {
      if (dropdownContent.classList.contains('show')) {
        dropdownContent.classList.remove('show');
      }
    }
  });
  
