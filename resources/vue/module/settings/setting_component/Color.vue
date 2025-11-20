<template>
    <div class="ehxdo-container">
        <!-- Color Picker Section -->
        <div class="ehxdo-section">
            {{ settings }} settings
            <h2 class="ehxdo-section-title">Primary Colors</h2>
            <el-row :gutter="20">
                <el-col :span="12">
                    <div class="ehxdo-color-item">
                        <label class="ehxdo-label">Button Color</label>
                        <el-color-picker v-model="settings.primary_btn" class="ehxdo-picker" />
                    </div>
                </el-col>
                <el-col :span="12">
                    <div class="ehxdo-color-item">
                        <label class="ehxdo-label">Button Text Color</label>
                        <el-color-picker v-model="settings.primary_btn_text" class="ehxdo-picker" />
                    </div>
                </el-col>
            </el-row>
        </div>

        <!-- Font Family Section -->
        <div class="ehxdo-section">
            <h2 class="ehxdo-section-title">Font Family</h2>
            <el-row :gutter="20">
                <el-col :span="24">
                    <div class="ehxdo-font-item">
                        <label class="ehxdo-label">Primary Font</label>
                        <el-select v-model="settings.fontFamily" class="ehxdo-select" placeholder="Select font family">
                            <el-option v-for="font in fontList" :key="font.value" :label="font.label"
                                :value="font.value" />
                        </el-select>
                        <div class="ehxdo-font-preview" :style="{ fontFamily: settings.fontFamily }">
                            Font preview sample text.
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
    </div>
</template>

<script>
export default {
    name: "ColorSettingsForm",
    props: {
        settings: {
            type: Object,
            default: () => ({})
        },
        loading: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            fontList: window.EHXDonate.fonts
                ? Object.keys(window.EHXDonate.fonts).map(key => ({
                    label: key,
                    value: window.EHXDonate.fonts[key]
                }))
                : [],
            nonce: window.EHXDonate?.restNonce || '',
            restUrl: window.EHXDonate?.restUrl || ''
        };
    },
    mounted() {
        console.log("ColorSettingsForm loaded", window);
    }
};
</script>

<style scoped>
.ehxdo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.ehxdo-section {
    margin-bottom: 40px;
}

.ehxdo-section-title {
    font-size: 18px;
    font-weight: 500;
    color: #303133;
    margin-bottom: 20px;
    padding-bottom: 20px;
    margin-top: 0px;
    border-bottom: 1px solid #EBEEF5;
}

.ehxdo-color-item,
.ehxdo-font-item {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ehxdo-label {
    font-size: 14px;
    color: #606266;
    font-weight: 500;
}

.ehxdo-picker,
.ehxdo-select {
    width: 100%;
}

.ehxdo-font-preview {
    padding: 16px;
    background-color: #f8f9fc;
    border-radius: 10px;
    border: 1px solid #efefef;
    font-size: 14px;
    margin-top: 10px;
    color: #303133;
}

:deep(.el-color-picker) {
    width: 100% !important;
    height: 45px !important;
}

:deep(.el-select .el-select__wrapper) {
    height: 44px !important;
}
</style>
