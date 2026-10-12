<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * System Audit Trail — dashboard/audit-logs.php
 * Module 1: Comprehensive forensic audit logging (User mutations, fee adjustments, permissions)
 */
$dashTitle   = 'Audit Logs';
$currentDash = 'audit-logs';
include 'includes/header.php';

$filterModule = $_GET['module'] ?? '';
$filterSearch = $_GET['search'] ?? '';

$logs = $database->getAuditLogs(100, $filterModule);

// If table is newly created and empty, seed an initial system log
if (empty($logs)) {
  $database->logAudit($session->username ?: 'system', 'SYSTEM_INITIALIZE', 'System', 'Setup', 1, '', 'Initial forensic audit log subsystem active');
  $logs = $database->getAuditLogs(100, $filterModule);
}

$modules = ['Authentication', 'Academic Setup', 'Admissions', 'Entry Tests', 'Students', 'Enrollments', 'Fee Management', 'Bank Payments', 'Concessions', 'Fines', 'RBAC & Roles', 'Settings'];
?>

<!-- Header & Export Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">System Audit Trail &amp; Security Logs</h2>
    <p class="text-sm text-[#44474f] mt-1">Immutable forensic tracking of staff actions, fee modifications, status changes, and user logons.</p>
  </div>
  <div class="flex items-center gap-3">
    <button onclick="exportAuditTableToCSV('sos_audit_logs.csv')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-[#002452] text-[#002452] rounded-lg font-semibold text-sm hover:bg-[#002452]/5 transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">download</span>
      Export Log Archive (CSV)
    </button>
  </div>
</div>

<!-- Filters -->
<form method="get" action="audit-logs.php" class="bg-white rounded-xl border border-[#e3e2df] p-4 shadow-sm flex flex-col md:flex-row gap-3 mt-6">
  <div class="relative flex-1">
    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
    <input type="text" name="search" value="<?= htmlspecialchars($filterSearch) ?>" id="audit-search" onkeyup="filterAuditTable()" placeholder="Search by staff user, action keyword, IP or record ID..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
  </div>
  <select name="module" onchange="this.form.submit()" class="px-3 py-2 border border-[#e3e2df] rounded-lg text-xs bg-white text-[#44474f]">
    <option value="">All Modules</option>
    <?php foreach ($modules as $m): ?>
      <option value="<?= $m ?>" <?= $filterModule === $m ? 'selected' : '' ?>><?= $m ?></option>
    <?php endforeach; ?>
  </select>
  <?php if ($filterModule || $filterSearch): ?>
    <a href="audit-logs.php" class="px-3 py-2 bg-gray-100 text-xs font-semibold rounded-lg text-[#44474f] hover:bg-gray-200 flex items-center">Reset</a>
  <?php endif; ?>
</form>

<!-- Audit Log Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mt-6">
  <div class="overflow-x-auto">
    <table class="w-full text-sm" id="audit-table">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Timestamp</th>
          <th class="px-4 py-3 text-left font-semibold">User / Operator</th>
          <th class="px-4 py-3 text-left font-semibold">Module</th>
          <th class="px-4 py-3 text-left font-semibold">Action</th>
          <th class="px-4 py-3 text-left font-semibold">Target Entity / Ref</th>
          <th class="px-4 py-3 text-left font-semibold">Change Description</th>
          <th class="px-4 py-3 text-left font-semibold">Client IP</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php foreach ($logs as $l): 
          $tStamp = date('d-M-Y H:i:s', strtotime($l['timestamp']));
          $uName = htmlspecialchars($l['user_id'] ?: 'system');
          $mod = htmlspecialchars($l['module'] ?: 'Core');
          $act = htmlspecialchars($l['action']);
          $ref = htmlspecialchars(($l['record_type'] ? $l['record_type'] . ' #' : '') . $l['record_id']);
          $desc = htmlspecialchars($l['new_values'] ?: ($l['old_values'] ?: 'Action recorded'));
          $ip = htmlspecialchars($l['ip_address'] ?: '127.0.0.1');
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-4 py-3 font-mono text-xs text-[#747780] whitespace-nowrap"><?= $tStamp ?></td>
          <td class="px-4 py-3">
            <span class="font-semibold text-[#002452] block text-xs"><?= $uName ?></span>
          </td>
          <td class="px-4 py-3 text-xs">
            <span class="inline-flex px-2 py-0.5 rounded bg-gray-100 text-[#44474f] font-medium"><?= $mod ?></span>
          </td>
          <td class="px-4 py-3 font-mono text-xs font-bold text-[#885200]"><?= $act ?></td>
          <td class="px-4 py-3 text-xs font-semibold text-[#1b1c1a]"><?= $ref ?></td>
          <td class="px-4 py-3 text-xs text-[#44474f] max-w-xs truncate" title="<?= $desc ?>"><?= $desc ?></td>
          <td class="px-4 py-3 font-mono text-xs text-[#747780]"><?= $ip ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="p-3 border-t border-[#e3e2df] text-xs text-[#747780] bg-gray-50 flex justify-between">
    <span>Showing latest <?= count($logs) ?> audit entries</span>
    <span class="text-emerald-700 font-semibold flex items-center gap-1">
      <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Immutable Forensic Ledger
    </span>
  </div>
</div>

<script>
function filterAuditTable() {
  const query = document.getElementById('audit-search').value.toLowerCase();
  const rows = document.querySelectorAll('#audit-table tbody tr');
  rows.forEach(r => {
    const txt = r.textContent.toLowerCase();
    r.style.display = txt.includes(query) ? '' : 'none';
  });
}

function exportAuditTableToCSV(filename) {
  let csv = [];
  const rows = document.querySelectorAll("#audit-table tr");
  for (let i = 0; i < rows.length; i++) {
    let row = [], cols = rows[i].querySelectorAll("td, th");
    for (let j = 0; j < cols.length; j++) {
      let text = cols[j].innerText.replace(/"/g, '""');
      row.push('"' + text + '"');
    }
    csv.push(row.join(","));
  }
  const csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
  const downloadLink = document.createElement("a");
  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = "none";
  document.body.appendChild(downloadLink);
  downloadLink.click();
}
</script>

<?php include 'includes/footer.php'; ?>
