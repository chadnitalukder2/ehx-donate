<template>
    <div class="ehxdo-stripe-settings-container">
        <!-- Stripe Integration Section -->
        <div class="ehxdo-section-spacing" style="padding: 32px;">
            <div class="ehxdo-section-header" style="margin-bottom: 20px;">
                <h2 class="ehxdo-section-title">Stripe</h2>
                <p class="ehxdo-section-description">
                    Configuration for Stripe payment gateway integration.
                </p>
            </div>

            <!-- Integration Settings Form -->
            <div class="ehxdo-form-container">
                <!-- Enable Toggle -->
                <div class="ehxdo-form-row">
                    <label class="ehxdo-form-label">Enable Stripe</label>
                    <div class="ehxdo-input-wrapper">
                        <el-switch v-model="settings.stripe.enabled" active-value="yes" inactive-value="no" class="ml-2"
                            style="--el-switch-on-color: #00A63E; --el-switch-off-color: #d1d5db" />
                    </div>
                </div>

                <!-- Mode Selection -->
                <div class="ehxdo-form-row">
                    <label class="ehxdo-form-label">Mode</label>
                    <div class="ehxdo-input-wrapper">
                        <el-radio-group v-model="settings.stripe.mode" :disabled="settings.stripe.enabled === 'no'">
                            <el-radio label="test">Test</el-radio>
                            <el-radio label="live">Live</el-radio>
                        </el-radio-group>
                    </div>
                </div>

                <!-- Test Credentials -->
                <div v-if="settings.stripe.mode === 'test'" class="ehxdo_live_mode" style="padding-top: 20px;">
                    <div class="ehxdo-form-row">
                        <label class="ehxdo-form-label">Test Client Key</label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.clientKey" placeholder="Test client key"
                                class="ehxdo-input-field" autocomplete="off"
                                :disabled="settings.stripe.enabled === 'no'" />
                        </div>
                    </div>

                    <div class="ehxdo-form-row">
                        <label class="ehxdo-form-label">Test Client Secret</label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.clientSecret" type="password"
                                placeholder="Test client secret" show-password class="ehxdo-input-field"
                                autocomplete="new-password" :disabled="settings.stripe.enabled === 'no'" />
                        </div>
                    </div>
                </div>

                <!-- Live Credentials -->
                <div v-if="settings.stripe.mode === 'live'" class="ehxdo_live_mode" style="padding-top: 20px;">
                    <div class="ehxdo-form-row">
                        <label class="ehxdo-form-label">Live Client Key</label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.live_clientKey" placeholder="Live client key"
                                class="ehxdo-input-field" autocomplete="off"
                                :disabled="settings.stripe.enabled === 'no'" />
                        </div>
                    </div>

                    <div class="ehxdo-form-row">
                        <label class="ehxdo-form-label">Live Client Secret</label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.live_clientSecret" type="password"
                                placeholder="Live client secret" show-password class="ehxdo-input-field"
                                autocomplete="new-password" :disabled="settings.stripe.enabled === 'no'" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'StripeIntegrationSettings',
    props: {
        settings: {
            type: Object,
            default: () => ({
                stripe: {
                    mode: 'test', // test or live
                    clientKey: '',
                    clientSecret: '',
                    live_clientKey: '',
                    live_clientSecret: '',
                    enabled: 'no',
                }
            })
        },
        loading: {
            type: Boolean,
            default: false
        }
    }
};
</script>

<style lang="scss" scoped>
.ehxdo-section-header {
    border-bottom: 1px solid #e5e7eb;

    .ehxdo-section-title {
        margin: 0px;
    }

}
 .ehxdo-form-row {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 15px;
        .ehxdo-form-label{
            width: 120px;
        }
        .ehxdo-input-wrapper{
            width: 75%
        }
    }
</style>