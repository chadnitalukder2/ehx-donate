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
                        <label class="ehxdo-form-label">
                            Test Client Key
                            <a href="https://dashboard.stripe.com/test/apikeys" target="_blank" rel="noopener noreferrer" 
                               class="ehxdo-help-link" title="Get your test API keys">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </a>
                        </label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.clientKey" placeholder="pk_test_..."
                                class="ehxdo-input-field" autocomplete="off"
                                :disabled="settings.stripe.enabled === 'no'" />
                            <p class="ehxdo-field-hint">
                                Starts with <code>pk_test_</code>. 
                                <a href="https://dashboard.stripe.com/test/apikeys" target="_blank" rel="noopener noreferrer">
                                    Find it here
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="ehxdo-form-row">
                        <label class="ehxdo-form-label">
                            Test Client Secret
                            <a href="https://dashboard.stripe.com/test/apikeys" target="_blank" rel="noopener noreferrer" 
                               class="ehxdo-help-link" title="Get your test secret key">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </a>
                        </label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.clientSecret" type="password"
                                placeholder="sk_test_..." show-password class="ehxdo-input-field"
                                autocomplete="new-password" :disabled="settings.stripe.enabled === 'no'" />
                            <p class="ehxdo-field-hint">
                                Starts with <code>sk_test_</code>. Keep this secret!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Live Credentials -->
                <div v-if="settings.stripe.mode === 'live'" class="ehxdo_live_mode" style="padding-top: 20px;">
                    <div class="ehxdo-form-row">
                        <label class="ehxdo-form-label">
                            Live Client Key
                            <a href="https://dashboard.stripe.com/apikeys" target="_blank" rel="noopener noreferrer" 
                               class="ehxdo-help-link" title="Get your live API keys">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </a>
                        </label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.live_clientKey" placeholder="pk_live_..."
                                class="ehxdo-input-field" autocomplete="off"
                                :disabled="settings.stripe.enabled === 'no'" />
                            <p class="ehxdo-field-hint">
                                Starts with <code>pk_live_</code>. 
                                <a href="https://dashboard.stripe.com/apikeys" target="_blank" rel="noopener noreferrer">
                                    Find it here
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="ehxdo-form-row">
                        <label class="ehxdo-form-label">
                            Live Client Secret
                            <a href="https://dashboard.stripe.com/apikeys" target="_blank" rel="noopener noreferrer" 
                               class="ehxdo-help-link" title="Get your live secret key">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </a>
                        </label>
                        <div class="ehxdo-input-wrapper">
                            <el-input v-model="settings.stripe.live_clientSecret" type="password"
                                placeholder="sk_live_..." show-password class="ehxdo-input-field"
                                autocomplete="new-password" :disabled="settings.stripe.enabled === 'no'" />
                            <p class="ehxdo-field-hint">
                                Starts with <code>sk_live_</code>. Keep this secret!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ehxdo-form-row">
                <label class="ehxdo-form-label">Webhook URL</label>
                <div class="ehxdo-input-wrapper">
                    <el-alert title="" type="info" :closable="false">
                        {{ webhookUrl }}
                    </el-alert>
                    <p class="ehxdo-checkbox-description">
                        This is the URL you need to configure in your Stripe dashboard to receive webhook events.
                        <a href="https://dashboard.stripe.com/webhooks" target="_blank" rel="noopener noreferrer">
                            Configure webhooks →
                        </a>
                    </p>
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
                    mode: 'test',
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
        },
    },
    data() {
        return {
            webhookUrl: window.EHXDonate?.stripe_webhook || ''
        };
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

.ehxdo-section-description {
    margin: 8px 0 0 0;
    color: #6b7280;
    font-size: 14px;
}

.ehxdo-dashboard-link {
    display: inline-block;
    margin-left: 12px;
    color: #635bff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
    
    &:hover {
        color: #0a2540;
        text-decoration: underline;
    }
}

.ehxdo-form-row {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    margin-bottom: 15px;
    
    .ehxdo-form-label {
        width: 120px;
        display: flex;
        align-items: center;
        gap: 6px;
        padding-top: 8px;
    }
    
    .ehxdo-input-wrapper {
        width: 75%;
    }
}

.ehxdo-help-link {
    color: #6b7280;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: color 0.2s;
    
    &:hover {
        color: #635bff;
    }
    
    svg {
        vertical-align: middle;
    }
}

.ehxdo-field-hint {
    margin: 6px 0 0 0;
    font-size: 13px;
    color: #6b7280;
    
    code {
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 12px;
        color: #1f2937;
    }
    
    a {
        color: #635bff;
        text-decoration: none;
        
        &:hover {
            text-decoration: underline;
        }
    }
}

.ehxdo-checkbox-description {
    margin: 8px 0 0 0;
    font-size: 13px;
    color: #6b7280;
    
    a {
        color: #635bff;
        text-decoration: none;
        font-weight: 500;
        
        &:hover {
            text-decoration: underline;
        }
    }
}
</style>