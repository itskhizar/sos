<?php
/**
 * SOS Hermann Gmeiner School & College · Session & Authorization Layer
 * Dynamic RBAC, Login/Logout, and Role-Based Permission Validation
 */

include_once("database.php");
include_once("mailer.php");
include_once("form.php");

class Session
{
   var $username;
   var $userid;
   var $userlevel;
   var $role_id;
   var $role_name;
   var $full_name;
   var $fullname;
   var $time;
   var $logged_in;
   var $userinfo = array();
   var $url;
   var $referrer;

   function __construct()
   {
      date_default_timezone_set('Asia/Karachi');
      $this->time = time();
      $this->startSession();
   }

   function startSession()
   {
      global $database;
      if (session_status() === PHP_SESSION_NONE) {
         session_start();
      }

      $this->logged_in = $this->checkLogin();

      if (!$this->logged_in) {
         $this->username = $_SESSION['username'] = GUEST_NAME;
         $this->userlevel = GUEST_LEVEL;
         $this->role_id = 0;
         $this->role_name = 'Guest';
         $database->addActiveGuest($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', $this->time);
      } else {
         $database->addActiveUser($this->username, $this->time);
      }

      $database->removeInactiveUsers();
      $database->removeInactiveGuests();

      if (isset($_SESSION['url'])) {
         $this->referrer = $_SESSION['url'];
      } else {
         $this->referrer = "index.php";
      }

      $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
   }

   function checkLogin()
   {
      global $database;
      $current_time = time();

      if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
         $_SESSION['username'] = $_COOKIE['cookname'];
         $_SESSION['userid'] = $_COOKIE['cookid'];
         $_SESSION['login_time'] = $_SESSION['login_time'] ?? $current_time;
      }

      if (
         isset($_SESSION['username']) &&
         isset($_SESSION['userid']) &&
         $_SESSION['username'] != GUEST_NAME
      ) {
         if (!isset($_SESSION['login_time']) || ($current_time - $_SESSION['login_time']) > COOKIE_EXPIRE) {
            $this->logout();
            return false;
         }

         if ($database->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0) {
            $this->logout();
            return false;
         }

         $this->userinfo = $database->getUserInfo($_SESSION['username']);
         if (!$this->userinfo) {
            $this->logout();
            return false;
         }

         $this->username = $this->userinfo['username'];
         $this->userid = $this->userinfo['userid'];
         $this->userlevel = $this->userinfo['userlevel'] ?? 1;
         $this->role_id = intval($this->userinfo['role_id'] ?? 1);
         $this->role_name = $this->userinfo['role_name'] ?? 'User';
         $this->full_name = $this->userinfo['full_name'] ?: $this->username;
         $this->fullname = $this->full_name;

         return true;
      }

      $this->logout();
      return false;
   }

   function login($subuser, $subpass, $remember = true)
   {
      global $database, $form;

      $subuser = trim($subuser);
      if (!$subuser) {
         $form->setError("user", "* Username or email is required");
      }
      if (!$subpass) {
         $form->setError("pass", "* Password is required");
      }
      if ($form->num_errors > 0) return false;

      $result = $database->confirmUserPass($subuser, $subpass);
      if ($result == 1) {
         $form->setError("user", "* Invalid username or inactive account");
         return false;
      } else if ($result == 2) {
         $form->setError("pass", "* Invalid password");
         return false;
      }

      $userInfo = $database->getUserInfo($subuser);
      if (!$userInfo) {
         $form->setError("user", "* Account not found");
         return false;
      }

      $this->userinfo = $userInfo;
      $this->username = $_SESSION['username'] = $userInfo['username'];
      $this->userid = $_SESSION['userid'] = $userInfo['userid'];
      $this->role_id = $_SESSION['role_id'] = intval($userInfo['role_id']);
      $this->role_name = $userInfo['role_name'] ?? 'Staff';
      $this->full_name = $userInfo['full_name'] ?: $userInfo['username'];
      $this->fullname = $this->full_name;

      $database->updateUserField($this->username, "last_login_at", date('Y-m-d H:i:s'));
      $database->addActiveUser($this->username, $this->time);
      $database->removeActiveGuest($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

      if ($remember) {
         setcookie("cookname", $this->username, time() + COOKIE_EXPIRE, COOKIE_PATH);
         setcookie("cookid", $this->userid, time() + COOKIE_EXPIRE, COOKIE_PATH);
      }

      $_SESSION['login_time'] = time();
      $this->logged_in = true;
      return true;
   }

   function logout()
   {
      global $database;
      if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
         setcookie("cookname", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
         setcookie("cookid", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
      }

      if (isset($_SESSION['username']) && $_SESSION['username'] != GUEST_NAME) {
         $database->removeActiveUser($_SESSION['username']);
      }

      $_SESSION['username'] = GUEST_NAME;
      $_SESSION['userid'] = "";
      $_SESSION['role_id'] = 0;
      unset($_SESSION['login_time']);

      $this->logged_in = false;
      $this->username = GUEST_NAME;
      $this->userlevel = GUEST_LEVEL;
      $this->role_id = 0;
      $this->role_name = 'Guest';
      return true;
   }

   function register($subuser, $subpass, $subemail, $fullname = '', $phone = '', $role_id = ROLE_ADMISSIONS_ID)
   {
      global $database, $form;

      $subuser = trim($subuser);
      if (!$subuser || strlen($subuser) < 3) {
         $form->setError("user", "* Username must be at least 3 characters");
      } else if ($database->usernameTaken($subuser)) {
         $form->setError("user", "* Username already registered");
      }

      if (!$subpass || strlen($subpass) < 6) {
         $form->setError("pass", "* Password must be at least 6 characters");
      }

      if (!$subemail || !filter_var($subemail, FILTER_VALIDATE_EMAIL)) {
         $form->setError("email", "* Valid email address is required");
      } else if ($database->emailTaken($subemail)) {
         $form->setError("email", "* Email is already registered");
      }

      if ($form->num_errors > 0) return 1;

      if ($database->addNewUser($subuser, $subpass, $subemail, $role_id, $fullname, $phone)) {
         return 0; // Success
      }
      return 2; // Database error
   }

   /**
    * DYNAMIC RBAC PERMISSION CHECK
    * Checks if current user has permission for a specific module and action.
    * No hardcoded user levels!
    */
   function hasPermission($module, $action = 'view')
   {
      global $database;
      if (!$this->logged_in) return false;

      // Super Admin & Sub Admin full access
      if ($this->role_id === ROLE_SUPER_ADMIN_ID || strtolower($this->role_name) === 'super admin' || strtolower($this->role_name) === 'sub admin' || $this->role_id === 5) {
         return true;
      }

      return $database->checkRolePermission($this->role_id, $module, $action);
   }

   function isSuperAdmin()
   {
      return ($this->logged_in && ($this->role_id === ROLE_SUPER_ADMIN_ID || strtolower($this->role_name) === 'super admin'));
   }

   function isSubAdmin()
   {
      return ($this->logged_in && (strtolower($this->role_name) === 'sub admin' || $this->role_id === 5));
   }

   function isAdmin()
   {
      return ($this->logged_in && ($this->isSuperAdmin() || $this->isSubAdmin()));
   }

   function isMaster()
   {
      return $this->isAdmin();
   }

   function isMember()
   {
      return $this->logged_in;
   }

   function requirePermission($module, $action = 'view', $redirect = 'index.php')
   {
      if (!$this->logged_in) {
         header("Location: ../login.php");
         exit;
      }
      if (!$this->hasPermission($module, $action)) {
         header("Location: $redirect?error=unauthorized");
         exit;
      }
   }

   function uploadimage($username, $image)
   {
      global $database;
      return $database->uploadimage($username, $image) ? 0 : 2;
   }

   function change_accountdetails($username, $displayname, $email, $phone)
   {
      global $database;
      return $database->change_accountdetails($username, $displayname, $email, $phone) ? 0 : 2;
   }

   function changepassword($username, $curpass, $newpass)
   {
      global $database, $form;
      if (empty($curpass)) {
         $form->setError("curpass", "* Current password is required");
         return 1;
      }
      $result = $database->confirmUserPass($username, $curpass);
      if ($result == 1) {
         $form->setError("user", "* User not found");
         return 1;
      } else if ($result == 2) {
         $form->setError("curpass", "* Invalid current password");
         return 1;
      }
      return $database->changepassword($username, $curpass, $newpass) ? 0 : 2;
   }

   function generateRandID()
   {
      return md5(uniqid(rand(), true));
   }
}

$session = new Session;
$form = new Form;
?>