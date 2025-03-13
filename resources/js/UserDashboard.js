/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll("nav ul li a");
    const navHighlight = document.querySelector(".nav-highlight");
    let activeLink = document.querySelector("nav ul li a.active");

    function moveHighlight(element) {
        if (!element)
            return;
        const rect = element.getBoundingClientRect();
        const navRect = document.querySelector("nav ul").getBoundingClientRect();
        navHighlight.style.top = `${rect.top - navRect.top}px`;
    }


    moveHighlight(activeLink);


    navLinks.forEach(link => {
        link.addEventListener("mouseover", function () {
            moveHighlight(this);

        });

        link.addEventListener("mouseleave", function () {
            moveHighlight(activeLink);
        });
    });
});