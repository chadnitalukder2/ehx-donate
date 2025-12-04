<template>
    <div class="ehxdo-recaptcha-container">
        <div class="ehxdo-settings-card">
            <h1 class="ehxdo-title">reCAPTCHA Settings</h1>

            <div class="ehxdo-mode-section">
                <h2 class="ehxdo-section-title">
                    reCaptcha Mode
                    <span class="ehxdo-info-icon" title="Select reCAPTCHA mode">?</span>
                </h2>

                <div class="ehxdo-mode-options">
                    <div :class="['ehxdo-mode-option', { 'ehxdo-selected': selectedMode === 'disabled' }]"
                        @click="selectedMode = 'disabled'">
                        <div class="ehxdo-radio"></div>
                        <span class="ehxdo-mode-label">Disable reCAPTCHA</span>
                    </div>

                    <div :class="['ehxdo-mode-option', { 'ehxdo-selected': selectedMode === 'visible' }]"
                        @click="selectedMode = 'visible'">
                        <div class="ehxdo-radio"></div>
                        <span class="ehxdo-mode-label">Visible reCAPTCHA (V2)</span>
                    </div>

                    <div :class="['ehxdo-mode-option', { 'ehxdo-selected': selectedMode === 'invisible' }]"
                        @click="selectedMode = 'invisible'">
                        <div class="ehxdo-radio"></div>
                        <span class="ehxdo-mode-label">Invisible reCAPTCHA (v3)</span>
                    </div>
                </div>
            </div>

            <div class="ehxdo-keys-section">
                <div class="ehxdo-rechaptcha-header">
                    <h2 class="ehxdo-section-title" style="margin-bottom: 6px;">
                        reCAPTCHA Keys
                        <span class="ehxdo-info-icon" title="API keys for reCAPTCHA">?</span>
                    </h2>

                    <div class="ehxdo-keys-info">
                        You may find the API keys from here:
                        <a href="https://www.google.com/recaptcha/admin" class="ehxdo-link" target="_blank"
                            rel="noopener noreferrer">
                            Google reCaptcha Site
                        </a>
                        . Please select appropriate reCAPTCHA version when creating the API key
                    </div>
                </div>


                <div class="ehxdo-form-row">
                    <div class="ehxdo-form-group" style="margin-bottom: 25px;">
                        <label class="ehxdo-label">Site Key</label>
                        <el-input v-model="settings.siteKey" placeholder="reCAPTCHA Site Key" />
                    </div>

                    <div class="ehxdo-form-group">
                        <label class="ehxdo-label">Secret key</label>
                        <el-input v-model="settings.secretKey" placeholder="reCAPTCHA Secret Key" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
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
            selectedMode: this.settings.mode || 'disabled'
        };
    },

    watch: {
        selectedMode(newVal) {
            this.settings.mode = newVal;
        }
    }
};
</script>

<style scoped>
.ehxdo-recaptcha-container {
    font-family: Inter Tight, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.ehxdo-settings-card {
    background: white;
    padding: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ehxdo-title {
    font-size: 18px;
    font-weight: 500;
    color: #303133;
    margin-bottom: 20px;
    padding-bottom: 20px;
    margin-top: 0px;
    border-bottom: 1px solid #EBEEF5;
}

.ehxdo-section-title {
    font-size: 16px;
    font-weight: 500;
    color: #5d5d5d;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ehxdo-info-icon {
    width: 20px;
    height: 20px;
    background: #e0e0e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #666;
    cursor: help;
}

.ehxdo-mode-options {
    display: flex;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 32px;
}

.ehxdo-mode-label {
    font-size: 12px;
    color: #3b3c3e;
    font-weight: 500;
}

.ehxdo-mode-option {
    padding: 8px 12px;
    border: 1.5px dashed #e0e0e0;
    border-radius: 10px;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ehxdo-mode-option:hover {
    border-color: #067a3b;
    background: #f6fffa;
}

.ehxdo-mode-option.ehxdo-selected {
    border-color: #067a3b;
    background: #f6fffa;
}

.ehxdo-radio {
    width: 14px;
    height: 14px;
    border: 1.5px solid #d0d0d0;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s;
}

.ehxdo-mode-option.ehxdo-selected .ehxdo-radio {
    border-color: #067a3b;
}

.ehxdo-mode-option.ehxdo-selected .ehxdo-radio::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: #067a3b;
    border-radius: 50%;
}

.ehxdo-keys-section {
    border: 1.5px solid #e0e0e0;
    border-radius: 16px;
    margin-top: 24px;
}

.ehxdo-rechaptcha-header {
    padding: 20px;
    border-bottom: 1.5px solid #e0e0e0;
}

.ehxdo-keys-info {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
}

.ehxdo-link {
    color: #1890ff;
    text-decoration: none;
    transition: all .3s;
}

.ehxdo-link:hover {
    text-decoration: underline;
}

.ehxdo-form-row {
    padding: 20px;
}

.ehxdo-form-group {
    display: grid;
    grid-template-columns: 90px 1fr;
    align-items: center;
    gap: 12px
}

.ehxdo-label {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.ehxdo-input {
    padding: 12px 16px;
    border: 1px solid #d9d9d9;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
    background: white;
}

.ehxdo-input:focus {
    outline: none;
    border-color: #067a3b;
    box-shadow: 0 0 0 2px rgba(255, 127, 39, 0.1);
}

.ehxdo-input::placeholder {
    color: #bbb;
}

.ehxdo-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 32px;
}

.ehxdo-save-button {
    padding: 12px 32px;
    background: #067a3b;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.ehxdo-save-button:hover {
    background: #ff6b0d;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 127, 39, 0.3);
}

.ehxdo-save-button:active {
    transform: translateY(0);
}

@media (max-width: 768px) {
    .ehxdo-mode-options {
        flex-direction: column;
    }

    .ehxdo-form-row {
        grid-template-columns: 1fr;
    }
}
</style>