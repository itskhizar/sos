<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * System Settings — dashboard/settings.php
 * Module 12: School Master Configuration, Bank Accounts, Payment Gateways, Session Variables
 */
$dashTitle   = 'System Settings';
$currentDash = 'settings';
include 'includes/header.php';

$settings = $database->getSystemSettings();

$get = function($k, $default = '') use ($settings) {
  return htmlspecialchars($settings[$k] ?? $default);
};
?>

<!-- Header & Save Actions -->
<form action="process.php" method="post">
  <input type="hidden" name="updatesettings" value="1"/>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="font-serif text-2xl font-bold text-[#002452]">Institution Configuration &amp; Settings</h2>
      <p class="text-sm text-[#44474f] mt-1">Configure institutional parameters, bank collection accounts, fee grace periods, and academic defaults.</p>
    </div>
    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">save</span>
      Save All Changes
    </button>
  </div>

  <?php if (isset($_GET['saved'])): ?>
    <div class="mt-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
        <span>System settings updated successfully.</span>
      </div>
      <a href="settings.php" class="underline font-bold">Dismiss</a>
    </div>
  <?php endif; ?>

  <!-- Settings Panels -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <!-- Left Navigation / Categories -->
    <div class="space-y-2">
      <a href="#panel-school" class="w-full text-left px-4 py-3 rounded-lg bg-[#002452] text-white font-semibold text-sm flex items-center gap-3">
        <span class="material-symbols-outlined text-[20px]">school</span>
        School Profile &amp; Campus
      </a>
      <a href="#panel-bank" class="w-full text-left px-4 py-3 rounded-lg bg-white border border-[#e3e2df] text-[#44474f] hover:bg-gray-50 font-medium text-sm flex items-center gap-3">
        <span class="material-symbols-outlined text-[20px]">account_balance</span>
        Bank Collection Accounts
      </a>
      <a href="#panel-fees" class="w-full text-left px-4 py-3 rounded-lg bg-white border border-[#e3e2df] text-[#44474f] hover:bg-gray-50 font-medium text-sm flex items-center gap-3">
        <span class="material-symbols-outlined text-[20px]">payments</span>
        Fee Rules &amp; Late Surcharges
      </a>
    </div>

    <!-- Right Form Body -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Panel 1: Institution Profile -->
      <div id="panel-school" class="bg-white rounded-xl border border-[#e3e2df] p-6 shadow-sm space-y-4">
        <h3 class="font-serif text-lg font-bold text-[#1b1c1a] border-b border-[#e3e2df] pb-3">Institution Identity</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Institution Name</label>
            <input type="text" name="settings[institution_name]" value="<?= $get('institution_name', 'SOS Hermann Gmeiner School & College') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Campus Location</label>
            <input type="text" name="settings[campus_location]" value="<?= $get('campus_location', 'Dhodial, Mansehra, Khyber Pakhtunkhwa') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Official Contact Phone</label>
            <input type="text" name="settings[contact_phone]" value="<?= $get('contact_phone', '+92 (997) 530-221') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Official Email</label>
            <input type="email" name="settings[contact_email]" value="<?= $get('contact_email', 'info@sos-dhodial.edu.pk') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">BISE Affiliation Number</label>
          <input type="text" name="settings[bise_affiliation]" value="<?= $get('bise_affiliation', 'BISE-ATD/SEC/1982-04') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <!-- Panel 2: Bank Collection Accounts -->
      <div id="panel-bank" class="bg-white rounded-xl border border-[#e3e2df] p-6 shadow-sm space-y-4">
        <h3 class="font-serif text-lg font-bold text-[#1b1c1a] border-b border-[#e3e2df] pb-3">Primary Bank Fee Collection Account</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Designated Bank</label>
            <input type="text" name="settings[bank_name]" value="<?= $get('bank_name', 'Habib Bank Limited (HBL)') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Branch Name &amp; Code</label>
            <input type="text" name="settings[bank_branch]" value="<?= $get('bank_branch', 'Dhodial Branch (Code: 0142)') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Account Title</label>
            <input type="text" name="settings[account_title]" value="<?= $get('account_title', 'SOS Hermann Gmeiner School Dhodial') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Account Number</label>
            <input type="text" name="settings[account_number]" value="<?= $get('account_number', '0142-79012345-03') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-mono focus:outline-none focus:border-[#002452]"/>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">IBAN Number</label>
          <input type="text" name="settings[iban]" value="<?= $get('iban', 'PK36HABB0001427901234503') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-mono focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <!-- Panel 3: Fee Collection Rules -->
      <div id="panel-fees" class="bg-white rounded-xl border border-[#e3e2df] p-6 shadow-sm space-y-4">
        <h3 class="font-serif text-lg font-bold text-[#1b1c1a] border-b border-[#e3e2df] pb-3">Monthly Billing Policies</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Monthly Challan Due Date</label>
            <select name="settings[challan_due_day]" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
              <option value="10" <?= $get('challan_due_day', '15') == '10' ? 'selected' : '' ?>>10th of every month</option>
              <option value="15" <?= $get('challan_due_day', '15') == '15' ? 'selected' : '' ?>>15th of every month</option>
              <option value="20" <?= $get('challan_due_day', '15') == '20' ? 'selected' : '' ?>>20th of every month</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Daily Late Fine (PKR)</label>
            <input type="number" name="settings[late_fine_daily]" value="<?= $get('late_fine_daily', '50') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          </div>
        </div>
      </div>

      <div class="flex justify-end pt-2">
        <button type="submit" class="px-6 py-3 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm">
          Save All Changes
        </button>
      </div>
    </div>
  </div>
</form>

<?php include 'includes/footer.php'; ?>
