<template>
    <div class="ehxdo_add_campaign_wrapper">
        <!-- Header Section -->
        <div class="ehxdo-header">
            <h1 class="ehxdo-title">Create new campaign</h1>
            <div class="ehxdo-header-actions">
                <el-button type="info">
                    <router-link to="/campaigns">Back</router-link>
                    <el-icon>
                        <Right />
                    </el-icon>
                </el-button>
            </div>
        </div>
        <el-form>
            <div class="ehxdo-container">

                <div class="ehxdo-main-content">

                    <!-- Basic Details Section -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none;     border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Basic Details </h2>
                                    <!-- <p class="ehxdo-section-subtitle">
                                        Enter the core information about your campaign
                                    </p> -->
                                </div>
                            </template>
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Campaign title</label>
                                <el-input v-model="title" class="ehxdo-input" placeholder="Enter campaign title" />
                            </div>

                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Description</label>
                                <el-input v-model="short_description" type="textarea" :rows="5"
                                    class="ehxdo-textarea" />
                            </div>

                            <div class="ehxdo-form-row">
                                <div class="ehxdo-form-col">
                                    <label class="ehxdo-label">Start Date</label>
                                    <el-date-picker v-model="startDate" type="date" class="ehxdo-date-picker"
                                        placeholder="DD-MM-YYYY" format="DD-MM-YYYY" style="width: 100%;" />
                                </div>
                                <div class="ehxdo-form-col">
                                    <label class="ehxdo-label">End Date</label>
                                    <el-date-picker v-model="endDate" type="date" class="ehxdo-date-picker"
                                        placeholder="DD-MM-YYYY" format="DD-MM-YYYY" style="width: 100%;" />
                                </div>
                            </div>

                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Category </label>
                                <el-select-v2 v-model="category" :options="options" placeholder="Please select"
                                    style="width: 100%; margin-right: 16px; vertical-align: middle" allow-create
                                    default-first-option filterable multiple clearable />
                            </div>

                              <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Tags </label>
                                <el-select-v2 v-model="tags" :options="options" placeholder="Please select"
                                    style="width: 100%; margin-right: 16px; vertical-align: middle" allow-create
                                    default-first-option filterable multiple clearable />
                            </div>

                        </el-card>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="ehxdo-sidebar">
                    <!-- Live Preview Card -->
                    <div class="ehxdo-card">
                        <h3 class="ehxdo-card-title">Live Preview</h3>
                        <p class="ehxdo-card-subtitle">How Your Campaign Will Look Online</p>
                        <div class="ehxdo-preview-placeholder">
                            <el-icon class="ehxdo-preview-icon">
                                <picture />
                            </el-icon>
                        </div>
                    </div>

                    <!-- Campaign Tips Card -->
                    <div class="ehxdo-card">
                        <h3 class="ehxdo-card-title">Campaign Tips</h3>
                        <div class="ehxdo-tips-list">
                            <div class="ehxdo-tip-item">
                                <span class="ehxdo-tip-label">Name</span>
                                <el-slider v-model="nameProgress" class="ehxdo-slider" :show-tooltip="false" />
                            </div>
                            <div class="ehxdo-tip-item">
                                <span class="ehxdo-tip-label">Subtitle</span>
                                <el-slider v-model="subtitleProgress" class="ehxdo-slider" :show-tooltip="false" />
                            </div>
                            <div class="ehxdo-tip-item">
                                <span class="ehxdo-tip-label">How You Fit In</span>
                                <el-slider v-model="fitProgress" class="ehxdo-slider" :show-tooltip="false" />
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="ehxdo-card ehxdo-actions-card">
                        <h3 class="ehxdo-card-title">Actions</h3>
                        <el-button type="success" class="ehxdo-action-btn ehxdo-action-btn-primary">
                            <el-icon>
                                <check />
                            </el-icon> Save Launch
                        </el-button>
                        <el-button plain class="ehxdo-action-btn ehxdo-action-btn-secondary">
                            <el-icon>
                                <download />
                            </el-icon> Save as Draft
                        </el-button>
                        <el-button text class="ehxdo-action-btn ehxdo-action-btn-text">Cancel</el-button>
                    </div>
                </div>

            </div>
        </el-form>
    </div>

</template>

<script setup>
import { ref } from 'vue'
import {
    UploadFilled,
    Plus,
    Check,
    Download,
    Picture
} from '@element-plus/icons-vue';

// Form data
const campaignTitle = ref('');
const campaignDescription = ref('');
const audience = ref('');
const startDate = ref('');
const endDate = ref('');

// Donation switches
const alexKrizenActive = ref(true);
const breakfastActive = ref(false);
const recurringActive = ref(false);
const breakfastTime = ref('');

// Settings
const sendingType = ref('public');
const uploadLink = ref('');
const textContent = ref('');
const valNumber = ref(0);

// Progress sliders
const nameProgress = ref(60);
const subtitleProgress = ref(40);
const fitProgress = ref(80);

const initials = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j']

const value1 = ref([])
const value2 = ref()
const category = ref([])
const tags = ref([])
const options = Array.from({ length: 1000 }).map((_, idx) => ({
  value: `Option ${idx + 1}`,
  label: `${initials[idx % 10]}${idx}`,
}))
</script>

<style lang="scss" scoped>
.ehxdo_add_campaign_wrapper {
    padding: 20px 0px;
}

.ehxdo-container {
    display: flex;
    gap: 20px;

    min-height: 100vh;
    font-family: Inter Tight, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.ehxdo-main-content {
    flex: 1;
    max-width: 800px;
}

.ehxdo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;

    .ehxdo-title {
        font-size: 20px;
        font-weight: 600;
        color: #0D0D12;
        margin: 0;
    }

    .ehxdo-header-actions {
        display: flex;
        gap: 12px;
    }
}

.ehxdo-section {
    // background: white;
    // border-radius: 16px;
    // padding: 24px;
    margin-bottom: 24px;
    // box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);

    .ehxdo-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #121A26;
        margin: 0px;
        margin: 0 0 6px 0;
    }

    .ehxdo-section-subtitle {
        font-size: 14px;
        color: #667085;
        font-weight: 400;
        margin: 0px;
    }
}

.ehxdo-form-group {
    margin-bottom: 20px;

    .ehxdo-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #606266;
        margin-bottom: 8px;
    }

    .ehxdo-input,
    .ehxdo-textarea {
        width: 100%;
    }
   
}

.ehxdo-form-row {
    display: flex;
    gap: 20px;
    justify-content: space-between;
    margin-bottom: 20px;

    .ehxdo-form-col {
        flex-basis: 50%;

        .ehxdo-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #606266;
            margin-bottom: 8px;
        }

        .ehxdo-select,
        .ehxdo-date-picker {
            width: 100%;
        }
    }
}

.ehxdo-upload-area {
    margin-bottom: 16px;

    .ehxdo-uploader {
        width: 100%;

        :deep(.el-upload) {
            width: 100%;
        }

        :deep(.el-upload-dragger) {
            width: 100%;
            height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #dcdfe6;
            border-radius: 8px;
            background-color: #fafafa;
            transition: all 0.3s;

            &:hover {
                border-color: #409eff;
            }
        }

        .ehxdo-upload-icon {
            font-size: 48px;
            color: #c0c4cc;
            margin-bottom: 16px;
        }

        .ehxdo-upload-text {
            font-size: 14px;
            color: #606266;
            margin-bottom: 8px;
        }

        .ehxdo-upload-formats {
            font-size: 12px;
            color: #909399;
        }
    }
}

.ehxdo-powered-by {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #909399;

    .ehxdo-powered-logo {
        height: 20px;
    }
}

.ehxdo-donation-item {
    display: flex;
    gap: 12px;
    padding: 16px;
    border: 1px solid #ebeef5;
    border-radius: 8px;
    margin-bottom: 16px;

    .ehxdo-checkbox {
        margin-top: 4px;
    }

    .ehxdo-donation-content {
        flex: 1;

        .ehxdo-donation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;

            .ehxdo-donation-title {
                font-size: 16px;
                font-weight: 600;
                color: #303133;
                margin: 0;
            }
        }

        .ehxdo-donation-desc {
            font-size: 14px;
            color: #606266;
            margin: 0 0 12px 0;
            line-height: 1.5;
        }

        .ehxdo-donation-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #909399;

            .ehxdo-donation-separator {
                color: #dcdfe6;
            }

            strong {
                color: #303133;
                font-weight: 600;
            }
        }

        .ehxdo-donation-actions {
            display: flex;
            align-items: center;
            gap: 12px;

            .ehxdo-donation-time {
                font-size: 14px;
                color: #606266;
            }

            .ehxdo-time-picker {
                flex: 1;
                max-width: 200px;
            }
        }
    }
}

.ehxdo-settings-item {
    margin-bottom: 20px;

    .ehxdo-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #606266;
        margin-bottom: 8px;
    }

    .ehxdo-radio-group {
        display: flex;
        flex-direction: column;
        gap: 8px;

        :deep(.el-radio) {
            margin-right: 0;
            margin-bottom: 8px;

            .el-radio__label {
                font-size: 14px;
                color: #606266;
            }
        }
    }

    .ehxdo-input,
    .ehxdo-textarea {
        width: 100%;
    }

    .ehxdo-input-number {
        width: 100%;
    }
}

// Sidebar styles
.ehxdo-sidebar {
    width: 320px;
    flex-shrink: 0;

    .ehxdo-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);

        .ehxdo-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #303133;
            margin: 0 0 8px 0;
        }

        .ehxdo-card-subtitle {
            font-size: 13px;
            color: #909399;
            margin: 0 0 16px 0;
        }
    }
}

.ehxdo-preview-placeholder {
    width: 100%;
    height: 180px;
    background-color: #f5f7fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;

    .ehxdo-preview-icon {
        font-size: 48px;
        color: #c0c4cc;
    }
}

.ehxdo-tips-list {
    .ehxdo-tip-item {
        margin-bottom: 20px;

        &:last-child {
            margin-bottom: 0;
        }

        .ehxdo-tip-label {
            display: block;
            font-size: 13px;
            color: #606266;
            margin-bottom: 8px;
        }

        .ehxdo-slider {
            :deep(.el-slider__runway) {
                height: 6px;
            }

            :deep(.el-slider__bar) {
                background-color: #67c23a;
            }

            :deep(.el-slider__button) {
                width: 14px;
                height: 14px;
                border-color: #67c23a;
            }
        }
    }
}

.ehxdo-actions-card {
    .ehxdo-action-btn {
        width: 100%;
        margin-bottom: 12px;
        justify-content: center;

        &:last-child {
            margin-bottom: 0;
        }

        &.ehxdo-action-btn-primary {
            background-color: #67c23a;
            border-color: #67c23a;
            color: white;

            &:hover {
                background-color: #85ce61;
                border-color: #85ce61;
            }
        }

        &.ehxdo-action-btn-secondary {
            border-color: #dcdfe6;
            color: #606266;

            &:hover {
                color: #409eff;
                border-color: #c6e2ff;
                background-color: #ecf5ff;
            }
        }

        &.ehxdo-action-btn-text {
            color: #909399;

            &:hover {
                color: #606266;
            }
        }
    }
}

// Responsive design
@media (max-width: 1200px) {
    .ehxdo-container {
        flex-direction: column;
    }

    .ehxdo-sidebar {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .ehxdo-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .ehxdo-form-row {
        flex-direction: column;
    }
}
</style>