<template>
    <div class="ehxdo-stripe-settings-container">
        <!-- Stripe Integration Section -->
        <div class="ehxdo-section">
            <h2 class="ehxdo-section-title">Stripe</h2>
            <p class="ehxdo-section-description">
                Configuration for Stripe payment gateway integration.
            </p>
        </div>

        <!-- Integration Settings Form -->
        <div class="ehxdo-section ehxdo-section-spacing">
            <div class="ehxdo-form-container">
                <!-- Enable Toggle -->
                <div class="ehxdo-form-row">
                    <label class="ehxdo-form-label">Enabled</label>
                    <div class="ehxdo-input-wrapper">
                        <!-- <el-switch v-model="settings.enabled" size="medium"
                            :active-color="settings.enabled ? '#00A63E' : '#d1d5db'" /> -->
                        <el-switch v-model="settings.enabled" class="ml-2"
                            style="--el-switch-on-color: #00A63E; --el-switch-off-color: #d1d5db" />
                    </div>
                </div>

                <!-- Enable Checkbox -->
                <div class="ehxdo-form-row ehxdo-checkbox-row">
                    <label class="ehxdo-form-label">Enabled</label>
                    <div class="ehxdo-checkbox-wrapper">
                        <div class="ehxdo-checkbox-container">
                            <el-checkbox v-model="settings.enabledOption" class="ehxdo-checkbox-input">
                                Enable Stripe as a payment option on the platform.
                            </el-checkbox>
                        </div>
                    </div>
                </div>

                <!-- Client Key -->
                <div class="ehxdo-form-row">
                    <label class="ehxdo-form-label">Client key</label>
                    <div class="ehxdo-input-wrapper">
                        <el-input v-model="settings.clientKey" placeholder="Stripe client key"
                            class="ehxdo-input-field" />
                    </div>
                </div>

                <!-- Client Secret -->
                <div class="ehxdo-form-row">
                    <label class="ehxdo-form-label">Client secret</label>
                    <div class="ehxdo-input-wrapper">
                        <el-input v-model="settings.clientSecret" type="password" placeholder="Stripe client secret"
                            show-password class="ehxdo-input-field" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'StripeIntegrationSettings',
    setup() {
        const settings = ref({
            enabled: false,
            enabledOption: true,
            clientKey: '',
            clientSecret: ''
        });

        return {
            settings
        };
    }
};
</script>

<style scoped>
.ehxdo-stripe-settings-container {
    max-width: 100%;
    padding: 2rem;
    background-color: #ffffff;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    color: #1f2937;
}

.ehxdo-section {
    padding: 0 0 18px 0;
}

.ehxdo-section-spacing {
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.ehxdo-section-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
    line-height: 1.4;
}

.ehxdo-section-description {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
}

.ehxdo-form-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.ehxdo-form-row {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 20px;
    align-items: start;
}

.ehxdo-checkbox-row {
    align-items: flex-start;
}

.ehxdo-form-label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    padding-top: 10px;
}

.ehxdo-input-wrapper {
    width: 100%;
}

/* Element Plus overrides */
/* .ehxdo-input-wrapper :deep(.el-input__wrapper) {
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    background-color: #ffffff;
    box-shadow: none;
    transition: border-color 0.15s ease-in-out;
} */

/* .ehxdo-input-wrapper :deep(.el-input__wrapper:hover) {
    border-color: #9ca3af;
} */
/* 
.ehxdo-input-wrapper :deep(.el-input__wrapper.is-focus) {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
} */

/* .ehxdo-input-wrapper :deep(.el-input__inner) {
    font-size: 14px;
    color: #111827;
} */

/* .ehxdo-input-wrapper :deep(.el-input__inner::placeholder) {
    color: #9ca3af;
} */

/* Switch styling */
/* .ehxdo-input-wrapper :deep(.el-switch) {
    --el-switch-on-color: #3b82f6;
    --el-switch-off-color: #d1d5db;
} */
/* 
.ehxdo-input-wrapper :deep(.el-switch__core) {
    height: 28px;
    min-width: 52px;
    border: none;
} */

/* .ehxdo-input-wrapper :deep(.el-switch__action) {
    width: 22px;
    height: 22px;
} */

/* Checkbox styling */
.ehxdo-checkbox-wrapper {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.ehxdo-checkbox-container {
    display: flex;
    align-items: center;
}

.ehxdo-checkbox-input :deep(.el-checkbox__input) {
    transform: scale(1.1);
}

.ehxdo-checkbox-input :deep(.el-checkbox__inner) {
    width: 18px;
    height: 18px;
    border: 2px solid #d1d5db;
    border-radius: 3px;
}

.ehxdo-checkbox-input :deep(.el-checkbox__inner:hover) {
    border-color: #00A63E;
}

.ehxdo-checkbox-input :deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
    background-color: #00A63E;
    border-color: #00A63E;
}

.ehxdo-checkbox-input :deep(.el-checkbox__label) {
    font-size: 14px;
    color: #374151;
    font-weight: 400;
    padding-left: 10px;
}

@media (max-width: 768px) {
    .ehxdo-stripe-settings-container {
        padding: 1rem;
    }

    .ehxdo-form-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .ehxdo-form-label {
        padding-top: 0;
    }
}
</style>