<template>
    <div class="ehxd_wrapper">

        <AppModal :title="'Add New Campaign'" :width="700" :showFooter="false" ref="add_campaign_modal">
            <template #body>
                <!-- <AddCampaign @updateDataAfterNewAdd="handleAddedCampaign" /> -->
            </template>
        </AppModal>

        <AppTable :tableData="campaigns" v-loading="loading">
          
            <template #header>
                <div class="ehxd_title">
                    <h1 class="table-title">All Campaign</h1>
                </div>
                <el-button @click="openCampaignAddModal()" size="large" type="primary" icon="Plus" class="ltm_button">
                    Add New Campaign
                </el-button>
            </template>

            <template #body>
                <div class="ehxdo-stats-grid">
                    <div class="ehxdo-stat-card">
                        <div class="ehxdo-stat-header">
                            <span class="ehxdo-stat-label">Campaign Active</span>
                            <span class="ehxdo-stat-icon">
                                <div class="ehxdo_icon_box">
                                    <el-icon class="ehxdo_icon">
                                        <SuccessFilled />
                                    </el-icon>
                                </div>
                            </span>
                        </div>
                        <div class="ehxdo-stat-value">1,263</div>
                        <div class="ehxdo-stat-change"><span class="ehxdo_positive">8.5%</span> from last month</div>
                    </div>

                    <div class="ehxdo-stat-card">
                        <div class="ehxdo-stat-header">
                            <span class="ehxdo-stat-label">Campaign Pending</span>
                            <span class="ehxdo-stat-icon">
                                <div class="ehxdo_icon_box">
                                    <el-icon class="ehxdo_icon">
                                        <RemoveFilled />
                                    </el-icon>
                                </div>
                            </span>
                        </div>
                        <div class="ehxdo-stat-value">32</div>
                        <div class="ehxdo-stat-change "><span class="ehxdo_negative">- 8.3%</span> from last month</div>
                    </div>

                    <div class="ehxdo-stat-card">
                        <div class="ehxdo-stat-header">
                            <span class="ehxdo-stat-label">Campaign Completed</span>
                            <span class="ehxdo-stat-icon">
                                <div class="ehxdo_icon_box">
                                    <el-icon class="ehxdo_icon">
                                        <SuccessFilled />
                                    </el-icon>
                                </div>
                            </span>
                        </div>
                        <div class="ehxdo-stat-value">936</div>
                        <div class="ehxdo-stat-change"> <span class="ehxdo_negative">8.3%</span> from last month</div>
                    </div>

                </div>

            </template>

            <template #filter>
                <el-input class="ehxd-search-input ehxd_input" v-model="search" style="width: 250px" size="medium"
                    placeholder="Search" prefix-icon="Search" />

                <div>
                    <el-select v-model="status_filter" size="medium" style="margin-left: 16px; width: 140px;">
                        <el-option label="All Status" :value="undefined"></el-option>
                        <el-option label="Active" value="active"></el-option>
                        <el-option label="Pending" value="pending"></el-option>
                        <el-option label="Completed" value="completed"></el-option>
                    </el-select>
                    <el-button @click="getAllCampaigns()" class="ehxdo_export_btn" size="medium" type="primary" style="">
                        <!-- <el-icon class="ehxdo_ex_icon"><Bottom /></el-icon> -->

                       Export CSV</el-button>
                </div>

            </template>

            <template #columns>
                <!-- <el-table :data="campaigns" style="width: 100%" :default-sort="{ prop: 'goal', order: 'descending' }"
                    @sort-change="handleSortChange"> -->
                <!-- Sortable columns -->
                <el-table-column prop="id" label="ID" width="80" />
                <el-table-column prop="name" label="Title" />
                <el-table-column prop="goal" label="Goal" />
                <el-table-column prop="donation" label="Donation" />
                <el-table-column prop="raised" label="Raised" />

                <!-- Status column -->
                <el-table-column prop="status" label="Status" />
                <!-- </el-table> -->
            </template>

            <template #footer>
                <div class="ehxd_footer_page">
                    <p>Page {{ currentPage }} of {{ last_page }}</p>
                </div>

                <el-pagination v-model:current-page="currentPage" v-model:page-size="pageSize"
                    :page-sizes="[10, 20, 30, 40]" large :disabled="total_campaign <= pageSize" background
                    layout="total, sizes, prev, pager, next" :total="+total_campaign" />
            </template>

        </AppTable>

        <AppModal :title="'Update campaign'" :width="700" :showFooter="false" ref="update_campaign_modal">
            <template #body>
                <div>
                    <!-- <AddCampaign ref="addCampaign" :campaigns_data="campaign"
                        @updateDataAfterNewAdd="handleUpdatedCampaign" /> -->
                </div>
            </template>
            <template #footer>

            </template>
        </AppModal>


        <AppModal :title="'Delete campaign'" :width="500" :showFooter="false" ref="delete_campaign_modal">
            <template #body>

                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this campaign</p>
                </div>
            </template>
            <template #footer>
                <div class="exd-modal-footer" style="text-align: center;">
                    <el-button @click="$refs.delete_campaign_modal.handleClose()" type="default"
                        size="medium">Cancel</el-button>
                    <el-button @click="deleteCampaign" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>

    </div>
</template>

<script>
import axios from "axios";

import AppTable from "../../components/AppTable.vue";
import Icon from "../../components/Icons/AppIcon.vue";
import AppModal from "../../components/AppModal.vue";
// import AddCampaign from "./AddCampaign.vue";
export default {
    components: {
        AppTable,
        Icon,
        AppModal,
        // AddCampaign
    },
    data() {
        return {
            search: '',
            campaigns: [
                { id: 1, name: 'Education Fund', goal: 5000, donation: 2500, raised: 2600, status: 'Active' },
                { id: 2, name: 'Health Support', goal: 10000, donation: 8000, raised: 9500, status: 'Completed' },
                { id: 3, name: 'Food Relief', goal: 3000, donation: 1000, raised: 1200, status: 'Pending' },
            ],
            campaign: {},
            total_campaign: 0,
            loading: false,
            currentPage: 1,
            last_page: 1,
            pageSize: 10,
            active_id: null,
            add_campaign_modal: false,
            nonce: window.EHXDonate.nonce,
            rest_api: window.EHXDonate.ajaxUrl,
        }
    },
    watch: {
        currentPage() {
            this.getAllCampaigns();
        },
        pageSize() {
            this.currentPage = 1;
            this.getAllCampaigns();
        },
        search: {
            handler() {
                this.currentPage = 1;
                this.getAllCampaigns();
            },
            immediate: false
        },
    },

    methods: {
        openCampaignAddModal() {
            if (this.$refs.add_campaign_modal) {
                this.$refs.add_campaign_modal.openModel();
            } else {
                console.log("Modal ref not found! Ensure AppModal is rendered.");
            }
        },

        formatAddedDate(date) {
            if (!date) return '';
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },

        handleSortChange({ column, prop, order }) {
            console.log('Sorted by:', prop, order)
            this.getAllCampaigns({
                sort_by: prop,
                sort_order: order === 'ascending' ? 'asc' : 'desc'
            })
        },

        async getAllCampaigns() {
            this.loading = true;
            console.log(`${this.rest_api}`, 'rest api');
            try {
                const response = await axios.get(`${this.rest_api}/getAllCampaigns`, {
                    params: {
                        page: this.currentPage,
                        limit: this.pageSize,
                        search: this.search || '',
                    },
                    headers: {
                        'X-WP-Nonce': this.nonce
                    }
                });
                this.campaigns = response?.data?.campaigns_data;
                this.total_campaign = response?.data?.total || 0;
                this.last_page = response?.data?.last_page || 1;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.error('Error fetching couriers:',);
            }
        },

        openDeleteCampaignModal(row) {
            this.active_id = row.id;
            this.$refs.delete_campaign_modal.openModel();
        },
        async deleteCampaign() {
            // this.loading = true;
            const id = this.active_id;

            // try {
            //     const response = await axios.post(`${this.rest_api}/deleteCampaign/${id}`, {}, {
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-WP-Nonce': this.nonce
            //         }
            //     });

            //     if (response.data.success === true) {
            //         this.$notify({
            //             title: 'Success',
            //             message: 'campaign deleted successfully',
            //             type: 'success',
            //         });
            //         this.getAllCampaigns();
            //         this.$refs.delete_campaign_modal.handleClose();
            //     } else {
            //         this.$notify({
            //             title: 'Error',
            //             message: 'Failed to delete campaign',
            //             type: 'error',
            //         });
            //     }

            // } catch (error) {
            //     console.error('Error deleting campaign:', error);
            //     this.$notify({
            //         title: 'Error',
            //         message: 'An unexpected error occurred while deleting the campaign.',
            //         type: 'error',
            //     });
            // } finally {
            //     this.loading = false;
            // }
        },

        openUpdateCampaignModal(row) {
            this.campaign = row;
            this.$refs.update_campaign_modal.openModel();
        },

        handleUpdatedCampaign(updated) {
            this.getAllCampaigns(); // or update the array locally
            this.$refs.update_campaign_modal.handleClose();
        },

        handleAddedCampaign(newCampaign) {
            this.getAllCampaigns();
            // this.$refs.add_campaign_modal.handleClose();
        }

    },

    mounted() {
        console.log('window', window);
        this.getAllCampaigns();
    },

}
</script>

<style lang="scss" scoped>
.ehxdo_export_btn{
    margin-left: 8px;
    background: #fff !important;
    color: #666D80;
    font-weight: normal;
    border: 1px solid #DFE1E7 !important;
    transition: all 0.3s ease;

    &:hover {
        background: #F8F9FC !important;
    }
}
.ehxdo_ex_icon {
    font-size: 16px;
    
}

.ehxdo-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
    margin-top: 10px;

}

.ehxdo-stat-card {
    background: white;
    border-radius: 16px;
    padding: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.ehxdo-stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.ehxdo-stat-label {
    font-size: 14px;
    color: #666D80;
    font-weight: 500;
}

.ehxdo-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1px solid #DFE1E7;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;

    .ehxdo_icon_box {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ehxdo_icon {
        margin: 0 auto;
        font-size: 20px;
        color: #079455;
    }

}

.ehxdo-stat-value {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #0D0D12;
}

.ehxdo-stat-change {
    font-size: 14px;
    font-weight: 500;
    color: #666D80;

    .ehxdo_negative {
        background: #FFF0F3;
        padding: 2px 8px;
        color: #DF1C41;
        margin-right: 8px;
        line-height: 30px;
        font-weight: 500;
        border-radius: 50px;
    }

    .ehxdo_positive {
        background: #F0FDF4;
        padding: 2px 8px;
        color: #00A63E;
        margin-right: 8px;
        line-height: 30px;
        font-weight: 500;
        border-radius: 50px;
    }
}
</style>