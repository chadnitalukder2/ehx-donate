<template>
    <div class="ehxdo_settings_page_container">
        <div class="ehxdo_settings_header">
            <div class="ehxdo_settings_title">
                <h3>Settings</h3>
            </div>
            <div class="ehxdo_settings_right">
                <el-button type="info" @click="handleCancel" :disabled="saving">Cancel</el-button>
                <el-button 
                    type="primary" 
                    @click="handleSave" 
                    :loading="saving"
                    :disabled="saving"
                >
                    {{ saving ? 'Saving...' : 'Save Change' }}
                </el-button>
            </div>
        </div>
        <div class="ehxdo_settings_main_content">
            <div class="ehxdo_settings_sidebar">
                <SettingSidebar />
            </div>
            <div class="ehxdo_settings_details">
                <RouterView @update-settings="updateSettings" :settings="settings" :loading="loading" />
            </div>
        </div>
    </div>
</template>

<script>
import { ElMessage } from 'element-plus';
import SettingSidebar from './setting_component/SettingSidebar.vue';

export default {
    name: 'SettingsIndex',
    components: {
        SettingSidebar
    },
    data() {
        return {
            settings: {},
            originalSettings: {},
            saving: false,
            loading: false,
            nonce: window.EHXDonate?.restNonce || '',
            restUrl: window.EHXDonate?.restUrl || ''
        };
    },
    mounted() {
        this.loadSettings();
    },
    methods: {
        async loadSettings() {
            this.loading = true;
            try {
                const response = await fetch(`${this.restUrl}ehxdonate/v1/settings`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    this.settings = { ...result.data };
                    this.originalSettings = JSON.parse(JSON.stringify(result.data));
                }
            } catch (error) {
                console.error('Error loading settings:', error);
                ElMessage.error('Failed to load settings');
            } finally {
                this.loading = false;
            }
        },

        updateSettings(newSettings) {
            this.settings = { ...this.settings, ...newSettings };
        },

        async handleSave() {
            this.saving = true;
            try {
                const response = await fetch(`${this.restUrl}ehxdonate/v1/settings`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    },
                    body: JSON.stringify(this.settings)
                });

                const result = await response.json();
                
                if (result.success) {
                    ElMessage.success(result.message || 'Settings saved successfully');
                    this.originalSettings = JSON.parse(JSON.stringify(this.settings));
                } else {
                    ElMessage.error(result.message || 'Failed to save settings');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                ElMessage.error('An error occurred while saving settings');
            } finally {
                this.saving = false;
            }
        },

        handleCancel() {
            this.settings = JSON.parse(JSON.stringify(this.originalSettings));
            ElMessage.info('Changes cancelled');
            this.$router.go(-1); // Or navigate to a specific route
        }
    }
}
</script>

<style scoped lang="scss">
.ehxdo_settings_page_container {
    font-family: Inter Tight, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;

    .ehxdo_settings_header {
        display: flex;
        justify-content: space-between;
        text-align: center;
        align-items: center;
        gap: 20px;
        padding: 24px 0px;

        .ehxdo_settings_title {
            h3 {
                margin: 0px;
                font-weight: 600;
                font-size: 20px;
                color: #0D0D12;
            }
        }
    }

    .ehxdo_settings_main_content {
        display: flex;

        .ehxdo_settings_details {
            width: 100%;
            border-radius: 0px 16px 16px 0px;
            background: #fff;
        }
    }
}
</style>