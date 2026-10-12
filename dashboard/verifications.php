<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Document Verifications Queue — dashboard/verifications.php
 * Aligned with Stitch Master Design
 */
$dashTitle = 'Pending Verifications';
$currentDash = 'payments';
include 'includes/header.php';

$docs = $database->getAllVerificationDocs();
$pendingCount = count(array_filter($docs, fn($d) => empty($d['is_verified'])));
?>

<?php if(isset($_GET['verified']) && $_GET['verified'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Document verification status updated successfully.</div>
<?php endif; ?>

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
  <div>
    <div class="flex items-center gap-2 text-xs text-[#54524B] mb-1">
      <span>Operations</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#002452] font-semibold">Verification Queue</span>
    </div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Document Verifications</h1>
  </div>
  <span class="px-3 py-1.5 rounded-full bg-[#ffdcbb] text-[#673d00] font-bold text-xs flex items-center gap-1.5">
    <span class="material-symbols-outlined text-[16px]">priority_high</span> <?= $pendingCount ?> Items Require Review
  </span>
</div>

<!-- Verifications Table Card -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Reference ID</th>
          <th class="py-3.5 px-6">Candidate / Student</th>
          <th class="py-3.5 px-6">Class</th>
          <th class="py-3.5 px-6">Document Type</th>
          <th class="py-3.5 px-6">Attachment</th>
          <th class="py-3.5 px-6">Status</th>
          <th class="py-3.5 px-6 text-right">Verification Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php if (empty($docs)): ?>
        <tr>
          <td colspan="7" class="py-8 text-center text-gray-500">
            No applicant documents currently queued for verification.
          </td>
        </tr>
        <?php else: foreach ($docs as $d):
          $isVer = !empty($d['is_verified']);
          $badge = $isVer ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800';
          $status = $isVer ? 'Verified' : 'Pending';
        ?>
        <tr class="hover:bg-gray-50/80 transition-colors">
          <td class="py-4 px-6 font-bold text-[#002452]"><?= htmlspecialchars($d['application_no'] ?? 'APP-' . $d['application_id']) ?></td>
          <td class="py-4 px-6 font-medium"><?= htmlspecialchars($d['student_name'] ?? '—') ?></td>
          <td class="py-4 px-6"><?= htmlspecialchars($d['class_name'] ?? '—') ?></td>
          <td class="py-4 px-6 text-[#54524B]"><?= htmlspecialchars($d['document_type']) ?></td>
          <td class="py-4 px-6">
            <?php if (!empty($d['file_path'])): ?>
            <a href="../<?= htmlspecialchars($d['file_path']) ?>" target="_blank" class="inline-flex items-center gap-1 text-[#002452] hover:underline font-semibold">
              <span class="material-symbols-outlined text-sm">attach_file</span> View File
            </a>
            <?php else: ?>
            <span class="text-gray-400 italic">No upload</span>
            <?php endif; ?>
          </td>
          <td class="py-4 px-6">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold <?= $badge ?>"><?= $status ?></span>
          </td>
          <td class="py-4 px-6 text-right">
            <div class="flex items-center justify-end gap-2">
              <form action="process.php" method="post" class="inline">
                <input type="hidden" name="doc_id" value="<?= $d['admission_doc_id'] ?>">
                <input type="hidden" name="is_verified" value="1">
                <button type="submit" name="verifydocument" class="px-3 py-1 bg-[#00522d] text-white font-bold rounded hover:bg-[#004424] transition-colors flex items-center gap-1 text-xs">
                  <span class="material-symbols-outlined text-sm">check</span> Verify
                </button>
              </form>
              <form action="process.php" method="post" class="inline">
                <input type="hidden" name="doc_id" value="<?= $d['admission_doc_id'] ?>">
                <input type="hidden" name="is_verified" value="0">
                <button type="submit" name="verifydocument" class="px-3 py-1 bg-red-50 text-red-700 font-bold rounded border border-red-200 hover:bg-red-100 transition-colors text-xs">
                  Reject
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
