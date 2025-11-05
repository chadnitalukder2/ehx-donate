<template>
    <div class="ehxdo-form-header" style="display: flex; justify-content: space-between; align-items: center;">
        <el-button @click="$router.back()">
            <el-icon>
                <ArrowLeft />
            </el-icon>
            Back
        </el-button>
        <div class="left-actions">
            <el-button type="primary" @click="submitForm">Save</el-button>
            <el-button type="info" @click="previewForm(form?.post?.guid)">Preview</el-button>
        </div>
    </div>
    <div class="ehxdo-form-wrapper" v-if="Object.keys(form).length > 0" style="display: flex; gap: 20px;">
        <el-form :model="form" label-position="top" class="ehxdo-form">
            <!-- Section: Campaign Information -->
            <div class="ehxdo-section">
                <h3 class="ehxdo-section__title">Campaign Information</h3>

                <el-form-item label="Campaign Title" required>
                    <el-input v-model="form.title" />
                </el-form-item>

                <el-form-item label="Description" required>
                    <div class="ehxdo-editable-textarea">
                        <div class="ehxdo-editable-header">
                            <el-button icon="el-icon-bold" link />
                            <el-button icon="el-icon-link" link />
                            <el-button icon="el-icon-picture" link />
                        </div>
                        <el-input type="textarea" :rows="4" v-model="form.description" />
                    </div>
                </el-form-item>

                <el-form-item label="Short Description">
                    <el-input type="textarea" :maxlength="200" show-word-limit rows="2"
                        placeholder="Say something about your campaign..." v-model="form.short_description" />
                </el-form-item>

                <el-form-item label="Choose keywords that describe your fundraising goal">
                    <el-select v-model="form.tags" multiple collapse-tags collapse-tags-tooltip placeholder=""
                        style="width: 100%">
                        <el-option v-for="item in tagOptions" :key="item.term_id" :label="item.name" :value="item.term_id" />
                    </el-select>
                </el-form-item>

                <el-form-item label="Choose a category that best describes your fundraising goal">
                    <el-select v-model="form.categories" multiple collapse-tags collapse-tags-tooltip placeholder=""
                        style="width: 100%">
                        <el-option v-for="item in categories" :key="item.term_id" :label="item.name" :value="item.term_id" />
                    </el-select>
                </el-form-item>
            </div>

            <!-- Section: Donation Information -->
            <div class="ehxdo-section">
                <h3 class="ehxdo-section__title">Donation Information</h3>

                <el-form-item label="Goal Amount" required>
                    <el-input v-model="form.goal_amount" placeholder="$ 100,000.00" />
                </el-form-item>

                <!-- Checkbox: Allow Custom Amount -->
                <el-form-item class="ehxdo-form__checkbox">
                    <el-checkbox v-model="form.settings.allow_custom_amount">Allow Custom Amount</el-checkbox>
                    <div class="ehxdo-hint">With the Allow Custom Amount feature, users can set their own payment.</div>

                    <div v-if="form.settings.allow_custom_amount" class="ehxdo-input-group">
                        <el-input v-model="form.settings.min_donation" placeholder="Minimum Donation" />
                        <el-input v-model="form.settings.max_donation" placeholder="Maximum Donation" />
                    </div>
                </el-form-item>

                <!-- Checkbox: Predefined Pricing -->
                <el-form-item class="ehxdo-form__checkbox">
                    <el-checkbox v-model="form.settings.predefined_pricing">Predefined Pricing</el-checkbox>
                    <div class="ehxdo-hint">Enable this option to allow commission payments on recurring product
                        subscriptions.</div>

                    <div class="ehxdo-pricing-box" v-if="form.settings.predefined_pricing">
                        <div class="ehxdo-pricing-header">
                            <span>Name</span>
                            <span>Amount</span>
                        </div>
                        <div class="ehxdo-pricing-row" v-for="(item, index) in form.settings.pricing_items"
                            :key="index">
                            <el-input v-model="item.name" placeholder="Name" />
                            <el-input v-model="item.amount" placeholder="$" />
                            <el-button icon="el-icon-delete" circle @click="removePricing(index)" />
                        </div>
                        <el-button type="primary" link @click="addPricing">+ Add More</el-button>
                    </div>
                </el-form-item>

                <!-- Checkbox: Recurring -->
                <el-form-item class="ehxdo-form__checkbox">
                    <el-checkbox v-model="form.settings.allow_recurring_amount">Allow Recurring Amount</el-checkbox>
                    <div class="ehxdo-hint">Enable this option to allow commission payments on recurring product
                        subscriptions.</div>
                </el-form-item>
            </div>
        </el-form>
        <div class="ehxdo-form__form-left">
            <DraggableFileUpload 
                :attachments="form.settings.images" 
                @mediaUploaded="onMediaUploaded"
                @removeImage="removeImage"
            />
        </div>
    </div>
</template>

<script>
import DraggableFileUpload from "../../components/DraggableFileUpload.vue";
export default {
    name: "CampaignForm",
    components: {
        DraggableFileUpload
    },
    data() {
        return {
            tagOptions: window.EHXDonate.tags,
            nonce: window.EHXDonate.restNonce,
            restUrl: window.EHXDonate.restUrl,
            categories: window.EHXDonate.categories,
            form: {
            },
        };
    },
    methods: {
        addPricing() {
            this.form.settings.pricing_items.push({ name: "", amount: null });
        },
        removePricing(index) {
            this.form.settings.pricing_items.splice(index, 1);
        },
        onMediaUploaded(images) {
            this.form.settings.images = images;
        },
        removeImage(index) {
            this.form.settings.images.splice(index, 1);
        },
        previewForm(url) {
            window.open(url, '_blank');
        },
        getCampaign(id) {
            // fetch campaign data from server
            fetch(this.restUrl + 'api/campaigns/' + id, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': this.nonce
                },
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Something went wrong');
                    }
                    // Parse response JSON
                    // console.log('Response:', res.json());
                    return res.json();
                })
                .then(data => {
                    console.log('Response data jj:', data.data.campaign);
                    this.form = data.data.campaign;
                })
                .catch(err => console.error('Error:', err));
        },

        submitForm() {
            fetch(this.restUrl + 'api/campaigns/' + this.$route.params.id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': this.nonce
                },
                body: JSON.stringify(this.form)
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Something went wrong');
                    }
                    // Parse response JSON
                    // console.log('Response:', res.json());
                    return res.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    // Example: access response fields
                    // data.success, data.message, data.data.campaign
                    if (data.success) {
                        this.$notify({
                            title: 'Success',
                            message: 'Campaign updated successfully',
                            type: 'success',
                        });
                    }
                })
                .catch(err => console.error('Error:', err));
        }

    },

    mounted() {
        this.getCampaign(this.$route.params.id);
    },
};
</script>

<style lang="scss" scoped>
.ehxdo-form-wrapper {
    max-width: 1040px;
    margin: auto;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
}

.ehxdo-form {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    width: calc(100% - 300px);

    .ehxdo-section {
        padding-bottom: 30px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 30px;

        &__title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }
    }

    .ehxdo-form__checkbox {
        margin-top: 20px;
    }

    .ehxdo-hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .ehxdo-input-group {
        display: flex;
        gap: 10px;
        margin-top: 10px;

        .el-input {
            flex: 1;
        }
    }

    .ehxdo-editable-textarea {
        .ehxdo-editable-header {
            display: flex;
            gap: 10px;
            margin-bottom: 6px;
        }
    }

    .ehxdo-pricing-box {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 16px;
        margin-top: 10px;

        .ehxdo-pricing-header {
            display: flex;
            justify-content: space-between;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .ehxdo-pricing-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;

            .el-input {
                flex: 1;
            }

            .el-button {
                margin-left: auto;
            }
        }
    }
}

.ehxdo-form__form-left {
    width: 300px;
}
</style>