<template>
    <div class="ehxdo-main-container">
        <div class="ehxdo-card">
            <!-- Account Details Section -->
            <div class="ehxdo-section ehxdo-section-border">

                <div class="ehxdo-section-header">
                    <h2 class="ehxdo-title">Account Details</h2>
                    <p class="ehxdo-description">Your users will use this information to contact you.</p>
                </div>

                <el-form
                    ref="formRef"
                    :model="settings"
                    :rules="rules"
                    label-position="top"
                >
                    <div class="ehxdo-fields-container">

                        <!-- Company Name -->
                        <div class="ehxdo-form-group">
                            <el-form-item label="Company Name" prop="company_name">
                                <el-input v-model="settings.company_name" />
                            </el-form-item>
                        </div>

                        <!-- Industry -->
                        <div class="ehxdo-form-group">
                            <el-form-item label="Industry" prop="industry">
                                <el-select v-model="settings.industry" placeholder="Select industry">
                                    <el-option label="Social" value="Social" />
                                    <el-option label="Technology" value="Technology" />
                                    <el-option label="Finance" value="Finance" />
                                    <el-option label="Healthcare" value="Healthcare" />
                                </el-select>
                            </el-form-item>
                        </div>

                        <!-- Currency -->
                        <div class="ehxdo-form-group">
                            <el-form-item label="Currency" prop="currency">
                                <el-select v-model="settings.currency" placeholder="Select currency" searchable clearable>
                                    <el-option
                                        v-for="(label, value) in currencies"
                                        :key="value"
                                        :label="label"
                                        :value="value"
                                    />
                                </el-select>
                            </el-form-item>
                        </div>

                         <!-- Currency Position -->
                        <div class="ehxdo-form-group">
                            <el-form-item label="Currency Position" prop="currency_position">
                                <el-select v-model="settings.currency_position" placeholder="Select currency position">
                                    <el-option label="Before"  value="Before" />
                                    <el-option label="After" selected value="After" />
                                </el-select>
                            </el-form-item>
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

                <el-form
                    ref="addressForm"
                    :model="settings"
                    :rules="rules"
                    label-position="top"
                >
                    <div class="ehxdo-fields-container">

                        <!-- Address Name -->
                        <div class="ehxdo-form-group">
                            <el-form-item label="Address Name" prop="address_name">
                                <el-input v-model="settings.address_name" />
                            </el-form-item>
                        </div>

                        <!-- Country -->
                        <div class="ehxdo-form-group">
                            <el-form-item label="Country or Region" prop="country">
                                <el-select v-model="settings.country" placeholder="Select country">
                                    <el-option
                                        v-for="(label, value) in countries"
                                        :key="value"
                                        :label="label"
                                        :value="value"
                                    />
                                </el-select>
                            </el-form-item>
                        </div>

                        <!-- City -->
                        <div class="ehxdo-form-group">
                            <el-form-item label="City" prop="city">
                                <el-input v-model="settings.city" />
                            </el-form-item>
                        </div>

                        <!-- Address + Postal Code -->
                        <div class="ehxdo-grid-container">
                            <div class="ehxdo-form-group ehxdo-grid-col-2">
                                <el-form-item label="Address" prop="address">
                                    <el-input v-model="settings.address" />
                                </el-form-item>
                            </div>

                            <div class="ehxdo-form-group">
                                <el-form-item label="Postal Code" prop="postal_code">
                                    <el-input v-model="settings.postal_code" />
                                </el-form-item>
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
            default: {}
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

            rules: {
                company_name: [
                    { required: true, message: "Company name is required.", trigger: "blur" },
                ],
                industry: [
                    { required: true, message: "Industry is required.", trigger: "change" },
                ],
                currency: [
                    { required: true, message: "Currency is required.", trigger: "change" },
                ],
                country: [
                    { required: true, message: "Country is required.", trigger: "change" },
                ],
            }
        };
    },
    mounted() {
        console.log('General.vue loaded', window)
    },
};
</script>

<style scoped lang="scss">
:deep(.el-form-item) {
    display: block !important;
}

.ehxdo-main-container {
    min-height: 100vh;
}

.ehxdo-card {
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 16px;
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
