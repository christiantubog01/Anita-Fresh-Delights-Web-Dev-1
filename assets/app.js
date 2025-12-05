import 'bootstrap';
import './styles/app.css';
import './styles/dashboard.css';
import DataTable from 'datatables.net-dt';

// Reusable function
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    if (!sidebar) return;

    sidebar.classList.toggle("collapsed");
    localStorage.setItem(
        "sidebar-collapsed",
        sidebar.classList.contains("collapsed")
    );
}

// Load saved sidebar state
function loadSidebarState() {
    const sidebar = document.getElementById("sidebar");
    if (!sidebar) return;

    if (localStorage.getItem("sidebar-collapsed") === "true") {
        sidebar.classList.add("collapsed");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("toggleSidebar");

    // Load state on page load
    loadSidebarState();

    // Attach function to the button
    if (btn) {
        btn.addEventListener("click", toggleSidebar);
    }

    // Initialize DataTables
    const tableElement = document.querySelector('#myTable');
    if (tableElement) {
        new DataTable('#myTable');
    }
});
