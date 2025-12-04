import 'bootstrap';
import './styles/app.css';
import './styles/dashboard.css';
import DataTable from 'datatables.net-dt';



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

    // ✅ Initialize DataTables if table exists
    const tableElement = document.querySelector('#myTable');
    if (tableElement) {
        new DataTable('#myTable');
    }
});


