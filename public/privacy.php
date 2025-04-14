<?php
require_once '../private/initialize.php';

$page_title = "Privacy Policy";
include_header($session, $page_title);

?>

<main role="main" id="main">
  <div class="container mt-5 mb-5">
    <h1 class="mb-2">Privacy Policy</h1>
    <p class="text-muted">Effective Date: 13 April 2025</p>

    <p class="mt-5">This Privacy Policy explains how the Village Market web application ("Site") collects, uses, and protects your information. This Site was developed as a student project for the WEB-289 Internet Technologies Project course at Asheville-Buncombe Technical Community College ("A-B Tech").</p>
    <p>This policy applies to all users of the Site, including members of the public, registered vendors, and administrative users. Please read this policy carefully to understand how your information may be used during your interaction with the Site.</p>
    <p>This Site is intended solely for educational demonstration and does not collect or use personal information for commercial purposes.</p>

    <h2 class="mt-5 mb-3">Information We Collect</h2>
    <p>This Site collects information that you voluntarily submit through forms, such as during registration, profile updates, or when submitting a contact message. This may include:</p>

    <ul>
      <li>First and last name</li>
      <li>Email address</li>
      <li>Phone number</li>
      <li>Business details (if registering as a vendor)</li>
      <li>Products or content you add as part of your account</li>
    </ul>

    <p>A temporary session cookie is also used to support secure login and user account functionality. This cookie contains a randomly generated session ID and does not store any personal information.</p>
    <p>No other cookies are used. The Site does not use third-party tracking, analytics, or persistent cookies of any kind.</p>
    <p>No sensitive personal data is requested or required. Users are encouraged not to submit real personal details when testing or demonstrating site features.</p>

    <h2 class="mt-5 mb-3">How We Use Your Information</h2>
    <p>Any information you submit through this Site is used solely to support the features and functionality of the applications. This includes:</p>

    <ul>
      <li>Creating and managing user accounts</li>
      <li>Displaying vendor and product information</li>
      <li>Tracking vendor attendance and availability</li>
      <li>Allowing administrators to review and manage user submissions</li>
      <li>Responding to user inquiries through the contact form</li>
    </ul>

    <p>No information is used for marketing purposes, shared with third parties, or stored beyond what is necessary for demonstrating the academic requirements of this project.</p>

    <h2 class="mt-5 mb-3">How Information is Stored and Protected</h2>
    <p>All information submitted through the Site is stored on a secure server associated with the project's web hosting environment. Data is stored in a MySQL database and only used within the context of this academic application.</p>
    <p>User passwords are encrypted using secure hashing algorithms before being stored. Administrative pages are protected by login authentication and role-based access control.</p>
    <p>While reasonable precautions have been taken to protect the integrity of the Site and its data, please be aware that this Site is not intended for handling sensitive or confidential information.</p>

    <h2 class="mt-5 mb-3">User Rights and Contact</h2>
    <p>If you have any questions about this Privacy Policy, or if you would like to request the removal of any information you have submitted during testing, please contact the administrator through the <a href="contact.php">Contact</a> page. Requests will be processed promptly as part of the project's ongoing development and maintenance.</p>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
