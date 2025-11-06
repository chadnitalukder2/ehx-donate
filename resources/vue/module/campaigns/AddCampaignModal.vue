<template>
    <div class="campaign-component">
        <el-button type="primary" @click="openDialog"><el-icon style="font-size: 16px; margin-right: 6px;">
                <Plus />
            </el-icon> Create Campaign</el-button>

        <el-dialog title="Create Campaign" v-model="dialogVisible" width="600px">
            <div class="ehxdo_modal_campaign">
                <el-form ref="campaignForm" :model="form" :rules="rules">
                    <el-form-item label="Campaign Title" prop="title">
                        <el-input v-model="form.title" placeholder="Enter campaign title" />
                    </el-form-item>


                    <el-form-item label="Short Description" prop="short_description">
                        <el-input v-model="form.short_description" :maxlength="250" show-word-limit :rows="4"
                            type="textarea" placeholder="Say something about your campaign..." />
                    </el-form-item>

                    <el-form-item label="Goal Amount" prop="goal_amount">
                        <el-input v-model.number="form.goal_amount" type="number" placeholder="Enter goal amount" />
                    </el-form-item>

                    <!-- <el-form-item label="Currency" prop="currency">
                    <el-select v-model="form.currency" placeholder="Select currency">
                        <el-option v-for="(item, index) in currencies" :key="index" :label="item" :value="index" />
                    </el-select>
                </el-form-item> -->

                    <el-form-item label="Categories" prop="categories">
                        <!-- <el-select v-model="form.categories" multiple placeholder="Select categories">
                        <el-option v-for="item in categories" :key="item?.term_id" :label="item.name"
                            :value="item?.term_id" />
                    </el-select> -->
                        <el-select-v2 v-model="form.categories"
                            :options="categories.map(item => ({ label: item.name, value: item.term_id }))" multiple
                            placeholder="Select categories" filterable clearable allow-create style="width: 100%;" />
                    </el-form-item>

                    <!-- <el-form-item label="Tags" prop="tags">
                    <el-select v-model="form.tags" multiple placeholder="Select tags">
                        <el-option v-for="(item, index) in tags" :key="item.term_id" :label="item.name"
                            :value="item.term_id" />
                    </el-select>
                </el-form-item> -->

                    <el-form-item label="Thumbnail Image" prop="header_image">
                        <div class="ehxdo-upload-image" style="overflow: hidden;">
                            <AppFileUpload v-model:selectedFile="form.header_image" btnTitle="Add Media" />
                        </div>
                    </el-form-item>




                </el-form>
            </div>
            <template #footer>
                <el-button @click="dialogVisible = false" type="info">Cancel</el-button>
                <el-button :loading="submitting" type="primary" @click="submitForm">Create Campaign</el-button>
            </template>

        </el-dialog>
    </div>
</template>

<script>
import AppFileUpload from "../../components/AppFileUpload.vue";
export default {
    name: "AddCampaign",
    components: {
        AppFileUpload
    },
    data() {
        return {
            submitting: false,
            dialogVisible: false,
            nonce: window.EHXDonate.restNonce,
            restUrl: window.EHXDonate.restUrl,
            categories: window.EHXDonate.categories,
            tags: window.EHXDonate.tags,
            currencies: window.EHXDonate.currencies,
            form: {
                title: "",
                goal_amount: null,
                short_description: "",
                template: "default",
                status: "active",
               start_date: new Date().toISOString().split('T')[0],
                end_date: null,
                categories: [],
                tags: [],
                header_image: "",
                settings: {
                    "allow_custom_amount": true,
                    "min_donation": 5,
                    "max_donation": 1000,
                    "predefined_pricing": true,
                    "description": '',
                    "pricing_items": [
                        { "name": "Basic", "amount": 5 },
                        { "name": "Premium", "amount": 10 },
                        { "name": "Premium", "amount": 15 }
                    ],
                    "allow_recurring_amount": false,
                    "images": [],
                }
            },
            rules: {
                title: [
                    { required: true, message: "Title is required", trigger: "blur" },
                ],
                goal_amount: [
                    { required: true, message: "Goal amount is required", trigger: "blur" },
                    { type: "number", min: 1, message: "Goal amount must be greater than 0", trigger: "blur" },
                ],
                short_description: [
                    { required: true, message: "Short Description is required", trigger: "blur" },
                ],

            },
        };
    },
    methods: {
        openDialog() {
            this.dialogVisible = true;
        },
        submitForm() {
            this.submitting = true;

            this.$refs.campaignForm.validate((valid) => {
                if (valid) {
                    fetch(this.restUrl + 'api/campaigns', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': this.nonce
                        },
                        body: JSON.stringify(this.form)
                    })
                        .then(res => {
                            if (!res.ok) throw new Error('Something went wrong');
                            return res.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (data.success) {
                                this.$notify({
                                    title: 'Success',
                                    message: 'Campaign created successfully',
                                    type: 'success',
                                    offset: 20,
                                });
                                this.dialogVisible = false;
                                this.$router.push({ name: 'edit_campaign', params: { id: data.data.campaign.id } });
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                        })
                        .finally(() => {
                            this.submitting = false;
                        });
                } else {
                    this.submitting = false;
                }
            });
        },
        resetForm() {
            this.$refs.campaignForm.resetFields();
        },
    },
};
</script>

<style scoped lang="scss">
:deep(.el-overlay-dialog) {
    top: -55px !important;
}

:deep(.el-dialog) {
    padding: 30px 15px 30px 30px !important;
    border-radius: 16px !important;
}

:deep(.el-dialog__header) {
    border-bottom: 1px solid #DFE1E7;
    margin-bottom: 16px;
    padding-bottom: 16px;
    margin-right: 15px;
}

:deep(.el-dialog__footer) {
    margin-top: 15px;
    margin-right: 15px;
    border-top: 1px solid #DFE1E7;
}

:deep(.el-dialog__headerbtn) {
    font-size: 20px !important;
    height: 80px !important;
    width: 77px !important;

    &:hover {
        color: #0D0D12 !important;
    }
}

:deep(.el-dialog__title) {
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #0D0D12 !important;
}

:deep(.el-form-item) {
    display: block !important;
    margin-bottom: 20px !important;
}

.ehxdo_modal_campaign {
    padding-right: 15px;
    height: 430px;
    overflow: auto;
    // scrollbar-width: thin;
    // scrollbar-color: #f5f7fa #e7e7e7;
}

.ehxdo_modal_campaign::-webkit-scrollbar {
    width: 8px;
}

.ehxdo_modal_campaign::-webkit-scrollbar-track {
    background: #f5f7fa;
    border-radius: 10px;
}

.ehxdo_modal_campaign::-webkit-scrollbar-thumb {
    background-color: #d9dada;
    border-radius: 10px;
    border: 2px solid #f5f7fa;
}

.ehxdo_modal_campaign::-webkit-scrollbar-thumb:hover {
    background-color: #d9dada;
}
</style>