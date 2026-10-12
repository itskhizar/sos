  </div> <!-- /.content -->
</div> <!-- /.main -->

<script>
// Sidebar collapse toggle with localStorage persistence
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) {
    sidebar.classList.toggle('collapsed');
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sos_sidebar_collapsed', isCollapsed ? '1' : '0');
  }
}

// Restore saved sidebar collapsed state on desktop
(function() {
  if (localStorage.getItem('sos_sidebar_collapsed') === '1' && window.innerWidth > 992) {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.add('collapsed');
  }
})();

// Mobile drawer toggle
function toggleMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebar-backdrop');
  if (sidebar && backdrop) {
    sidebar.classList.toggle('mobile-active');
    backdrop.classList.toggle('active');
  }
}

// Accordion sub-menu toggle
function toggleAccordion(element) {
  const sidebar = document.getElementById('sidebar');
  if (sidebar && sidebar.classList.contains('collapsed')) {
    // If sidebar is collapsed, expand it to show the full sub-menu
    sidebar.classList.remove('collapsed');
    localStorage.setItem('sos_sidebar_collapsed', '0');
  }
  element.classList.toggle('open');
  const sub = element.nextElementSibling;
  if (sub && sub.classList.contains('sub')) {
    sub.classList.toggle('open');
  }
}

// Session dropdown single-source-of-truth switcher
function toggleSessionDropdown(e) {
  e.stopPropagation();
  const menu = document.getElementById('session-dropdown-menu');
  if (menu) menu.classList.toggle('show');
}

function switchSession(sessionYear, e) {
  e.preventDefault();
  e.stopPropagation();
  const label = document.getElementById('selected-session-label');
  if (label) label.innerText = sessionYear;
  
  const pillText = document.querySelector('.session-pill span:not(.dot)');
  if (pillText) pillText.innerText = `Session ${sessionYear} active`;
  
  const menu = document.getElementById('session-dropdown-menu');
  if (menu) menu.classList.remove('show');
}

// Global click-away listener
document.addEventListener('click', () => {
  const menu = document.getElementById('session-dropdown-menu');
  if (menu) menu.classList.remove('show');
});

// Keyboard shortcuts: ⌘K or Ctrl+K for search, ⌘B or Ctrl+B for sidebar collapse
document.addEventListener('keydown', (e) => {
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault();
    const input = document.getElementById('dash-search-input');
    if (input) input.focus();
  }
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'b') {
    e.preventDefault();
    toggleSidebar();
  }
});
</script>
</body>
</html>
