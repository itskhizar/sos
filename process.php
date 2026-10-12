<?php
/**
 * SOS Hermann Gmeiner School & College · Root Process Controller
 * new-structure/process.php
 */
include_once("include/classes/session.php");

class Process
{
   function __construct()
   {
      global $session;

      if (isset($_POST['sublogin'])) {
         $this->procLogin();
      } else if (isset($_POST['subjoin'])) {
         $this->procRegister();
      } else if (isset($_POST['public_enquiry']) || isset($_POST['contact_submission'])) {
         $this->procPublicEnquiry();
      } else if (isset($_POST['public_admission']) || isset($_POST['admission_submission']) || isset($_POST['application'])) {
         $this->procPublicAdmission();
      } else if (isset($_POST['apply_job'])) {
         $this->procApplyJob();
      } else if (isset($_POST['changepassword'])) {
         $this->procChangePassword();
      } else if (isset($_POST['change_accountdetails'])) {
         $this->procChangeAccountDetails();
      } else if (isset($_POST['uploadimage'])) {
         $this->procUploadImage();
      } else if ($session->logged_in) {
         $this->procLogout();
      } else {
         header("Location: index.php");
         exit;
      }
   }

   function procLogin()
   {
      global $session, $form;
      $username = $_POST['user'] ?? ($_POST['username'] ?? '');
      $password = $_POST['pass'] ?? '';
      $remember = isset($_POST['rememberMe']);

      $retval = $session->login($username, $password, $remember);

      if ($retval) {
         header("Location: dashboard/index.php");
         exit;
      } else {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: login.php?error=invalid");
         exit;
      }
   }

   function procLogout()
   {
      global $session;
      $session->logout();
      header("Location: login.php?logout=true");
      exit;
   }

   function procRegister()
   {
      global $session, $form;
      $retval = $session->register(
         $_POST['user'] ?? '',
         $_POST['pass'] ?? '',
         $_POST['email'] ?? '',
         $_POST['fullname'] ?? '',
         $_POST['phone'] ?? ''
      );

      if ($retval == 0) {
         header("Location: login.php?registered=true");
         exit;
      } else {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: signup.php?error=failed");
         exit;
      }
   }

   function procPublicEnquiry()
   {
      global $database;
      $fullName = trim(($_POST['first_name'] ?? '') . ' ' . ($_POST['last_name'] ?? ''));
      if (!$fullName) {
         $fullName = $_POST['parent_name'] ?? ($_POST['student_name'] ?? ($_POST['name'] ?? 'Visitor'));
      }

      $msg = $_POST['message'] ?? '';
      if (!empty($_POST['interest'])) {
         $msg = '[' . ucfirst($_POST['interest']) . '] ' . $msg;
      }

      $data = [
         'source' => 'Website',
         'enquiry_type' => $_POST['enquiry_type'] ?? 'Admission',
         'parent_name' => $fullName,
         'student_name' => $_POST['student_name'] ?? $fullName,
         'phone' => $_POST['phone'] ?? '',
         'email' => $_POST['email'] ?? '',
         'address' => $_POST['address'] ?? '',
         'message' => $msg,
         'requested_class_id' => intval($_POST['class_id'] ?? 0),
         'status' => 'New'
      ];
      $id = $database->addNewEnquiry($data, 'Website Visitor');
      if ($id) {
         header("Location: contact.php?submitted=true");
         exit;
      } else {
         header("Location: contact.php?error=failed");
         exit;
      }
   }

   function procPublicAdmission()
   {
      global $database;
      
      // Resolve student name
      $sName = trim(($_POST['s_first_name'] ?? '') . ' ' . ($_POST['s_last_name'] ?? ''));
      if (!$sName) $sName = $_POST['student_name'] ?? 'Applicant';

      // Resolve class id from name or id
      $classId = intval($_POST['class_id'] ?? ($_POST['applied_class_id'] ?? 0));
      if ($classId <= 0 && !empty($_POST['s_class'])) {
         $classes = $database->getAllClasses();
         foreach ($classes as $c) {
            if (stripos($c['class_name'], $_POST['s_class']) !== false || stripos($_POST['s_class'], $c['class_name']) !== false) {
               $classId = intval($c['class_id']);
               break;
            }
         }
         if ($classId <= 0 && !empty($classes)) {
            $classId = intval($classes[0]['class_id']);
         }
      }

      $pName = $_POST['father_name'] ?? ($_POST['parent_name'] ?? '');
      $pPhone = $_POST['father_phone'] ?? ($_POST['parent_phone'] ?? ($_POST['phone'] ?? ''));
      $pEmail = $_POST['email'] ?? ($_POST['parent_email'] ?? '');
      $addr = $_POST['home_address'] ?? ($_POST['address'] ?? '');
      if (!empty($_POST['city_district'])) $addr .= ', ' . $_POST['city_district'];
      if (!empty($_POST['province'])) $addr .= ', ' . $_POST['province'];

      $data = [
         'source' => 'Website',
         'student_name' => $sName,
         'date_of_birth' => $_POST['s_dob'] ?? ($_POST['date_of_birth'] ?? null),
         'gender' => $_POST['s_gender'] ?? ($_POST['gender'] ?? 'Male'),
         'blood_group' => $_POST['blood_group'] ?? '',
         'religion' => $_POST['religion'] ?? 'Islam',
         'nationality' => $_POST['nationality'] ?? 'Pakistani',
         'address' => $addr,
         'phone' => $pPhone,
         'email' => $pEmail,
         'parent_name' => $pName,
         'parent_phone' => $pPhone,
         'parent_email' => $pEmail,
         'requested_session_id' => intval($_POST['session_id'] ?? 0),
         'applied_class_id' => $classId,
         'status' => 'Submitted',
         'remarks' => 'Online submission (Father CNIC: ' . ($_POST['father_cnic'] ?? 'N/A') . ')'
      ];

      // Auto resolve active session if not selected
      if ($data['requested_session_id'] <= 0) {
         $act = $database->getActiveSession();
         if ($act) $data['requested_session_id'] = $act['session_id'];
      }

      $app_id = $database->addNewApplication($data, 'Web Applicant');
      if ($app_id) {
         $app = $database->getApplication($app_id);

         // Handle file uploads
         $uploadDir = __DIR__ . '/assets/uploads/admissions/';
         if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
         }

         if (!empty($_FILES)) {
            foreach ($_FILES as $inputName => $file) {
               if (!empty($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
                  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                  $safeName = 'doc_' . $app_id . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
                  $dest = $uploadDir . $safeName;
                  if (move_uploaded_file($file['tmp_name'], $dest)) {
                     $relPath = 'assets/uploads/admissions/' . $safeName;
                     $docType = match($inputName) {
                        'doc_photo' => 'Student Photograph',
                        'doc_cnic_front' => 'Father CNIC Front',
                        'doc_cnic_back' => 'Father CNIC Back',
                        'doc_previous_record' => 'Previous Academic Certificate / DMC',
                        default => ucfirst(str_replace(['doc_', '_', '-'], ' ', $inputName))
                     };
                     $database->addApplicationDocument($app_id, $docType, $relPath, 'Web Applicant');
                  }
               }
            }
         }

         header("Location: admissions-track.php?app_id=" . urlencode($app['application_no']) . "&submitted=1");
         exit;
      } else {
         header("Location: admissions-apply.php?error=submission_failed");
         exit;
      }
   }

   function procChangePassword()
   {
      global $session;
      $username = $_POST['username'] ?? $session->username;
      $curpass = $_POST['curpass'] ?? '';
      $newpass = $_POST['newpass'] ?? '';
      $retval = $session->changepassword($username, $curpass, $newpass);
      header("Location: " . $session->referrer . "?msg=" . ($retval === 0 ? "pass_success" : "pass_error"));
      exit;
   }

   function procChangeAccountDetails()
   {
      global $session;
      $username = $_POST['username'] ?? $session->username;
      $displayname = $_POST['displayname'] ?? '';
      $email = $_POST['email'] ?? '';
      $phone = $_POST['phone'] ?? '';
      $retval = $session->change_accountdetails($username, $displayname, $email, $phone);
      header("Location: " . $session->referrer . "?msg=" . ($retval === 0 ? "account_success" : "account_error"));
      exit;
   }

   function procUploadImage()
   {
      global $session;
      if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
         header("Location: " . $session->referrer . "?msg=upload_error");
         exit;
      }
      $image = basename($_FILES['image']['name']);
      $fileTmpPath = $_FILES['image']['tmp_name'];
      $dest = __DIR__ . "/images/" . $image;
      if (!is_dir(__DIR__ . "/images")) @mkdir(__DIR__ . "/images", 0777, true);
      move_uploaded_file($fileTmpPath, $dest);
      $session->uploadimage($_POST['username'] ?? $session->username, $image);
      header("Location: " . $session->referrer . "?msg=upload_success");
      exit;
   }

   function procApplyJob()
   {
      global $database;
      $job_id = intval($_POST['job_id'] ?? 0);
      if ($job_id <= 0) {
         header("Location: careers.php?error=invalid_job");
         exit;
      }

      $resumePath = '';
      if (!empty($_FILES['resume_file']['name']) && $_FILES['resume_file']['error'] === UPLOAD_ERR_OK) {
         $uploadDir = __DIR__ . '/uploads/resumes/';
         if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
         }
         $ext = pathinfo($_FILES['resume_file']['name'], PATHINFO_EXTENSION);
         $filename = 'cv_' . $job_id . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
         $target = $uploadDir . $filename;
         if (move_uploaded_file($_FILES['resume_file']['tmp_name'], $target)) {
            $resumePath = 'uploads/resumes/' . $filename;
         }
      }

      $ok = $database->addJobApplication($job_id, $_POST, $resumePath);
      if ($ok) {
         header("Location: careers.php?applied=true#open-positions");
      } else {
         header("Location: careers.php?error=application_failed#open-positions");
      }
      exit;
   }
}

new Process;
?>