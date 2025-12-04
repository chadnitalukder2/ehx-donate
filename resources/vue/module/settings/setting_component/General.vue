<template>
    <div class="ehxdo-main-container">
        <div class="ehxdo-card">
            <!-- Account Details Section -->
            <div class="ehxdo-section ehxdo-section-border">

                <div class="ehxdo-section-header">
                    <h2 class="ehxdo-title">General Settings</h2>
                    <p class="ehxdo-description">Configure basic settings for your application.</p>
                </div>

                <el-form ref="formRef" :model="settings" :rules="rules" label-position="top">
                    <div class="ehxdo-fields-container">
                        <!--  Progressbar Switch -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px;">
                                <label class="ehxdo-form-label">Progressbar</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-switch v-model="settings.progressbar" class="ml-2"
                                        style="--el-switch-on-color: #00A63E; --el-switch-off-color: #d1d5db" />
                                    <p style="margin: 0px; color: #6b7280;">Enable the progress bar to show campaign
                                        progress on your frontend view.</p>
                                </div>
                            </div>
                        </div>


                        <!-- Currency -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px;">
                                <label class="ehxdo-form-label">Currency</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-select v-model="settings.currency" placeholder="Select currency" searchable>
                                        <el-option v-for="(label, value) in currencies" :key="value" :label="label"
                                            :value="value" />
                                    </el-select>
                                </div>
                            </div>
                        </div>

                        <!-- Currency Position -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px;">
                                <label class="ehxdo-form-label">Currency Position</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-select v-model="settings.currency_position"
                                        placeholder="Select currency position">
                                        <el-option label="Before" value="Before" />
                                        <el-option label="After" selected value="After" />
                                    </el-select>
                                </div>
                            </div>
                        </div>

                        <!-- Service Fee Switch -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px; " >
                                <label class="ehxdo-form-label">Service Fee</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-switch v-model="settings.service_fee" class="ml-2"
                                        style="--el-switch-on-color: #00A63E; --el-switch-off-color: #d1d5db" />
                                    <p style="margin:0; color:#6b7280;">Turn on to apply a service fee percentage to all
                                        donations.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Service Fee Percentage (Show only when enabled) -->
                        <div class="ehxdo-form-group" v-if="settings.service_fee">
                            <div class="ehxdo-form-row" style="align-items: start;">
                                <label class="ehxdo-form-label">Percentage (%)</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-input v-model.number="settings.service_fee_percentage" type="number"
                                        placeholder="Enter percentage (e.g., 5 for 5%)" min="0" max="100" step="0.01">
                                        <template #append>%</template>
                                    </el-input>
                                    <p class="ehxdo-fee-hint">
                                        Example: If donation is {{ formatCurrency(100) }} with {{
                                            settings.service_fee_percentage || 0 }}% fee,
                                        total will be {{ formatCurrency(calculateTotalWithFee(100)) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </el-form>
            </div>

            <!-- Address Section -->
            <div class="ehxdo-section">
                <div class="ehxdo-section-header">
                    <h2 class="ehxdo-title">Address</h2>
                    <p class="ehxdo-description">This address will appear on your invoice.</p>
                </div>

                <el-form ref="addressForm" :model="settings" :rules="rules" label-position="top">
                    <div class="ehxdo-fields-container">
                        <!-- Company Name -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px; ">
                                <label class="ehxdo-form-label">Company Name</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-input v-model="settings.company_name" />
                                </div>
                            </div>
                        </div>

                        <!-- Industry -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px; ">
                                <label class="ehxdo-form-label">Industry</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-select v-model="settings.industry" placeholder="Select industry">
                                        <el-option label="Social" value="Social" />
                                        <el-option label="Technology" value="Technology" />
                                        <el-option label="Finance" value="Finance" />
                                        <el-option label="Healthcare" value="Healthcare" />
                                    </el-select>
                                </div>
                            </div>
                        </div>


                        <!-- Country -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px; ">
                                <label class="ehxdo-form-label">Country or Region</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-select v-model="settings.country" placeholder="Select country">
                                        <el-option v-for="(label, value) in countries" :key="value" :label="label"
                                            :value="value" />
                                    </el-select>
                                </div>
                            </div>
                        </div>


                        <!-- Address Name -->
                        <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px; ">
                                <label class="ehxdo-form-label">Address</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-input v-model="settings.address_name" />
                                </div>
                            </div>
                        </div>

                        <!-- City -->
                         <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row" style="margin-bottom: 30px; ">
                                <label class="ehxdo-form-label">City</label>
                                <div class="ehxdo-input-wrapper">
                                    <el-input v-model="settings.city" />
                                </div>
                            </div>
                        </div>
            

                        <!-- Address + Postal Code -->
                           <div class="ehxdo-form-group">
                            <div class="ehxdo-form-row">
                                <label class="ehxdo-form-label">Postal Code</label>
                                <div class="ehxdo-input-wrapper">
                                     <el-input v-model="settings.postal_code" />
                                </div>
                            </div>
                        </div>

                    </div>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AccountDetailsForm',
    props: {
        settings: {
            type: Object,
            default: () => ({
                service_fee: false,
                service_fee_percentage: 5, // Default 5%
                currency: 'GBP',
                currency_position: 'Before'
            })
        },
        loading: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            currencies: window.EHXDonate.currencies,
            countries: window.EHXDonate.countries,
            nonce: window.EHXDonate?.restNonce || '',
            restUrl: window.EHXDonate?.restUrl || '',
        };
    },

    methods: {
        // Calculate total with service fee
        calculateTotalWithFee(amount) {
            if (!this.settings.service_fee || !this.settings.service_fee_percentage) {
                return amount;
            }
            const fee = (amount * this.settings.service_fee_percentage) / 100;
            return (amount + fee).toFixed(2);
        },

        // Format currency display
        formatCurrency(amount) {
            const symbol = this.getCurrencySymbol();
            const formatted = parseFloat(amount).toFixed(2);
            return this.settings.currency_position === 'Before'
                ? `${symbol}${formatted}`
                : `${formatted}${symbol}`;
        },

        // Get currency symbol
        getCurrencySymbol() {
            const currencySymbols = {
                'GBP': '£',
                'USD': '$',
                'EUR': '€',
                'BDT': '৳'
            };
            return currencySymbols[this.settings.currency] || '$';
        }
    },

    mounted() {
        console.log('General.vue loaded', this.settings);
    },
};
</script>

<style scoped lang="scss">
.ehxdo-form-row {
    display: grid;
    grid-template-columns: 160px 1fr;
    gap: 20px;
    align-items: center;
    

    .ehxdo-form-label {
        color: #606266;
        font-size: 14px;
        font-weight: 500;
    }
}

.ehxdo-fee-hint {
    font-size: 0.8125rem;
    color: #6b7280;
    padding: 0.75rem;
    background-color: #F8F9FC;
    border-radius: 6px;
    border-left: 3px solid #00A63E;
}

:deep(.el-input-group__append) {
    background-color: #F8F9FC;
    color: #6b7280;
    margin-left: -12px;
    font-weight: 500;
    box-shadow: none;
    border-radius: 0px 10px 10px 0px;
    ;
    border: 1px solid #E8EAF1;
}

:deep(.el-form-item) {
    display: block !important;
}

.ehxdo-main-container {
    min-height: 100vh;
}

.ehxdo-card {
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 0px 16px 16px 0px;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.ehxdo-section {
    padding: 2rem;
}

.ehxdo-section-border {
    border-bottom: 1px solid #e5e7eb;
}

.ehxdo-section-header {
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 20px;
    padding-bottom: 10px;
}

.ehxdo-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.ehxdo-description {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.ehxdo-fields-container {
    display: flex;
    flex-direction: column;
}

.ehxdo-currency-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.ehxdo-form-group {
    display: flex;
    flex-direction: column;
}

.ehxdo-grid-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 768px) {
    .ehxdo-grid-container {
        grid-template-columns: repeat(3, 1fr);
    }

    .ehxdo-grid-col-2 {
        grid-column: span 2;
    }
}

:deep(.el-select__wrapper) {
    height: 44px !important;
}
</style>
