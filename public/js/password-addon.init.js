/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Password addon Js File
*/

document.addEventListener('DOMContentLoaded', function () {
    // Initialize acorn icons if nav is not present (e.g. login page)
    if (typeof AcornIcons !== 'undefined' && !document.getElementById('nav')) {
        new AcornIcons().replace();
    }

    // password addon
    Array.from(document.querySelectorAll(".password-addon")).forEach(function (subitem) {
        subitem.addEventListener("click", function (event) {
            var passwordInput = subitem.closest('.position-relative').querySelector(".password-input");
            var iconEyeOff = subitem.querySelector(".icon-eye-off");
            var iconEye = subitem.querySelector(".icon-eye");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                if (iconEyeOff) iconEyeOff.classList.add("d-none");
                if (iconEye) iconEye.classList.remove("d-none");
            } else {
                passwordInput.type = "password";
                if (iconEyeOff) iconEyeOff.classList.remove("d-none");
                if (iconEye) iconEye.classList.add("d-none");
            }
        });
    });
});