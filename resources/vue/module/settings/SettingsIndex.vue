<template>
    <div class="ehxdo_settings_page_container">
        <div class="ehxdo_settings_header">
            <div class="ehxdo_settings_title">
                <h3>Settings</h3>
            </div>
            <div class="ehxdo_settings_right">
                <el-button type="info" @click="handleCancel" :disabled="saving">Cancel</el-button>
                <el-button type="primary" @click="handleSave" :loading="saving" :disabled="saving">
                    {{ saving ? 'Saving...' : 'Save Change' }}
                </el-button>
            </div>
        </div>
        <div class="ehxdo_settings_main_content">
            <div class="ehxdo_settings_sidebar">
                <SettingSidebar />
            </div>
            <div class="ehxdo_settings_details" v-loading="loading">
                <RouterView :settings="settings" v-if="!loading && settings" />
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
    watch: {
        $route: {
            immediate: true,
            handler() {
                this.loadSettings(this.$route.name);
            }
        }
    },
    mounted() {

        this.loadSettings(this.$route.name);
    },
    methods: {
        async loadSettings(key) {

            if (key == 'shortcode' || key == 'index') {
                return false;
            }

            this.loading = true;
            try {
                const response = await fetch(`${this.restUrl}api/settings/${key}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce,
                    }
                });

                const result = await response.json();

                if (result.success) {
                    this.settings = { ...result.data?.settings };
                    this.originalSettings = JSON.parse(JSON.stringify(result.data?.settings));
                }
            } catch (error) {
                console.error('Error loading settings:', error);
                ElMessage.error('Failed to load settings');
            } finally {
                this.loading = false;
            }
        },
        async handleSave(settings, key) {
            this.saving = true;
            try {
                const response = await fetch(`${this.restUrl}api/settings/${this.$route.name}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    },
                    body: JSON.stringify({ settings: JSON.stringify(this.settings) })
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
            this.$router.go(-1);
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
        padding: 35px 20px 0px 20px;
        // border-bottom: 1px solid #e5e7eb;

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
        margin-bottom: 50px;
        padding: 20px 20px 0px 20px;

        .ehxdo_settings_details {
            width: 100%;
            background: #fff;
            overflow: hidden;
            border-radius: 0px 16px 16px 0px;
        }
    }
}
</style>