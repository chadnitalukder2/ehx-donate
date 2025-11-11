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