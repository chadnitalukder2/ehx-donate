<template>
    <div class="ehxdo-container">
        <h1 class="ehxdo-main-title">Color & Typography Template</h1>

        <!-- Color Picker Section -->
        <div class="ehxdo-section">
            <h2 class="ehxdo-section-title">Primary Colors</h2>
            <el-row :gutter="20">
                <el-col :span="6">
                    <div class="ehxdo-color-item">
                        <label class="ehxdo-label">Primary Color</label>
                        <el-color-picker v-model="colors.primary" class="ehxdo-picker" />
                        <el-input v-model="colors.primary" class="ehxdo-input" readonly />
                    </div>
                </el-col>
                <!-- <el-col :span="6">
                    <div class="ehxdo-color-item">
                        <label class="ehxdo-label">Success Color</label>
                        <el-color-picker v-model="colors.success" class="ehxdo-picker" />
                        <el-input v-model="colors.success" class="ehxdo-input" readonly />
                    </div>
                </el-col>
                <el-col :span="6">
                    <div class="ehxdo-color-item">
                        <label class="ehxdo-label">Warning Color</label>
                        <el-color-picker v-model="colors.warning" class="ehxdo-picker" />
                        <el-input v-model="colors.warning" class="ehxdo-input" readonly />
                    </div>
                </el-col>
                <el-col :span="6">
                    <div class="ehxdo-color-item">
                        <label class="ehxdo-label">Danger Color</label>
                        <el-color-picker v-model="colors.danger" class="ehxdo-picker" />
                        <el-input v-model="colors.danger" class="ehxdo-input" readonly />
                    </div>
                </el-col> -->
            </el-row>
        </div>

        <!-- Text Colors Section -->
        <!-- <div class="ehxdo-section">
      <h2 class="ehxdo-section-title">Text Colors</h2>
      <el-row :gutter="20">
        <el-col :span="6">
          <div class="ehxdo-color-item">
            <label class="ehxdo-label">Primary Text</label>
            <el-color-picker v-model="colors.textPrimary" class="ehxdo-picker" />
            <el-input v-model="colors.textPrimary" class="ehxdo-input" readonly />
          </div>
        </el-col>
        <el-col :span="6">
          <div class="ehxdo-color-item">
            <label class="ehxdo-label">Regular Text</label>
            <el-color-picker v-model="colors.textRegular" class="ehxdo-picker" />
            <el-input v-model="colors.textRegular" class="ehxdo-input" readonly />
          </div>
        </el-col>
        <el-col :span="6">
          <div class="ehxdo-color-item">
            <label class="ehxdo-label">Secondary Text</label>
            <el-color-picker v-model="colors.textSecondary" class="ehxdo-picker" />
            <el-input v-model="colors.textSecondary" class="ehxdo-input" readonly />
          </div>
        </el-col>
        <el-col :span="6">
          <div class="ehxdo-color-item">
            <label class="ehxdo-label">Placeholder</label>
            <el-color-picker v-model="colors.textPlaceholder" class="ehxdo-picker" />
            <el-input v-model="colors.textPlaceholder" class="ehxdo-input" readonly />
          </div>
        </el-col>
      </el-row>
    </div> -->

        <!-- Font Family Section -->
        <div class="ehxdo-section">
            <h2 class="ehxdo-section-title">Font Family</h2>
            <el-row :gutter="20">
                <el-col :span="12">
                    <div class="ehxdo-font-item">
                        <label class="ehxdo-label">Primary Font</label>
                        <el-select v-model="fonts.primary" class="ehxdo-select" placeholder="Select font family">
                            <el-option v-for="font in fontOptions" :key="font.value" :label="font.label"
                                :value="font.value" />
                        </el-select>
                        <div class="ehxdo-font-preview" :style="{ fontFamily: fonts.primary }">
                            Lekha - The quick brown fox jumps over the lazy dog
                        </div>
                    </div>
                </el-col>
                <!-- <el-col :span="12">
          <div class="ehxdo-font-item">
            <label class="ehxdo-label">Secondary Font (Monospace)</label>
            <el-select 
              v-model="fonts.mono" 
              class="ehxdo-select"
              placeholder="Select monospace font"
            >
              <el-option
                v-for="font in monoFontOptions"
                :key="font.value"
                :label="font.label"
                :value="font.value"
              />
            </el-select>
            <div class="ehxdo-font-preview" :style="{ fontFamily: fonts.mono }">
              console.log('Lekha - Hello World');
            </div>
          </div>
        </el-col> -->
            </el-row>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { ElMessage } from 'element-plus'

// Color palette
const colors = ref({
    primary: '#409EFF',
    success: '#67C23A',
    warning: '#E6A23C',
    danger: '#F56C6C',
    textPrimary: '#303133',
    textRegular: '#606266',
    textSecondary: '#909399',
    textPlaceholder: '#C0C4CC'
})

// Font families
const fonts = ref({
    primary: '"Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", Arial, sans-serif',
    mono: '"Courier New", Courier, monospace'
})

// Font options for select
const fontOptions = ref([
    { label: 'Lekhar', value: '"Lekhar", Arial, sans-serif' },
    { label: 'Helvetica Neue', value: '"Helvetica Neue", Helvetica, Arial, sans-serif' },
    { label: 'Arial', value: 'Arial, sans-serif' },
    { label: 'Georgia', value: 'Georgia, serif' },
    { label: 'Times New Roman', value: '"Times New Roman", Times, serif' },
    { label: 'Verdana', value: 'Verdana, sans-serif' },
    { label: 'Tahoma', value: 'Tahoma, sans-serif' },
    { label: 'Trebuchet MS', value: '"Trebuchet MS", sans-serif' },
    { label: 'Segoe UI', value: '"Segoe UI", Tahoma, sans-serif' },
    { label: 'Roboto', value: '"Roboto", sans-serif' },
    { label: 'Open Sans', value: '"Open Sans", sans-serif' },
    { label: 'Lato', value: '"Lato", sans-serif' }
])

const monoFontOptions = ref([
    { label: 'Courier New', value: '"Courier New", Courier, monospace' },
    { label: 'Consolas', value: 'Consolas, monospace' },
    { label: 'Monaco', value: 'Monaco, monospace' },
    { label: 'Menlo', value: 'Menlo, monospace' },
    { label: 'Source Code Pro', value: '"Source Code Pro", monospace' },
    { label: 'Fira Code', value: '"Fira Code", monospace' }
])

// Font sizes
const fontSizes = ref({
    extraLarge: 20,
    large: 18,
    medium: 16,
    base: 14,
    small: 13,
    extraSmall: 12
})

// Format label helper
const formatLabel = (key) => {
    return key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())
}

// Generate CSS variables
const cssVariables = computed(() => {
    return `:root {
  /* Primary Colors */
  --ehxdo-color-primary: ${colors.value.primary};
  --ehxdo-color-success: ${colors.value.success};
  --ehxdo-color-warning: ${colors.value.warning};
  --ehxdo-color-danger: ${colors.value.danger};
  
  /* Text Colors */
  --ehxdo-text-primary: ${colors.value.textPrimary};
  --ehxdo-text-regular: ${colors.value.textRegular};
  --ehxdo-text-secondary: ${colors.value.textSecondary};
  --ehxdo-text-placeholder: ${colors.value.textPlaceholder};
  
  /* Font Family */
  --ehxdo-font-family: ${fonts.value.primary};
  --ehxdo-font-family-mono: ${fonts.value.mono};
  
  /* Font Sizes */
  --ehxdo-font-size-extra-large: ${fontSizes.value.extraLarge}px;
  --ehxdo-font-size-large: ${fontSizes.value.large}px;
  --ehxdo-font-size-medium: ${fontSizes.value.medium}px;
  --ehxdo-font-size-base: ${fontSizes.value.base}px;
  --ehxdo-font-size-small: ${fontSizes.value.small}px;
  --ehxdo-font-size-extra-small: ${fontSizes.value.extraSmall}px;
}`
})

// Copy to clipboard
const copyCSSVariables = () => {
    navigator.clipboard.writeText(cssVariables.value)
    ElMessage.success('CSS Variables copied to clipboard!')
}
</script>

<style scoped>
.ehxdo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.ehxdo-main-title {
    font-size: 28px;
    font-weight: 600;
    color: #303133;
    margin-bottom: 32px;
}

.ehxdo-section {
    margin-bottom: 40px;
}

.ehxdo-section-title {
    font-size: 20px;
    font-weight: 500;
    color: #303133;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #EBEEF5;
}

.ehxdo-color-item {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ehxdo-label {
    font-size: 14px;
    color: #606266;
    font-weight: 500;
}

.ehxdo-picker {
    width: 100%;
}

.ehxdo-input {
    margin-top: 8px;
}

.ehxdo-select {
    width: 100%;
}

.ehxdo-font-item {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ehxdo-font-preview {
    padding: 16px;
    background-color: #F5F7FA;
    border-radius: 4px;
    border: 1px solid #DCDFE6;
    font-size: 16px;
    color: #303133;
}

.ehxdo-typography-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.ehxdo-typography-item {
    padding: 16px;
    background-color: #F5F7FA;
    border-radius: 4px;
}

.ehxdo-typography-controls {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 12px;
}

.ehxdo-number-input {
    width: 120px;
}

.ehxdo-size-label {
    font-size: 13px;
    color: #909399;
    font-weight: 500;
}

.ehxdo-text-preview {
    padding: 12px;
    background-color: white;
    border-radius: 4px;
    border: 1px solid #DCDFE6;
}

.ehxdo-preview-card {
    margin-top: 16px;
}

.ehxdo-preview-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.ehxdo-button-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.ehxdo-code-card {
    position: relative;
}

.ehxdo-code-block {
    background-color: #2D2D2D;
    color: #F8F8F2;
    padding: 20px;
    border-radius: 4px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 13px;
    line-height: 1.6;
    overflow-x: auto;
    margin: 0;
    white-space: pre;
}

.ehxdo-copy-btn {
    margin-top: 16px;
}

:deep(.el-color-picker) {
    width: 240px !important;
    height: 55px !important;
}
</style>