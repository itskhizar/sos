<?php
/**
 * SOS Hermann Gmeiner School & College · Staff & Admin Portal Login
 * new-structure/login.php
 */
include_once 'include/classes/session.php';
if ($session->logged_in) {
    header("Location: dashboard/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html class="h-full bg-[#132644]" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Staff Portal Sign In — SOS Hermann Gmeiner</title>
  
  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

  <!-- Tailwind Play CDN -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <style>
    body { font-family: 'Work Sans', sans-serif; }
    h1, h2, .font-fraunces { font-family: 'Fraunces', Georgia, serif; }
  </style>
</head>
<body class="antialiased min-h-screen relative flex items-center justify-center bg-[#132644] overflow-hidden p-6">
  
  <!-- Login Card Container -->
  <main class="relative z-10 w-full max-w-[440px] bg-white rounded-2xl shadow-2xl p-8 md:p-10 border border-[#EAE7DE] flex flex-col gap-6">
    
    <!-- Header Section with Official Crest -->
    <header class="flex flex-col items-center text-center gap-3">
      <div class="w-16 h-16 rounded-full bg-[#F7F4EE] flex items-center justify-center border border-[#EAE7DE] p-2 shadow-sm">
        <img alt="SOS Crest" class="w-full h-full object-contain" src="assets/images/logo.jpg"/>
      </div>
      <div>
        <h1 class="font-fraunces text-2xl font-bold text-[#132644]">Sign in to your account</h1>
        <p class="text-xs text-[#54524B] mt-1">SOS Hermann Gmeiner School Management Portal</p>
      </div>
    </header>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'unauthorized'): ?>
      <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">lock</span>
        <span>Access restricted. Insufficient role permissions.</span>
      </div>
    <?php endif; ?>

    <!-- Form Section -->
    <form action="process.php" method="post" class="flex flex-col gap-4">
      <!-- Email/Username Field -->
      <div>
        <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="username">Username or Email</label>
        <input class="w-full bg-white border border-[#c4c6d0] text-[#1C1C1A] text-sm rounded-lg px-3.5 py-2.5 focus:outline-none focus:border-[#C6892E] focus:ring-1 focus:ring-[#C6892E] transition-colors" id="username" name="user" placeholder="e.g. khizar or admin" required type="text" value="<?= isset($form) ? htmlspecialchars($form->value('user')) : '' ?>"/>
        <?php if(isset($form) && $form->error('user')): ?>
          <p class="text-xs text-red-600 mt-1"><?= $form->error('user') ?></p>
        <?php endif; ?>
      </div>

      <!-- Password Field -->
      <div>
        <div class="flex justify-between items-center mb-1.5">
          <label class="text-xs font-bold text-[#1C1C1A]" for="password">Password</label>
          <span class="text-xs text-[#C6892E] font-medium">Default: dev workspace</span>
        </div>
        <div class="relative flex items-center">
          <input class="w-full bg-white border border-[#c4c6d0] text-[#1C1C1A] text-sm rounded-lg pl-3.5 pr-10 py-2.5 focus:outline-none focus:border-[#C6892E] focus:ring-1 focus:ring-[#C6892E] transition-colors" id="password" name="pass" placeholder="••••••••" required type="password" value=""/>
          <button type="button" onclick="togglePass()" class="absolute right-3 text-[#747780] hover:text-[#1C1C1A] transition-colors">
            <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
          </button>
        </div>
        <?php if(isset($form) && $form->error('pass')): ?>
          <p class="text-xs text-red-600 mt-1"><?= $form->error('pass') ?></p>
        <?php endif; ?>
      </div>

      <!-- Remember Me -->
      <div class="flex items-center gap-2">
        <input type="checkbox" id="remember" name="rememberMe" checked class="rounded text-[#132644] focus:ring-[#C6892E]"/>
        <label for="remember" class="text-xs text-[#54524B]">Keep me signed in on this device</label>
      </div>

      <!-- Submit Button -->
      <button type="submit" name="sublogin" class="w-full bg-[#132644] hover:bg-[#0C1B30] text-white font-semibold text-sm py-3 rounded-lg shadow-sm transition-all mt-2 flex items-center justify-center gap-2">
        <span>Sign In to Portal</span>
        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
      </button>
    </form>

    <!-- Footer Links -->
    <div class="text-center pt-2 border-t border-[#EAE7DE] text-xs text-[#54524B]">
      <a href="index.php" class="text-[#C6892E] hover:underline font-semibold flex items-center justify-center gap-1">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
        Back to Public Website
      </a>
    </div>

  </main>

  <script>
    function togglePass() {
      const p = document.getElementById('password');
      const icon = document.getElementById('eye-icon');
      if (p.type === 'password') {
        p.type = 'text';
        icon.textContent = 'visibility_off';
      } else {
        p.type = 'password';
        icon.textContent = 'visibility';
      }
    }
  </script>
</body>
</html>