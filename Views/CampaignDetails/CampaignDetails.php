<?php
// Configuration
$campaign = [
    'title' => 'Food Drive 2024',
    'raised' => 15200,
    'goal' => 20000,
    'donors' => 156,
    'end_date' => '12/31/2024',
    'image' => 'https://cdn.optinmonster.com/wp-content/uploads/2023/03/how-to-create-an-email-campaign-facebook.png',
    'description' => [
        'Feeding hungry families and ensuring no one goes to bed hungry.',
        'Your generous donation will help us continue our mission to make a real difference in the lives of those who need it most. Every contribution, no matter the size, brings us closer to our goal and helps create lasting positive change.',
        'We believe in transparency and accountability. Regular updates will be shared with all donors to show exactly how funds are being utilized to maximize impact.'
    ]
];

// Calculate progress
$progress = round(($campaign['raised'] / $campaign['goal']) * 100);

// Donation amounts
$preset_amounts = [10, 25, 50, 100, 250];
$default_amount = 50;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process donation
    $donation_data = [
        'amount' => $_POST['amount'] ?? $default_amount,
        'type' => $_POST['donation_type'] ?? 'one-time',
        'title' => $_POST['title'] ?? '',
        'first_name' => $_POST['first_name'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'address_line_1' => $_POST['address_line_1'] ?? '',
        'address_line_2' => $_POST['address_line_2'] ?? '',
        'city' => $_POST['city'] ?? '',
        'state' => $_POST['state'] ?? '',
        'country' => $_POST['country'] ?? '',
        'post_code' => $_POST['post_code'] ?? '',
        'gift_aid' => isset($_POST['gift_aid']) ? true : false
    ];

    // Here you would process the payment and save to database
    // For now, just redirect to a thank you page
    // header('Location: thank-you.php');
    // exit;
}
?>
<div class="ehxdo_campaign_list_wrapper">
    <a href="campaigns.php" class="ehxdo-back-link">
        <span class="ehxdo-back-arrow">←</span> Back to Campaigns
    </a>

    <div class="ehxdo-campaign_details_container">
        <!-- Left Section -->

        <div class="ehxdo-left-section">


            <div class="ehxdo-campaign-header-image">
                <img src="<?php echo htmlspecialchars($campaign['image']); ?>"
                    alt="<?php echo htmlspecialchars($campaign['title']); ?>"
                    class="ehxdo-campaign-image">
            </div>
            <div class="ehxdo_details_campaign_list">


                <div class="ehxdo_campaign_title_section">
                    <h1 class="ehxdo-campaign-title"><?php echo htmlspecialchars($campaign['title']); ?></h1>
                </div>

                <div class="ehxdo-stats-container">
                    <div class="ehxdo-stat-item ehxdo-stat-raised">
                        <div class="ehxdo-stat-value">$<?php echo number_format($campaign['raised']); ?></div>
                        <div class="ehxdo-stat-label">Raised</div>
                    </div>
                    <div class="ehxdo-stat-item ehxdo-stat-goal">
                        <div class="ehxdo-stat-value">$<?php echo number_format($campaign['goal']); ?></div>
                        <div class="ehxdo-stat-label">Goal</div>
                    </div>
                    <div class="ehxdo-stat-item ehxdo-stat-donors">
                        <div class="ehxdo-stat-value"><?php echo $campaign['donors']; ?></div>
                        <div class="ehxdo-stat-label">Donors</div>
                    </div>
                </div>

                <div class="ehxdo-progress-container">
                    <div class="ehxdo-progress-label">
                        <span>Campaign Progress</span>
                        <span class="ehxdo-progress-percent"><?php echo $progress; ?>%</span>
                    </div>
                    <div class="ehxdo-progress-bar">
                        <div class="ehxdo-progress-fill" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>

                <div class="ehxdo-about-section">
                    <h2 class="ehxdo-about-title">About This Campaign</h2>
                    <?php foreach ($campaign['description'] as $paragraph): ?>
                        <p class="ehxdo-about-text"><?php echo htmlspecialchars($paragraph); ?></p>
                    <?php endforeach; ?>
                </div>

                <div class="ehxdo-campaign-meta">
                    <span class="ehxdo-meta-item">
                        <span class="ehxdo-meta-icon">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.3335 1.33337V4.00004" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10.6665 1.33337V4.00004" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12.6667 2.66663H3.33333C2.59695 2.66663 2 3.26358 2 3.99996V13.3333C2 14.0697 2.59695 14.6666 3.33333 14.6666H12.6667C13.403 14.6666 14 14.0697 14 13.3333V3.99996C14 3.26358 13.403 2.66663 12.6667 2.66663Z" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2 6.66663H14" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span> Ends <?php echo htmlspecialchars($campaign['end_date']); ?>
                    </span>
                    <span class="ehxdo-meta-item">
                        <span class="ehxdo-meta-icon">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.6668 14V12.6667C10.6668 11.9594 10.3859 11.2811 9.88578 10.781C9.38568 10.281 8.70741 10 8.00016 10H4.00016C3.29292 10 2.61464 10.281 2.11454 10.781C1.61445 11.2811 1.3335 11.9594 1.3335 12.6667V14" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10.6665 2.08533C11.2383 2.23357 11.7448 2.5675 12.1063 3.0347C12.4678 3.5019 12.664 4.07592 12.664 4.66666C12.664 5.2574 12.4678 5.83142 12.1063 6.29862C11.7448 6.76582 11.2383 7.09975 10.6665 7.24799" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M14.6665 14V12.6667C14.6661 12.0758 14.4694 11.5019 14.1074 11.0349C13.7454 10.5679 13.2386 10.2344 12.6665 10.0867" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.00016 7.33333C7.47292 7.33333 8.66683 6.13943 8.66683 4.66667C8.66683 3.19391 7.47292 2 6.00016 2C4.5274 2 3.3335 3.19391 3.3335 4.66667C3.3335 6.13943 4.5274 7.33333 6.00016 7.33333Z" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </span> <?php echo $campaign['donors']; ?> Supporters
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="ehxdo-right-section">

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="ehxdo-donation-form">
                <!-- Section 1: Make a Donation -->
                <div class="ehxdo-donation-card" id="ehxdo-section-1">
                    <h3 class="ehxdo-card-title">Make a Donation</h3>

                    <!-- <div class="ehxdo-donation-type">
                        <label for="donation_type" class="ehxdo-label">Donation Type</label>
                        <select id="donation_type" name="donation_type" class="ehxdo-select-type">
                            <option value="one-time" selected>One-time</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div> -->

                    <div class="ehxdo-donation-type">
                        <label for="donation_type" class="ehxdo-label">Donation Type</label>
                        <div class="custom-select">
                            <div class="select-display">One-time</div>
                            <div class="select-options">
                                <div class="select-option selected" data-value="one-time">One-time</div>
                                <div class="select-option" data-value="weekly">Weekly</div>
                                <div class="select-option" data-value="monthly">Monthly</div>
                                <div class="select-option" data-value="quarterly">Quarterly</div>
                                <div class="select-option" data-value="yearly">Yearly</div>
                            </div>
                        </div>
                        <input type="hidden" name="donation_type" id="donation_type" value="one-time">
                    </div>

                    <div class="ehxdo-amount-section">
                        <label class="ehxdo-label">Select Amount</label>
                        <input type="hidden" name="amount" id="ehxdo-selected-amount" value="<?php echo $default_amount; ?>">
                        <div class="ehxdo-amount-grid">
                            <?php foreach ($preset_amounts as $amount): ?>
                                <button type="button"
                                    class="ehxdo-amount-btn <?php echo $amount === $default_amount ? 'ehxdo-selected' : ''; ?>"
                                    data-amount="<?php echo $amount; ?>">
                                    $<?php echo $amount; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <input type="text"
                            placeholder="Custom amount"
                            class="ehxdo-custom-amount"
                            id="ehxdo-custom-amount">
                    </div>

                    <button type="button" class="ehxdo-donate-btn" id="ehxdo-donate-btn">


                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.3335 3.33331H2.66683C1.93045 3.33331 1.3335 3.93027 1.3335 4.66665V11.3333C1.3335 12.0697 1.93045 12.6666 2.66683 12.6666H13.3335C14.0699 12.6666 14.6668 12.0697 14.6668 11.3333V4.66665C14.6668 3.93027 14.0699 3.33331 13.3335 3.33331Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M1.3335 6.66669H14.6668" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>


                        Donate $<?php echo $default_amount; ?>
                    </button>

                    <p class="ehxdo-disclaimer">Protected by Google reCAPTCHA. Issues payment via Stripe.</p>

                </div>

                <!-- Section 2: Personal Information -->
                <div class="ehxdo-donation-card ehxdo-hidden" id="ehxdo-section-2">
                    <h3 class="ehxdo-card-title">Personal Information</h3>

                    <div class="ehxdo-form">
                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Title *</label>
                            <select name="title" class="ehxdo-input" required>
                                <option value="">Select title</option>
                                <option value="mr">Mr.</option>
                                <option value="mrs">Mrs.</option>
                                <option value="ms">Ms.</option>
                                <option value="dr">Dr.</option>
                            </select>
                        </div>

                        <div class="ehxdo-form-row">
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">First Name *</label>
                                <input type="text" name="first_name" class="ehxdo-input" required>
                            </div>
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Last Name *</label>
                                <input type="text" name="last_name" class="ehxdo-input" required>
                            </div>
                        </div>


                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Email Address *</label>
                            <input type="email" name="email" class="ehxdo-input" required>
                        </div>
                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Phone Number *</label>
                            <input type="tel" name="phone" class="ehxdo-input" required>
                        </div>


                        <div class="ehxdo-gift-aid">
                            <!-- <label class="ehxdo-label">Gift Aid</label> -->
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="gift_aid" value="1" style="cursor: pointer;">
                                <span style="font-size: 0.875rem;">Gift Aid</span>
                            </label>
                        </div>


                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Address line 1</label>
                            <input type="text" name="address_line_1" class="ehxdo-input">
                        </div>
                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Address line 2</label>
                            <input type="text" name="address_line_2" class="ehxdo-input">
                        </div>


                        <div class="ehxdo-form-row">
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">City</label>
                                <input type="text" name="city" class="ehxdo-input">
                            </div>
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">State</label>
                                <input type="text" name="state" class="ehxdo-input">
                            </div>
                        </div>

                        <div class="ehxdo-form-row">
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Country</label>
                                <input type="text" name="country" class="ehxdo-input">
                            </div>
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Post Code</label>
                                <input type="text" name="post_code" class="ehxdo-input">
                            </div>
                        </div>

                        <div class="ehxdo-summary">
                            <div class="ehxdo-summary-row">
                                <span>Due Directly from You:</span>
                                <span class="ehxdo-amount" id="ehxdo-summary-amount">£<?php echo $default_amount; ?>.00</span>
                            </div>
                            <div class="ehxdo-summary-row ehxdo-highlight">
                                <span>Your Contribution with Gift Aid:</span>
                                <span class="ehxdo-amount" id="ehxdo-summary-total">£<?php echo $default_amount; ?>.00</span>
                            </div>
                        </div>

                        <div class="ehxdo-section-nav">
                            <button type="button" class="ehxdo-nav-btn ehxdo-prev">Previous</button>
                            <button type="submit" class="ehxdo-nav-btn ehxdo-submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const customSelect = document.querySelector('.custom-select');
    const selectDisplay = customSelect.querySelector('.select-display');
    const selectOptions = customSelect.querySelector('.select-options');
    const options = customSelect.querySelectorAll('.select-option');
    const hiddenInput = document.getElementById('donation_type');
    const displayValue = document.getElementById('display-value');

    // Toggle dropdown
    selectDisplay.addEventListener('click', function(e) {
        e.stopPropagation();
        selectOptions.classList.toggle('show');
        this.classList.toggle('active');
    });

    // Select option
    options.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            const text = this.textContent;

            // Update display
            selectDisplay.textContent = text;
            hiddenInput.value = value;
            displayValue.textContent = value;

            // Update selected state
            options.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            // Close dropdown
            selectOptions.classList.remove('show');
            selectDisplay.classList.remove('active');
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!customSelect.contains(e.target)) {
            selectOptions.classList.remove('show');
            selectDisplay.classList.remove('active');
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            selectOptions.classList.remove('show');
            selectDisplay.classList.remove('active');
        }
    });
</script>

<!-- <script>
       // Section Navigation
        const sections = document.querySelectorAll('.ehxdo-donation-card');
        let currentSection = 0;

        function showSection(index) {
            sections.forEach((section, i) => {
                if (i === index) {
                    section.classList.remove('ehxdo-hidden');
                } else {
                    section.classList.add('ehxdo-hidden');
                }
            });

            // Update button states
            const prevBtns = document.querySelectorAll('.ehxdo-prev');
            const nextBtns = document.querySelectorAll('.ehxdo-next');
            
            prevBtns.forEach(btn => {
                btn.disabled = index === 0;
            });

            nextBtns.forEach(btn => {
                btn.style.display = index === sections.length - 1 ? 'none' : 'inline-block';
            });
        }

        document.querySelectorAll('.ehxdo-next').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentSection < sections.length - 1) {
                    currentSection++;
                    showSection(currentSection);
                }
            });
        });

        document.querySelectorAll('.ehxdo-prev').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentSection > 0) {
                    currentSection--;
                    showSection(currentSection);
                }
            });
        });

        // Amount selection
        const amountInput = document.getElementById('ehxdo-selected-amount');
        const donateBtn = document.getElementById('ehxdo-donate-btn');
        const customAmountInput = document.getElementById('ehxdo-custom-amount');
        const summaryAmount = document.getElementById('ehxdo-summary-amount');
        const summaryTotal = document.getElementById('ehxdo-summary-total');

        function updateAmount(amount) {
            amountInput.value = amount;
            donateBtn.textContent = `💳 Donate $${amount}`;
            summaryAmount.textContent = `£${amount}.00`;
            summaryTotal.textContent = `£${amount}.00`;
        }

        document.querySelectorAll('.ehxdo-amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.ehxdo-amount-btn').forEach(b => b.classList.remove('ehxdo-selected'));
                this.classList.add('ehxdo-selected');
                customAmountInput.value = '';
                
                const amount = this.getAttribute('data-amount');
                updateAmount(amount);
            });
        });

        customAmountInput.addEventListener('input', function() {
            const customAmount = parseFloat(this.value) || 0;
            if (customAmount > 0) {
                document.querySelectorAll('.ehxdo-amount-btn').forEach(b => b.classList.remove('ehxdo-selected'));
                updateAmount(customAmount);
            }
        });

        // Donate button click (goes to next section)
        donateBtn.addEventListener('click', () => {
            if (currentSection < sections.length - 1) {
                currentSection++;
                showSection(currentSection);
            }
        });
</script> -->