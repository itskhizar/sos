<?php
/**
 * Student Documents — dashboard/student-documents.php
 */
$dashTitle   = 'Student Documents';
$currentDash = 'student-documents';
include 'includes/header.php';
?>

<!-- Page Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <p class="text-sm text-[#44474f]">Manage and verify uploaded student documents including B-Forms, photos, and certificates.</p>
  <button onclick="document.getElementById('modal-upload-doc').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">upload_file</span>
    Upload Document
  </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
  <?php $s = [
    ['Total Documents','1,284','folder','#002452'],
    ['Verified','1,102','verified','#004424'],
    ['Pending Verify','93','pending_actions','#885200'],
    ['Missing Docs','89','report_problem','#ba1a1a'],
  ];
  foreach ($s as [$l,$v,$i,$c]): ?>
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center gap-3 mb-3">
      <div class="p-2 rounded-lg" style="background:<?= $c ?>18">
        <span class="material-symbols-outlined text-[20px]" style="color:<?= $c ?>"><?= $i ?></span>
      </div>
    </div>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $v ?></p>
    <p class="text-xs text-[#44474f] font-medium mt-1"><?= $l ?></p>
  </div>
  <?php endforeach; ?>
</div>

<!-- Filters & Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <!-- Filter Header -->
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row gap-3">
    <div class="relative flex-1">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" placeholder="Search by student name…" class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]">
      <option>All Document Types</option>
      <option>B-Form / CNIC</option>
      <option>Birth Certificate</option>
      <option>School Leaving Certificate</option>
      <option>Photograph</option>
      <option>Medical Certificate</option>
    </select>
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]">
      <option>All Statuses</option>
      <option>Verified</option>
      <option>Pending</option>
      <option>Rejected</option>
    </select>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3 text-left font-semibold">Student</th>
          <th class="px-4 py-3 text-left font-semibold">Document Type</th>
          <th class="px-4 py-3 text-left font-semibold">Class</th>
          <th class="px-4 py-3 text-left font-semibold">Uploaded On</th>
          <th class="px-4 py-3 text-left font-semibold">Verified By</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
          <th class="px-4 py-3 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $docs = [
          ['Ahmad Bilal',    'B-Form / CNIC',             'Class VI',   '2027-01-05', 'Admin',   'Verified'],
          ['Sara Malik',     'Birth Certificate',          'Class I',    '2027-01-06', 'Admin',   'Verified'],
          ['Sara Malik',     'Photograph',                 'Class I',    '2027-01-06', 'Admin',   'Verified'],
          ['Hassan Ali',     'School Leaving Certificate', 'Class III',  '2027-01-08', '—',       'Pending'],
          ['Fatima Noor',    'B-Form / CNIC',              'Class IX',   '2027-01-04', 'Admin',   'Verified'],
          ['Fatima Noor',    'Medical Certificate',        'Class IX',   '2027-01-04', '—',       'Pending'],
          ['Usman Tariq',    'Photograph',                 'Class VII',  '2027-01-09', 'Staff1',  'Verified'],
          ['Zainab Hussain', 'B-Form / CNIC',              'KG',         '2027-01-07', '—',       'Pending'],
          ['Ali Raza',       'Birth Certificate',          'Class II',   '2026-12-20', 'Admin',   'Verified'],
          ['Ayesha Khan',    'School Leaving Certificate', 'Class VIII', '2026-12-22', '—',       'Rejected'],
        ];
        $statusColors = [
          'Verified' => 'bg-[#9af6b8]/40 text-[#00522d]',
          'Pending'  => 'bg-[#ffdcbb] text-[#673d00]',
          'Rejected' => 'bg-[#ffdad6] text-[#93000a]',
        ];
        $docIcons = [
          'B-Form / CNIC'               => 'badge',
          'Birth Certificate'           => 'description',
          'School Leaving Certificate'  => 'school',
          'Photograph'                  => 'photo_camera',
          'Medical Certificate'         => 'local_hospital',
        ];
        foreach ($docs as [$student,$docType,$class,$uploadDate,$verifiedBy,$status]):
          $icon = $docIcons[$docType] ?? 'folder';
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-4">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-full bg-[#002452]/10 flex items-center justify-center text-[#002452] font-bold text-xs shrink-0">
                <?= strtoupper(substr($student, 0, 1)) ?>
              </div>
              <span class="font-semibold text-[#1b1c1a]"><?= $student ?></span>
            </div>
          </td>
          <td class="px-4 py-4">
            <div class="flex items-center gap-2 text-[#44474f]">
              <span class="material-symbols-outlined text-[16px]"><?= $icon ?></span>
              <?= $docType ?>
            </div>
          </td>
          <td class="px-4 py-4 text-[#44474f]"><?= $class ?></td>
          <td class="px-4 py-4 text-[#747780] text-xs"><?= $uploadDate ?></td>
          <td class="px-4 py-4 text-[#44474f]"><?= $verifiedBy ?></td>
          <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold <?= $statusColors[$status] ?>"><?= $status ?></span>
          </td>
          <td class="px-4 py-4">
            <div class="flex items-center justify-center gap-1">
              <button class="p-1.5 text-[#44474f] hover:text-[#002452] hover:bg-[#002452]/8 rounded-md transition-all" title="View Document">
                <span class="material-symbols-outlined text-[16px]">visibility</span>
              </button>
              <?php if ($status === 'Pending'): ?>
              <button class="p-1.5 text-[#44474f] hover:text-[#004424] hover:bg-[#004424]/8 rounded-md transition-all" title="Verify">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>
              </button>
              <button class="p-1.5 text-[#44474f] hover:text-[#ba1a1a] hover:bg-[#ba1a1a]/8 rounded-md transition-all" title="Reject">
                <span class="material-symbols-outlined text-[16px]">cancel</span>
              </button>
              <?php endif; ?>
              <button class="p-1.5 text-[#44474f] hover:text-[#885200] hover:bg-[#885200]/8 rounded-md transition-all" title="Download">
                <span class="material-symbols-outlined text-[16px]">download</span>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="px-6 py-3 border-t border-[#e3e2df] flex items-center justify-between text-xs text-[#44474f]">
    <span>Showing 10 of 1,284 documents</span>
    <div class="flex items-center gap-1">
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0] disabled:opacity-50" disabled>Prev</button>
      <button class="px-2.5 py-1 rounded bg-[#002452] text-white">1</button>
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0]">2</button>
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0]">Next</button>
    </div>
  </div>
</div>

<!-- UPLOAD DOC MODAL -->
<div id="modal-upload-doc" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-upload-doc').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold mb-1">Upload Document</h2>
    <p class="text-sm text-[#44474f] mb-6">Upload a document for a student record.</p>
    <form class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Student</label>
        <input type="text" placeholder="Search student name or ID…" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Document Type</label>
        <select class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option>B-Form / CNIC</option>
          <option>Birth Certificate</option>
          <option>School Leaving Certificate</option>
          <option>Photograph</option>
          <option>Medical Certificate</option>
          <option>Other</option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">File</label>
        <div class="border-2 border-dashed border-[#e3e2df] rounded-xl p-6 text-center hover:border-[#002452]/40 transition-colors cursor-pointer">
          <span class="material-symbols-outlined text-[36px] text-[#c4c6d0] mb-2">cloud_upload</span>
          <p class="text-sm text-[#44474f]">Click to upload or drag & drop</p>
          <p class="text-xs text-[#747780] mt-1">PDF, JPG, PNG up to 5MB</p>
          <input type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png"/>
        </div>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-upload-doc').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Upload</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
