<template>
    <div class="ehxdo_settings_page_container">
        <div class="ehxdo_settings_header">
            <div class="ehxdo_settings_title">
                <h3>Settings</h3>
            </div>
            <div class="ehxdo_settings_right">
                <el-button type="info">Cancel</el-button>
                <el-button type="primary">Save Change</el-button>
            </div>

        </div>
        <div class="ehxdo_settings_main_content">
            <div class="ehxdo_settings_sidebar">
                <SettingSidebar />
            </div>
            <div class="ehxdo_settings_details">
                <RouterView />
            </div>
        </div>
    </div>
</template>

<script>
import SettingSidebar from './setting_component/SettingSidebar.vue';

export default {
    name: 'FileUploader',
    components: {
        SettingSidebar
    },
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
            padding: 24px;
            background: #fff;
        }
    }

}
</style>