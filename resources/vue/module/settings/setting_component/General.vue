<template>
    <div class="ehxdo-main-container">
        <div class="ehxdo-card">
            <!-- Account Details Section -->
            <div class="ehxdo-section ehxdo-section-border">

                <div class="ehxdo-section-header">
                    <h2 class="ehxdo-title">Account Details</h2>
                    <p class="ehxdo-description">Your users will use this information to contact you.</p>
                </div>

                <div class="ehxdo-fields-container">
                    <!-- Company Name -->
                    <div class="ehxdo-form-group">
                        <el-form-item label="Company Name" prop="company_name" required>
                            <el-input v-model="settings.company_name" />
                        </el-form-item>

                    </div>

                    <!-- Industry -->
                    <div class="ehxdo-form-group">
                        <div class="ehxdo-select-wrapper">
                            <el-form-item label="Industry" prop="industry" required>
                                <el-select v-model="settings.industry" placeholder="Select industry">
                                    <el-option label="Social" value="Social" />
                                    <el-option label="Technology" value="Technology" />
                                    <el-option label="Finance" value="Finance" />
                                    <el-option label="Healthcare" value="Healthcare" />
                                </el-select>
                            </el-form-item>

                        </div>
                    </div>

                    <!-- Currency -->
                    <div class="ehxdo-form-group">
                        <el-form-item label="Currency" prop="currency" required>
                            <el-select v-model="settings.currency" placeholder="Select currency">
                                <el-option label="Social" value="Social" />
                                <el-option label="Technology" value="Technology" />
                                <el-option label="Finance" value="Finance" />
                                <el-option label="Healthcare" value="Healthcare" />
                            </el-select>
                        </el-form-item>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="ehxdo-section">
                <div class="ehxdo-section-header">
                    <h2 class="ehxdo-title">Address</h2>
                    <p class="ehxdo-description">This address will appear on your invoice.</p>
                </div>

                <div class="ehxdo-fields-container">
                    <!-- Address Name -->
                    <div class="ehxdo-form-group">
                        <el-form-item label="Address Name " prop="address_name">
                            <el-input v-model="settings.address_name" />
                        </el-form-item>
                    </div>

                    <!-- Country or Region -->
                    <div class="ehxdo-form-group">
                        <el-form-item label="Country or Region" prop="country">
                            <el-select v-model="settings.country" placeholder="Select industry">
                                <el-option label="United States" value="United States" />
                                <el-option label="United Kingdom" value="United Kingdom" />
                                <el-option label="Canada" value="Canada" />
                                <el-option label="Australia" value="Australia" />
                            </el-select>
                        </el-form-item>
                    </div>

                    <!-- City -->
                    <div class="ehxdo-form-group">
                        <el-form-item label="City" prop="city">
                            <el-input v-model="settings.city" />
                        </el-form-item>

                    </div>

                    <!-- Address and Postal Code -->
                    <div class="ehxdo-grid-container">
                        <div class="ehxdo-form-group ehxdo-grid-col-2">
                            <el-form-item label="Address" prop="address">
                                <el-input v-model="settings.address" />
                            </el-form-item>
                        </div>

                        <div class="ehxdo-form-group">
                            <el-form-item label="Postal Code" prop="code">
                                <el-input v-model="settings.postal_code" />
                            </el-form-item>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'AccountDetailsForm',
    data() {
        return {
            settings: {},
            submitting: false,
            statusActive: false,
            statusPending: false,
            statusComplete: false,
            nonce: window.EHXDonate?.restNonce || '',
            restUrl: window.EHXDonate?.restUrl || '',
            tagOptions: [],
            categoryOptions: [],

            rules: {
                title: [
                    { required: true, message: "Title is required.", trigger: "blur" },
                ],
                goal_amount: [
                    { required: true, message: "Goal amount is required.", trigger: "blur" },
                    { type: "number", min: 1, message: "Goal amount must be greater than 0", trigger: "blur" },
                ],
                short_description: [
                    { required: true, message: "Short Description is required. ", trigger: "blur" },
                ],

            },
        };
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
    border-radius: 0.5rem;
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

.ehxdo-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 400;
    color: #374151;
    margin-bottom: 0.5rem;
}

.ehxdo-required {
    color: #ef4444;
}

.ehxdo-input {
    width: 100%;
    padding: 0.625rem 0.75rem;
    font-size: 0.9375rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    background-color: #ffffff;
    color: #111827;
    outline: none;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    box-sizing: border-box;
}

.ehxdo-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.ehxdo-select-wrapper {
    position: relative;
    width: 100%;
}

.ehxdo-select {
    width: 100%;
    padding: 0.625rem 2.5rem 0.625rem 0.75rem;
    font-size: 0.9375rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    background-color: #ffffff;
    color: #111827;
    outline: none;
    appearance: none;
    cursor: pointer;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    box-sizing: border-box;
}

.ehxdo-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.ehxdo-select-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    pointer-events: none;
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
</style>