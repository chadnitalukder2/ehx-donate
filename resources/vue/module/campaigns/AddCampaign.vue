<template>
    <div class="ehxdo_edit_campaign_wrapper">
        <!-- Header Section -->
        <div class="ehxdo-header">
            <h1 class="ehxdo-title">Edit Campaign</h1>
            <div class="ehxdo-header-actions">
                <!-- <el-button @click="$router.back()" class="ehxdo-back-btn">
                    <el-icon>
                        <ArrowLeft />
                    </el-icon>
                    Back
                </el-button> -->
            </div>
        </div>

        <el-form v-if="Object.keys(form).length > 0">
            <div class="ehxdo-container">

                <div class="ehxdo-main-content">

                    <!-- Campaign Information Section -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Campaign Information</h2>
                                </div>
                            </template>

                            <div class="ehxdo-form-group">
                                <el-form-item label="Campaign Title" required>
                                    <el-input v-model="form.title" />
                                </el-form-item>
                            </div>

                            <div class="ehxdo-form-group">
                                <el-form-item label="Short Description" required>
                                    <el-input type="textarea" :maxlength="250" show-word-limit rows="4"
                                        placeholder="Say something about your campaign..."
                                        v-model="form.short_description" />
                                </el-form-item>
                            </div>

                            <div class="ehxdo-form-group">
                                <el-form-item label="Description">
                                    <el-input type="textarea" rows="6"
                                        placeholder="Say something about your campaign..."
                                        v-model="form.settings.description" />
                                </el-form-item>
                            </div>

                            <div class="ehxdo-form-row">
                                <div class="ehxdo-form-col">
                                    <el-form-item label="Start Date">
                                        <el-date-picker v-model="form.start_date" type="date" class="ehxdo-date-picker"
                                            placeholder="DD-MM-YYYY" format="DD-MM-YYYY" style="width: 100%;" />
                                    </el-form-item>
                                </div>
                                <div class="ehxdo-form-col">
                                    <el-form-item label="End Date">
                                        <el-date-picker v-model="form.end_date" type="date" class="ehxdo-date-picker"
                                            placeholder="DD-MM-YYYY" format="DD-MM-YYYY" style="width: 100%;" />
                                    </el-form-item>

                                </div>
                            </div>

                        </el-card>
                    </div>

                    <!-- Donation Information Section -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Donation Information</h2>
                                </div>
                            </template>

                            <!-- Goal Amount -->
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">
                                    Goal Amount <span style="color:#db0000; font-size:16px;">*</span>
                                </label>
                                <el-input v-model="form.goal_amount" class="ehxdo-input" placeholder="100,000.00"
                                    :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" />
                            </div>

                            <!-- Allow Custom Amount -->
                            <div class="ehxdo-section-inner">
                                <div class="ehxdo-checkbox-group">
                                    <el-checkbox v-model="form.settings.allow_custom_amount" class="ehxdo-checkbox">
                                        Allow Custom Amount
                                    </el-checkbox>
                                </div>
                                <p class="ehxdo-description">
                                    With the Allow Custom Amount feature, users can set their own payment.
                                </p>

                                <div class="ehxdo-row" v-if="form.settings.allow_custom_amount">
                                    <div class="ehxdo-col">
                                        <el-input v-model="form.settings.min_donation" class="ehxdo-input"
                                            placeholder="Minimum Donation" />
                                    </div>
                                    <div class="ehxdo-col">
                                        <el-input v-model="form.settings.max_donation" class="ehxdo-input"
                                            placeholder="Maximum Donation" />
                                    </div>
                                </div>
                            </div>

                            <!-- Predefined Pricing -->
                            <div class="ehxdo-section-inner">
                                <div class="ehxdo-checkbox-group">
                                    <el-checkbox v-model="form.settings.predefined_pricing" class="ehxdo-checkbox">
                                        Predefined Pricing
                                    </el-checkbox>
                                </div>
                                <p class="ehxdo-description">
                                    Enable this option to allow commission payments on recurring product subscriptions.
                                </p>

                                <div class="ehxdo-pricing-table" v-if="form.settings.predefined_pricing">
                                    <div class="ehxdo-table-header">
                                        <div class="ehxdo-table-cell"
                                            style="font-weight: 500; color: #666; font-size: 14px;">Name</div>
                                        <div class="ehxdo-table-cell"
                                            style="font-weight: 500; color: #666; font-size: 14px;">Amount</div>
                                        <div class="ehxdo-table-cell-action"></div>
                                    </div>

                                    <div v-for="(item, index) in form.settings.pricing_items" :key="index"
                                        class="ehxdo-table-row">
                                        <div class="ehxdo-table-cell">
                                            <el-input v-model="item.name" class="ehxdo-input" placeholder="Basic" />
                                        </div>
                                        <div class="ehxdo-table-cell">
                                            <el-input v-model="item.amount" class="ehxdo-input" placeholder="$ 5"
                                                :formatter="(value) => `$ ${value}`"
                                                :parser="(value) => value.replace(/\$\s?/g, '')" />
                                        </div>
                                        <div class="ehxdo-table-cell-action">
                                            <el-button class="ehxdo-delete-btn" text @click="removePricing(index)">
                                                <el-icon>
                                                    <Delete />
                                                </el-icon>
                                            </el-button>
                                        </div>
                                    </div>

                                    <el-button class="ehxdo-add-btn" text @click="addPricing">
                                        <el-icon style="font-size: 16px; font-weight: 600; margin-right: 8px;">
                                            <Plus />
                                        </el-icon>
                                        Add More
                                    </el-button>
                                </div>
                            </div>

                            <!-- Allow Recurring Amount -->
                            <div class="ehxdo-section-inner">
                                <div class="ehxdo-checkbox-group">
                                    <el-checkbox v-model="form.settings.allow_recurring_amount" class="ehxdo-checkbox">
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
                    <!-- Campaign Images Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Campaign Images</h2>
                                </div>
                            </template>
                            <div class="ehxdo-upload-image">
                                <!-- <DraggableFileUpload :attachments="form.settings.images" 
                                    @mediaUploaded="onMediaUploaded"
                                    @removeImage="removeImage" /> -->
                                <EditFileUpload v-model:selectedFile="form.header_image" btnTitle="Add Media" />
                            </div>
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
                                        <!-- <span class="ehxdo-status-dot ehxdo-status-dot-active"></span> -->
                                        Active
                                    </span>
                                </el-checkbox>

                                <el-checkbox v-model="statusPending" class="ehxdo-status-checkbox"
                                    @change="handleStatusChange('pending')">
                                    <span class="ehxdo-status-label">
                                        <!-- <span class="ehxdo-status-dot ehxdo-status-dot-pending"></span> -->
                                        Pending
                                    </span>
                                </el-checkbox>

                                <el-checkbox v-model="statusComplete" class="ehxdo-status-checkbox"
                                    @change="handleStatusChange('completed')">
                                    <span class="ehxdo-status-label">
                                        <!-- <span class="ehxdo-status-dot ehxdo-status-dot-complete"></span> -->
                                        Completed
                                    </span>
                                </el-checkbox>
                            </div>
                        </el-card>
                    </div>

                    <!-- Campaign Categories Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Categories</h2>
                                </div>
                            </template>
                            <el-select-v2 v-model="form.categories" :options="categoryOptions"
                                placeholder="Choose categories"
                                style="width: 100%; margin-right: 16px; vertical-align: middle" allow-create
                                default-first-option filterable multiple clearable />
                        </el-card>
                    </div>

                    <!-- Campaign Tags Card -->
                    <div class="ehxdo-section">
                        <el-card style="max-width: 100%; border-radius: 16px; box-shadow: none; border: none;">
                            <template #header>
                                <div class="card-header">
                                    <h2 class="ehxdo-section-title">Tags</h2>
                                </div>
                            </template>
                            <el-select-v2 v-model="form.tags" :options="tagOptions" placeholder="Choose keywords"
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
                            <el-button class="ehxdo-action-button ehxdo-action-button-primary" type="primary"
                                @click="submitForm" :loading="submitting">
                                Save Changes
                            </el-button>
                            <el-button type="info" class="ehxdo-action-button ehxdo-action-preview-button"
                                @click="previewForm(form?.post?.guid)">
                                Preview
                            </el-button>
                            <el-button type="info" class="ehxdo-action-can-button" @click="$router.back()">
                                Cancel
                            </el-button>
                        </el-card>
                    </div>
                </div>

            </div>
        </el-form>
    </div>
</template>

<script>
// import DraggableFileUpload from "../../components/DraggableFileUpload.vue";
import EditFileUpload from "../../components/EditFileUpload.vue";

export default {
    name: "EditCampaign",
    components: {
        // DraggableFileUpload,
        EditFileUpload,
    },
    data() {
        return {
            form: {},
            submitting: false,
            statusActive: false,
            statusPending: false,
            statusComplete: false,
            nonce: window.EHXDonate?.restNonce || '',
            restUrl: window.EHXDonate?.restUrl || '',
            tagOptions: [],
            categoryOptions: []
        };
    },
    created() {
        // Transform tags from window.EHXDonate
        if (window.EHXDonate?.tags) {
            this.tagOptions = window.EHXDonate.tags.map(tag => ({
                value: tag.term_id,
                label: tag.name
            }));
        }

        // Transform categories from window.EHXDonate
        if (window.EHXDonate?.categories) {
            this.categoryOptions = window.EHXDonate.categories.map(cat => ({
                value: cat.term_id,
                label: cat.name
            }));
        }
    },
    methods: {
        addPricing() {
            this.form.settings.pricing_items.push({ name: '', amount: '' });
        },

        removePricing(index) {
            if (this.form.settings.pricing_items.length > 1) {
                this.form.settings.pricing_items.splice(index, 1);
            } else {
                this.$notify({
                    title: "Warning",
                    message: "At least one pricing item is required",
                    type: "warning",
                     offset: 20,
                });
            }
        },

        onMediaUploaded(images) {
            this.form.settings.images = images;
        },

        removeImage(index) {
            this.form.settings.images.splice(index, 1);
        },

        handleStatusChange(selectedStatus) {
            if (selectedStatus === 'active') {
                if (this.statusActive) {
                    this.statusPending = false;
                    this.statusComplete = false;
                    this.form.status = 'active';
                }
            } else if (selectedStatus === 'pending') {
                if (this.statusPending) {
                    this.statusActive = false;
                    this.statusComplete = false;
                    this.form.status = 'pending';
                }
            } else if (selectedStatus === 'completed') {
                if (this.statusComplete) {
                    this.statusActive = false;
                    this.statusPending = false;
                    this.form.status = 'completed';
                }
            }
        },

        previewForm(url) {
            if (url) {
                window.open(url, '_blank');
            }
        },

        async getCampaign(id) {
            try {
                const response = await fetch(this.restUrl + 'api/campaigns/' + id, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch campaign');
                }

                const data = await response.json();
                this.form = data.data.campaign;
                console.log(this.form, 'hello')
                // Set status checkboxes based on current status
                if (this.form.status === 'active') {
                    this.statusActive = true;
                } else if (this.form.status === 'pending') {
                    this.statusPending = true;
                } else if (this.form.status === 'completed') {
                    this.statusComplete = true;
                }
            } catch (error) {
                console.error('Error fetching campaign:', error);
                this.$notify({
                    title: "Error",
                    message: "Failed to load campaign data",
                    type: "error",
                     offset: 20,
                });
            }
        },

        async submitForm() {
            // Validate required fields

            this.submitting = true;

            try {
                const response = await fetch(this.restUrl + 'api/campaigns/' + this.$route.params.id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    },
                    body: JSON.stringify(this.form)
                });

                if (!response.ok) {
                    throw new Error('Failed to update campaign');
                }

                const data = await response.json();

                if (data.success) {
                    this.$notify({
                        title: 'Success',
                        message: 'Campaign updated successfully',
                        type: 'success',
                        offset: 20,
                    });

                    // Optionally redirect to campaigns list
                    // this.$router.push('/campaigns');
                }
            } catch (error) {
                console.error('Error updating campaign:', error);
                this.$notify({
                    title: "Error",
                    message: "An unexpected error occurred while updating the campaign.",
                    type: "error",
                     offset: 20,
                });
            } finally {
                this.submitting = false;
            }
        }
    },

    mounted() {
        this.getCampaign(this.$route.params.id);
    },
};
</script>

<style lang="scss" scoped>
.ehxdo_edit_campaign_wrapper {
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
    margin-top: 10px;

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

    .ehxdo-back-btn {
        display: flex;
        align-items: center;
        gap: 6px;
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

// Sidebar styles
.ehxdo-sidebar_campaign {
    width: 290px;
    flex-shrink: 0;
}

// Upload styles
.ehxdo-upload-image {
    min-height: 200px;
}

// Status styles
.ehxdo-status-group {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.ehxdo-status-checkbox {
    margin-right: 0px !important;

    :deep(.el-checkbox__label) {
        width: 100%;
        padding: 12px 16px !important;
        border: 1px solid #e8e8e8 !important;
        border-radius: 8px !important;
        transition: all 0.3s;
        margin-left: 16px;
        display: flex;
        gap: 16px;
        align-items: center;

        &:hover {
            background-color: #f5f7fa;
            border-color: #f5f7fa !important;
        }
    }

    :deep(.el-checkbox__input.is-checked+.el-checkbox__label) {
        background-color: #f5f7fa !important;
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
        background-color: #00A63E;
        border-color: #00A63E;
        color: white;

        &:hover {
            background-color: #85ce61;
            border-color: #85ce61;
        }
    }
}

.ehxdo-action-preview-button {
    width: 100%;
    margin-top: 16px;
    margin-left: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    font-weight: 500;
    font-size: 14px;
    background-color: #FFFFFF !important;
    border-color: #E4E7EC !important;
    color: #344054 !important;

    &:hover {
        background-color: #F6F8FA !important;
        border-color: #E4E7EC !important;
    }
}

.ehxdo-action-can-button {
    width: 100%;
    margin-top: 16px;
    margin-left: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    font-weight: 500;
    font-size: 14px;
    background-color: #F6F8FA !important;
    border-color: #F6F8FA !important;
    color: #667085 !important;

    &:hover {
        background-color: #F6F8FA !important;
        border-color: #e4e7ed !important;
    }
}

// Donation styles
.ehxdo-checkbox {
    font-size: 15px;
    font-weight: 500;
    color: #1a1a1a !important;
}

:deep(.ehxdo-checkbox.is-checked .el-checkbox__label) {
    color: #00A63E !important;
}

.ehxdo-description {
    font-size: 13px;
    color: #666;
    margin: 0 0 16px 0;
    margin-left: 26px;
    line-height: 1.5;
}

.ehxdo-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-left: 26px;
}

.ehxdo-col {
    width: 100%;
}

.ehxdo-pricing-table {
    margin-top: 16px;
    background: #F8F9FC;
    border-radius: 16px;
    padding: 16px;
    margin-left: 26px;
}

.ehxdo-table-header {
    display: grid;
    grid-template-columns: 1fr 1fr 40px;
    gap: 12px;
    margin-bottom: 12px;
    padding: 0 4px;
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
    color: #68696b;
    padding: 8px;
    font-size: 16px;
    transition: all 0.3s ease;

    &:hover {
        color: #f56c6c;
        background: #fff !important;
        border-radius: 6px !important;
    }
}

.ehxdo-add-btn {
    margin-top: 8px;
    font-weight: 500;
    border-radius: 6px;
    color: #067a3b;
    font-size: 14px;

    &:hover {
        background: transparent !important;
        color: #00A63E;
    }
}

:deep(.el-checkbox__inner) {
    height: 15px !important;
    width: 15px !important;
    border: 2px solid #35343480 !important;
    border-radius: 4px !important;
    transition:
        border-color 0.25s cubic-bezier(.71, -.46, .29, 1.46),
        background-color 0.25s cubic-bezier(.71, -.46, .29, 1.46),
        outline 0.25s cubic-bezier(.71, -.46, .29, 1.46);

    &::after {
        border: 2.5px solid transparent !important;
        border-left: 0 !important;
        border-top: 0 !important;
        height: 8px !important;
        width: 4px !important;
        border-color: #fff !important;
    }
}

:deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
    background-color: #00A63E !important;
    border-color: #00A63E !important;
}

:deep(.el-checkbox__label) {
    color: #1a1a1a !important;
    font-weight: 500 !important;
    padding-left: 12px !important;
    transition: all 0.3s ease;

    &:hover {
        color: #00A63E !important;
    }
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

    .ehxdo-row {
        grid-template-columns: 1fr;
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

:deep(.el-form-item) {
    display: block !important;
}
</style>