<template>
    <div class="campaign-component">
        <el-button type="primary" @click="openDialog">Add Campaign</el-button>
        <el-dialog title="Add Campaign" v-model="dialogVisible" width="500px">
            <el-form ref="campaignForm" :model="form" :rules="rules" label-width="140px">
                <el-form-item label="Title" prop="title">
                    <el-input v-model="form.title" placeholder="Enter campaign title" />
                </el-form-item>

                <el-form-item label="Goal Amount" prop="goal_amount">
                    <el-input v-model.number="form.goal_amount" type="number" placeholder="Enter goal amount" />
                </el-form-item>

                <el-form-item label="Currency" prop="currency">
                    <el-select v-model="form.currency" placeholder="Select currency">
                        <el-option v-for="(item, index) in currencies" :key="index" :label="item" :value="index" />
                    </el-select>
                </el-form-item>

                <el-form-item label="Categories" prop="categories">
                    <el-select v-model="form.categories" multiple placeholder="Select categories">
                        <el-option v-for="item in categories" :key="item?.term_id" :label="item.name"
                            :value="item?.term_id" />
                    </el-select>
                </el-form-item>

                <el-form-item label="Tags" prop="tags">
                    <el-select v-model="form.tags" multiple placeholder="Select tags">
                        <el-option v-for="(item, index) in tags" :key="item.term_id" :label="item.name"
                            :value="item.term_id" />
                    </el-select>
                </el-form-item>

                <el-form-item label="Short Description" prop="short_description">
                    <el-input v-model="form.short_description" type="textarea" placeholder="Enter short description" />
                </el-form-item>
            </el-form>

            <template #footer>
                <el-button @click="dialogVisible = false">Cancel</el-button>
                <el-button type="primary" @click="submitForm">Save</el-button>
            </template>
        </el-dialog>
    </div>
</template>

<script>
export default {
    name: "AddCampaign",
    data() {
        return {
            dialogVisible: false,
            nonce: window.EHXDonate.restNonce,
            restUrl: window.EHXDonate.restUrl,
            categories: window.EHXDonate.categories,
            tags: window.EHXDonate.tags,
            currencies: window.EHXDonate.currencies,
            form: {
                title: "",
                goal_amount: null,
                currency: "",
                short_description: "",
                template: "default",
                categories: [],
                tags: [],
                settings: {
                    "allow_custom_amount": true,
                    "min_donation": 5,
                    "max_donation": 1000,
                    "predefined_pricing": true,
                    "pricing_items": [
                        { "name": "Basic", "amount": 5 },
                        { "name": "Premium", "amount": 10 }
                    ],
                    "allow_recurring_amount": true,
                    "images": [],
                }
            },
            rules: {
                title: [
                    { required: true, message: "Title is required", trigger: "blur" },
                ],
                currency: [
                    { required: true, message: "Please select a currency", trigger: "change" },
                ],
                goal_amount: [
                    { required: true, message: "Goal amount is required", trigger: "blur" },
                    { type: "number", min: 1, message: "Goal amount must be greater than 0", trigger: "blur" },
                ],
                short_description: [
                    { required: true, message: "Short description is required", trigger: "blur" },
                ],
                categories: [
                    { type: "array", required: true, message: "Please select at least one category", trigger: "change" },
                ],
                tags: [
                    { type: "array", required: true, message: "Please select at least one tag", trigger: "change" },
                ],
            },
        };
    },
    methods: {
        openDialog() {
            this.dialogVisible = true;
        },
        submitForm() {
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
                                this.dialogVisible = false;
                                this.$router.push({ name: 'edit_campaign', params: { id: data.data.campaign.id } });
                            }
                        })
                        .catch(err => console.error('Error:', err));
                }
            });
        },
        resetForm() {
            this.$refs.campaignForm.resetFields();
        },
    },
};
</script>

<style scoped>
.campaign-component {
    padding: 1rem;
}
</style>