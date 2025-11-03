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
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Basic Details </h2>
                                </div>
                            </template>
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Campaign title</label>
                                <el-input v-model="campaigns.title" class="ehxdo-input"
                                    placeholder="Enter campaign title" />
                            </div>

                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Description</label>
                                <el-input v-model="campaigns.short_description" type="textarea" :rows="5"
                                    class="ehxdo-textarea" />
                            </div>

                            <div class="ehxdo-form-row">
                                <div class="ehxdo-form-col">
                                    <label class="ehxdo-label">Start Date</label>
                                    <el-date-picker v-model="campaigns.startDate" type="date" class="ehxdo-date-picker"
                                        placeholder="DD-MM-YYYY" format="DD-MM-YYYY" style="width: 100%;" />
                                </div>
                                <div class="ehxdo-form-col">
                                    <label class="ehxdo-label">End Date</label>
                                    <el-date-picker v-model="campaigns.endDate" type="date" class="ehxdo-date-picker"
                                        placeholder="DD-MM-YYYY" format="DD-MM-YYYY" style="width: 100%;" />
                                </div>
                            </div>

                        </el-card>
                    </div>

                    <!-- Donation Information Section -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Donation information </h2>
                                </div>
                            </template>
                            <!-- Goal Amount -->
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">
                                    Goal Amount
                                    <el-icon class="ehxdo-info-icon">
                                        <QuestionFilled />
                                    </el-icon>
                                </label>
                                <el-input v-model="campaigns.goal_amount" class="ehxdo-input" placeholder="100,000.00"
                                    :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" />
                            </div>

                            <!-- Allow Custom Amount -->
                            <div class="ehxdo-section-inner">
                                <div class="ehxdo-checkbox-group">
                                    <el-checkbox v-model="campaigns.allow_custom_amount" class="ehxdo-checkbox">
                                        Allow Custom Amount
                                    </el-checkbox>
                                </div>
                                <p class="ehxdo-description">
                                    With the Allow Custom Amount feature, users can set their own payment.
                                </p>

                                <div class="ehxdo-row">
                                    <div class="ehxdo-col">
                                        <el-input v-model="campaigns.min_donation" class="ehxdo-input"
                                            placeholder="Minimum Donation" />
                                    </div>
                                    <div class="ehxdo-col">
                                        <el-input v-model="campaigns.max_donation" class="ehxdo-input"
                                            placeholder="Maximum Donation" />
                                    </div>
                                </div>
                            </div>

                            <!-- Predefined Pricing -->
                            <div class="ehxdo-section-inner">
                                <div class="ehxdo-checkbox-group">
                                    <el-checkbox v-model="campaigns.predefined_pricing" class="ehxdo-checkbox">
                                        Predefined Pricing
                                    </el-checkbox>
                                </div>
                                <p class="ehxdo-description">
                                    Enable this option to allow commission payments on recurring product subscriptions.
                                </p>

                                <div class="ehxdo-pricing-table">
                                    <div class="ehxdo-table-header">
                                        <div class="ehxdo-table-cell">Name</div>
                                        <div class="ehxdo-table-cell">Amount</div>
                                        <div class="ehxdo-table-cell-action"></div>
                                    </div>

                                    <div v-for="(item, index) in campaigns.pricing_items" :key="index" class="ehxdo-table-row">
                                        <div class="ehxdo-table-cell">
                                            <el-input v-model="item.name" class="ehxdo-input" placeholder="Basic" />
                                        </div>
                                        <div class="ehxdo-table-cell">
                                            <el-input v-model="item.amount" class="ehxdo-input" placeholder="$ 5"
                                                :formatter="(value) => `$ ${value}`"
                                                :parser="(value) => value.replace(/\$\s?/g, '')" />
                                        </div>
                                        <div class="ehxdo-table-cell-action">
                                            <el-button class="ehxdo-delete-btn" :icon="Delete" text
                                                @click="removePricingItem(index)" />
                                        </div>
                                    </div>

                                    <el-button class="ehxdo-add-btn" type="success" text :icon="Plus"
                                        @click="addPricingItem">
                                        Add More
                                    </el-button>
                                </div>
                            </div>

                            <!-- Allow Recurring Amount -->
                            <div class="ehxdo-section-inner">
                                <div class="ehxdo-checkbox-group">
                                    <el-checkbox v-model="campaigns.allow_recurring_amount" class="ehxdo-checkbox">
                                        Allow Recurring Amount
                                    </el-checkbox>
                                </div>
                                <p class="ehxdo-description">
                                    Enable this option to allow commission payments on recurring product subscriptions.
                                </p>
                            </div>

                        </el-card>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="ehxdo-sidebar_campaign">
                    <!-- Campaign Image Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Campaign Image </h2>
                                </div>
                            </template>
                            <el-upload class="ehxdo-upload" drag action="#" :show-file-list="false"
                                :auto-upload="false">
                                <div class="ehxdo-upload-content">
                                    <el-icon class="ehxdo-upload-icon">
                                        <UploadFilled />
                                    </el-icon>
                                    <p class="ehxdo-upload-text">Click to upload or drag and drop</p>
                                    <p class="ehxdo-upload-hint">PNG, JPG or GIF (max. 2MB)</p>
                                    <el-button type="primary" size="small" class="ehxdo-upload-button">
                                        Choose File
                                    </el-button>
                                </div>
                            </el-upload>
                        </el-card>
                    </div>

                    <!-- Campaign Status Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Status</h2>
                                </div>
                            </template>

                            <div class="ehxdo-status-group">
                                <el-checkbox v-model="statusActive" class="ehxdo-status-checkbox"
                                    @change="handleStatusChange('active')">
                                    <span class="ehxdo-status-label">
                                        <span class="ehxdo-status-dot ehxdo-status-dot-active"></span>
                                        Active
                                    </span>
                                </el-checkbox>

                                <el-checkbox v-model="statusPending" class="ehxdo-status-checkbox"
                                    @change="handleStatusChange('pending')">
                                    <span class="ehxdo-status-label">
                                        <span class="ehxdo-status-dot ehxdo-status-dot-pending"></span>
                                        Pending
                                    </span>
                                </el-checkbox>

                                <el-checkbox v-model="statusComplete" class="ehxdo-status-checkbox"
                                    @change="handleStatusChange('complete')">
                                    <span class="ehxdo-status-label">
                                        <span class="ehxdo-status-dot ehxdo-status-dot-complete"></span>
                                        Complete
                                    </span>
                                </el-checkbox>
                            </div>
                        </el-card>
                    </div>

                    <!-- Campaign Category Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title"> Category</h2>
                                </div>
                            </template>
                            <el-select-v2 v-model="campaigns.category" :options="options" placeholder="Please select"
                                style="width: 100%; margin-right: 16px; vertical-align: middle" allow-create
                                default-first-option filterable multiple clearable />
                        </el-card>
                    </div>

                    <!-- Campaign Tags Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title"> Tags</h2>
                                </div>
                            </template>
                            <el-select-v2 v-model="campaigns.tags" :options="options" placeholder="Please select"
                                style="width: 100%; margin-right: 16px; vertical-align: middle" allow-create
                                default-first-option filterable multiple clearable />
                        </el-card>
                    </div>

                    <!-- Actions Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Actions</h2>
                                </div>
                            </template>
                            <el-button class="ehxdo-action-button ehxdo-action-button-primary" type="primary" @click="submitCampaignForm">
                                <el-icon class="ehxdo-action-icon">
                                    <Check />
                                </el-icon>
                                Save Campaign
                            </el-button>
                        </el-card>
                    </div>
                </div>

            </div>
        </el-form>
    </div>

</template>

<script>
import axios from "axios";
import {
    UploadFilled,
    Plus,
    Check,
    Delete,
    QuestionFilled,
    Right
} from '@element-plus/icons-vue';

export default {
    name: "AddCampaign",
    components: {
        UploadFilled,
        Plus,
        Check,
        Delete,
        QuestionFilled,
        Right
    },
    data() {
        return {
            campaigns: {
                title: "",
                short_description: "",
                startDate: "",
                endDate: "",
                goal_amount: "",
                allow_custom_amount: false,
                min_donation: "",
                max_donation: "",
                predefined_pricing: false,
                pricing_items: [
                    { name: 'Basic', amount: '5' }
                ],
                allow_recurring_amount: false,
                campaign_image: null,
                status: "active",
                category: [],
                tags: [],
            },
            statusActive: true,
            statusPending: false,
            statusComplete: false,
            rules: {
                title: [
                    { required: true, message: "Campaign title is required", trigger: "blur" },
                ],
            },
            submitting: false,
            directories: [],
            nonce: window.EHXDonate?.restNonce || '',
            rest_api: window.EHXDonate?.restUrl || '',
            initials: ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'],
            options: []
        };
    },
    created() {
        // Generate options for select
        this.options = Array.from({ length: 1000 }).map((_, idx) => ({
            value: `Option ${idx + 1}`,
            label: `${this.initials[idx % 10]}${idx}`,
        }));
    },
    methods: {
        addPricingItem() {
            this.campaigns.pricing_items.push({ name: '', amount: '' });
        },

        removePricingItem(index) {
            if (this.campaigns.pricing_items.length > 1) {
                this.campaigns.pricing_items.splice(index, 1);
            } else {
                this.$notify({
                    title: "Warning",
                    message: "At least one pricing item is required",
                    type: "warning",
                });
            }
        },

        handleStatusChange(selectedStatus) {
            // Only one status can be selected at a time
            if (selectedStatus === 'active') {
                if (this.statusActive) {
                    this.statusPending = false;
                    this.statusComplete = false;
                    this.campaigns.status = 'active';
                }
            } else if (selectedStatus === 'pending') {
                if (this.statusPending) {
                    this.statusActive = false;
                    this.statusComplete = false;
                    this.campaigns.status = 'pending';
                }
            } else if (selectedStatus === 'complete') {
                if (this.statusComplete) {
                    this.statusActive = false;
                    this.statusPending = false;
                    this.campaigns.status = 'complete';
                }
            }
        },

        async submitCampaignForm() {
            console.log("Submitting campaign form with data:", this.campaigns);
            
            // Validate required fields
            if (!this.campaigns.title) {
                this.$notify({
                    title: "Error",
                    message: "Campaign title is required",
                    type: "error",
                });
                return;
            }

            this.submitting = true;

            try {
                const response = await axios.post(`${this.rest_api}/postCampaign`, this.campaigns, {
                    headers: {
                        "Content-Type": "application/json",
                        "X-WP-Nonce": this.nonce,
                    },
                });

                if (response.data.success === true) {
                    this.$notify({
                        title: "Success",
                        message: "Campaign created successfully",
                        type: "success",
                    });

                    // Reset form or redirect
                    this.$router.push('/campaigns');
                } else {
                    this.$notify({
                        title: "Error",
                        message: "Failed to create campaign",
                        type: "error",
                    });
                }
            } catch (error) {
                console.error("Error saving campaign:", error);
                this.$notify({
                    title: "Error",
                    message: "An unexpected error occurred while saving the campaign.",
                    type: "error",
                });
            } finally {
                this.submitting = false;
            }
        },
    },
    mounted() {
        // Initialize anything needed on mount
    },
};
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
    margin-bottom: 24px;

    .ehxdo-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #121A26;
        margin: 0 0 6px 0;
    }

    .ehxdo-section-subtitle {
        font-size: 14px;
        color: #667085;
        font-weight: 400;
        margin: 0px;
    }
}

.ehxdo-section-inner {
    margin-bottom: 32px;
    padding-bottom: 32px;
    border-bottom: 1px solid #e8e8e8;

    &:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
}

.ehxdo-form-group {
    margin-bottom: 25px;

    .ehxdo-label {
        display: flex;
        align-items: center;
        gap: 6px;
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
    margin-bottom: 25px;

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

// Sidebar styles
.ehxdo-sidebar_campaign {
    width: 320px;
    flex-shrink: 0;
}

// Upload styles
.ehxdo-upload {
    :deep(.el-upload) {
        width: 100%;
        border: none;
    }

    :deep(.el-upload-dragger) {
        width: 100%;
        height: auto;
        min-height: 200px;
        padding: 32px 20px;
        border: 2px dashed #dcdfe6;
        border-radius: 8px;
        background-color: #fafafa;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;

        &:hover {
            border-color: #409eff;
            background-color: #f0f7ff;
        }
    }
}

.ehxdo-upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.ehxdo-upload-icon {
    font-size: 48px;
    color: #909399;
    margin-bottom: 16px;
}

.ehxdo-upload-text {
    font-size: 14px;
    color: #606266;
    margin: 0 0 4px 0;
}

.ehxdo-upload-hint {
    font-size: 12px;
    color: #909399;
    margin: 0 0 16px 0;
}

.ehxdo-upload-button {
    margin-top: 8px;
}

// Status styles
.ehxdo-status-group {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.ehxdo-status-checkbox {
    :deep(.el-checkbox__label) {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        transition: all 0.3s;
        display: flex;
        align-items: center;

        &:hover {
            background-color: #f5f7fa;
            border-color: #d0d0d0;
        }
    }

    :deep(.el-checkbox__input.is-checked+.el-checkbox__label) {
        background-color: #f0f9ff;
        border-color: #409eff;
    }
}

.ehxdo-status-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #303133;
}

.ehxdo-status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.ehxdo-status-dot-active {
    background-color: #67c23a;
    box-shadow: 0 0 0 3px rgba(103, 194, 58, 0.2);
}

.ehxdo-status-dot-pending {
    background-color: #e6a23c;
    box-shadow: 0 0 0 3px rgba(230, 162, 60, 0.2);
}

.ehxdo-status-dot-complete {
    background-color: #409eff;
    box-shadow: 0 0 0 3px rgba(64, 158, 255, 0.2);
}

// Actions styles
.ehxdo-action-button {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    font-weight: 500;

    &.ehxdo-action-button-primary {
        background-color: #67c23a;
        border-color: #67c23a;
        color: white;

        &:hover {
            background-color: #85ce61;
            border-color: #85ce61;
        }
    }
}

.ehxdo-action-icon {
    margin-right: 6px;
    font-size: 16px;
}

// Donation styles
.ehxdo-info-icon {
    color: #909399;
    font-size: 16px;
    cursor: help;
}

.ehxdo-checkbox-group {
    margin-bottom: 8px;
}

.ehxdo-checkbox {
    font-size: 15px;
    font-weight: 500;
    color: #1a1a1a;
}

.ehxdo-description {
    font-size: 13px;
    color: #666;
    margin: 0 0 16px 0;
    line-height: 1.5;
}

.ehxdo-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.ehxdo-col {
    width: 100%;
}

.ehxdo-pricing-table {
    margin-top: 16px;
    background: #fafafa;
    border-radius: 8px;
    padding: 16px;
}

.ehxdo-table-header {
    display: grid;
    grid-template-columns: 1fr 1fr 40px;
    gap: 12px;
    margin-bottom: 12px;
    padding: 0 4px;
}

.ehxdo-table-cell {
    font-size: 13px;
    font-weight: 500;
    color: #666;
}

.ehxdo-table-cell-action {
    width: 40px;
}

.ehxdo-table-row {
    display: grid;
    grid-template-columns: 1fr 1fr 40px;
    gap: 12px;
    align-items: center;
    margin-bottom: 12px;
}

.ehxdo-delete-btn {
    color: #909399;
    padding: 8px;
}

.ehxdo-delete-btn:hover {
    color: #f56c6c;
}

.ehxdo-add-btn {
    margin-top: 8px;
    font-weight: 500;
}

:deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
    background-color: #67c23a;
    border-color: #67c23a;
}

:deep(.el-checkbox__label) {
    color: #1a1a1a;
    font-weight: 500;
}

:deep(.el-input__wrapper) {
    border-radius: 6px;
    box-shadow: 0 0 0 1px #dcdfe6 inset;
    padding: 8px 12px;
}

:deep(.el-input__wrapper:hover) {
    box-shadow: 0 0 0 1px #c0c4cc inset;
}

:deep(.el-input__wrapper.is-focus) {
    box-shadow: 0 0 0 1px #409eff inset;
}

// Responsive design
@media (max-width: 1200px) {
    .ehxdo-container {
        flex-direction: column;
    }

    .ehxdo-sidebar_campaign {
        width: 100%;
        max-width: 100%;
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