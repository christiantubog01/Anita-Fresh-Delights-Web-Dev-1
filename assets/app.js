// import './stimulus_bootstrap.js';
// /*
//  * Welcome to your app's main JavaScript file!
//  *
//  * We recommend including the built version of this JavaScript file
//  * (and its CSS file) in your base layout (base.html.twig).
//  */

// // any CSS you import will output into a single css file (app.css in this case)
// import './styles/app.css';
// import './styles/dashboard.css';

// // This is for Side Bar Toggle
// document.addEventListener('DOMContentLoaded', () => {
//     const btn = document.getElementById("toggleSidebar");
//     const sidebar = document.getElementById("sidebar");

//     if (btn && sidebar) {
//         btn.addEventListener("click", () => {
//             sidebar.classList.toggle("collapsed");
//         });
//     }
// });





// Import Bootstrap JS & CSS
import 'bootstrap';
import './styles/app.css';
import './styles/dashboard.css';


// Sidebar toggle with state persistence
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");

    if (btn && sidebar) {
        // Load previous state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }

        // Toggle sidebar & save state
        btn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        });
    }
});
