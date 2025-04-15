<?php
require_once('../../private/initialize.php');
$page_title = "Vendor FAQ / Getting Started";
include_header($session, $page_title);
?>

<main role="main" id="main">
  <div class="container mt-5 mb-5">
    <h1 class="mb-3">Vendor FAQ / Getting Started</h1>

    <p class="lead">
      Curious about becoming a vendor at the Village Market? Here's what you need to know.
    </p>

    <section class="mt-5">
      <!-- Row 1: Becoming a Vendor + Image -->
      <div class="row align-items-start mb-5">

        <!-- Left: Text -->
        <div class="col-md-7">
          <h2>Becoming a Vendor</h2>

          <p>Being part of the Village Market means more than just selling your products — it's about joining a community. Our vendors support one another, connect with local shoppers, and help create a vibrant, inclusive marketplace that reflects the best of our region.</p>

          <p>From pasture-raised meats and heirloom vegetables to baked goods, fresh flowers, and handcrafted wares — the Village Market welcomes a wide variety of offerings. We value the individuality of each vendor and the unique products they bring. Whether you're a seasoned grower or launching your first handmade product line, this is a place to share your passion, connect with the community, and grow your business in a supportive, collaborative environment.</p>

          <p>Ready to join us? Start by completing the <a href="/vendors/register.php">Vendor Registration Form</a>. All applications must be approved before login access is granted.</p>
        </div>

        <!-- Right: First Image -->
        <div class="col-md-5 text-center mt-4 mt-md-0">
          <img src="../assets/images/vendor_faq.jpeg" alt="Smiling vendor at market booth." class="img-fluid rounded shadow-sm h-100 object-fit-cover">
        </div>
      </div>

      <!-- Row 2: Image + Vendor Requirements -->
      <div class="row align-items-start">
        <!-- Left: Second Image -->
        <div class="col-md-5 text-center mb-4 mb-md-0">
          <img src="../assets/images/vendor_faq2.jpeg" alt="Smiling vendor and customer." class="img-fluid rounded shadow-sm h-100 object-fit-cover">
        </div>

        <!-- Right: Requirements Content -->
        <div class="col-md-7">
          <h2>Vendor Requirements</h2>

          <p>To ensure a high-quality, locally focused market, vendors must meet the following requirements:</p>

          <ul>
            <li><strong>Local Residency:</strong> Vendors must be based in North Carolina, South Carolina, Georgia, Tennessee, or Virginia.</li>
            <li><strong>Monthly Attendance:</strong> Vendors are required to confirm their attendance for upcoming market dates during the last week of each month.</li>
            <li><strong>Product Guidelines:</strong> All goods must be homegrown, handmade, or personally produced. Resale of mass-produced items is not permitted.</li>
            <li><strong>Professional Conduct:</strong> Vendors must uphold a standard of courtesy, cleanliness, and reliability while representing themselves and the market.</li>
          </ul>

          <p>If you have questions about eligibility or expectations, feel free to <a href="/contact.php">contact us</a> before applying. We're happy to help!</p>
        </div>
      </div>
    </section>



    <section class="mt-5">
      <h2 class="mb-4">Frequently Asked Questions</h2>

      <div class="accordion" id="vendorFAQ">

        <!-- Section: Account & Registration -->
        <h3 class="h5 mt-4 mb-3 text-primary">Account & Registration</h3>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading1">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
              How do I sign up as a vendor?
            </button>
          </h2>
          <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Fill out the <a href="/vendors/register.php">Vendor Registration Form</a>. You'll be asked to provide contact info and a description of your business. Approval is required before access.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
              What happens after I register?
            </button>
          </h2>
          <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Your application will be reviewed by an administrator. If approved, you'll receive a confirmation email and can then log in to manage your profile and products.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
              Can I edit my vendor information later?
            </button>
          </h2>
          <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Yes! You can update your contact info, business description, logo, and images anytime from your vendor dashboard.
            </div>
          </div>
        </div>

        <!-- Section: Attendance & Availability -->
        <h3 class="h5 mt-4 mb-3 text-primary">Attendance & Availability</h3>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading4">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
              How do I indicate which markets I'll attend?
            </button>
          </h2>
          <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Log in to your account and navigate to the Manage Attendance section. There, you can select dates for the upcoming month and save your availability.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading5">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
              What happens if I miss the deadline to select dates?
            </button>
          </h2>
          <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              If no dates are selected, we will assume you are unavailable for the next month. Contact an admin if you need to make changes after the deadline.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading6">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
              What if I need to cancel after confirming?
            </button>
          </h2>
          <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Cancellations should be submitted via your dashboard at least 48 hours in advance. Repeated no-shows may affect future participation.
            </div>
          </div>
        </div>

        <!-- Section: Products & Pricing -->
        <h3 class="h5 mt-4 mb-3 text-primary">Products & Pricing</h3>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading7">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
              How do I list my products?
            </button>
          </h2>
          <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              After logging in, go to the Products section. You can add items by name, description, image, and price unit. Each product must be approved before appearing publicly.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading8">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse8" aria-expanded="false" aria-controls="faqCollapse8">
              Can I set different price units?
            </button>
          </h2>
          <div id="faqCollapse8" class="accordion-collapse collapse" aria-labelledby="faqHeading8" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Yes. You can choose from options like “each,” “per pound,” or “per dozen.” You may add multiple price units per product if applicable.
            </div>
          </div>
        </div>

        <!-- Section: At the Market -->
        <h3 class="h5 mt-4 mb-3 text-primary">At the Market</h3>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading9">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse9" aria-expanded="false" aria-controls="faqCollapse9">
              What should I bring to the market?
            </button>
          </h2>
          <div id="faqCollapse9" class="accordion-collapse collapse" aria-labelledby="faqHeading9" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Bring your own tables, tents, signage, and payment setup (cash/change, card reader, etc.). The market does not provide these items.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading10">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse10" aria-expanded="false" aria-controls="faqCollapse10">
              Are vendor spaces assigned?
            </button>
          </h2>
          <div id="faqCollapse10" class="accordion-collapse collapse" aria-labelledby="faqHeading10" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Vendor locations are first-come, first-served unless otherwise arranged with the market coordinator.
            </div>
          </div>
        </div>

        <!-- Section: Policies & Support -->
        <h3 class="h5 mt-4 mb-3 text-primary">Policies & Support</h3>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading11">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse11" aria-expanded="false" aria-controls="faqCollapse11">
              Can I suspend or deactivate my vendor account?
            </button>
          </h2>
          <div id="faqCollapse11" class="accordion-collapse collapse" aria-labelledby="faqHeading11" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Yes. Contact an admin if you'd like to temporarily pause or permanently close your vendor account.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading12">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse12" aria-expanded="false" aria-controls="faqCollapse12">
              What are the market's rules or code of conduct?
            </button>
          </h2>
          <div id="faqCollapse12" class="accordion-collapse collapse" aria-labelledby="faqHeading12" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              All vendors are expected to conduct themselves professionally and follow all safety, cleanliness, and courtesy guidelines provided upon approval.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faqHeading13">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse13" aria-expanded="false" aria-controls="faqCollapse13">
              Who can I contact if I have questions?
            </button>
          </h2>
          <div id="faqCollapse13" class="accordion-collapse collapse" aria-labelledby="faqHeading13" data-bs-parent="#vendorFAQ">
            <div class="accordion-body">
              Our team is happy to help! Visit our <a href="/contact.php">Contact Us page</a> to reach a market coordinator directly.
            </div>
          </div>
        </div>

      </div>
    </section>

    <section class="mt-5">
      <p class="fw-bold">Still need help? <a href="/contact.php">Contact us</a> — we'll be glad to assist.</p>
    </section>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
