<template>
    <div class="ehxd_wrapper">

        <AppTable :tableData="campaigns" v-loading="loading">

            <template #header>
                <div class="ehxd_title">
                    <h1 class="table-title">All Campaign</h1>
                </div>
                <AddCampaignModal />
            </template>

            <template #body>
                <div class="ehxdo-stats-grid">
                    <div class="ehxdo-stat-card">
                        <div class="ehxdo-stat-header">
                            <span class="ehxdo-stat-label">Campaign Active</span>
                            <span class="ehxdo-stat-icon">
                                <div class="ehxdo_icon_box">
                                    <!-- <el-icon class="ehxdo_icon">
                                        <SuccessFilled />
                                    </el-icon> -->
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.0003 18.3333C5.39795 18.3333 1.66699 14.6023 1.66699 9.99996C1.66699 5.39759 5.39795 1.66663 10.0003 1.66663C14.6027 1.66663 18.3337 5.39759 18.3337 9.99996C18.3337 14.6023 14.6027 18.3333 10.0003 18.3333ZM10.8337 5.41663C10.8337 4.95639 10.4606 4.58329 10.0003 4.58329C9.54009 4.58329 9.16699 4.95639 9.16699 5.41663V5.9369C7.97486 6.24058 7.08366 7.18021 7.08366 8.33329C7.08366 9.16378 7.37486 9.863 8.00554 10.3135C8.5802 10.724 9.31138 10.8333 10.0003 10.8333C10.5614 10.8333 10.8719 10.9323 11.0264 11.0427C11.1249 11.113 11.2503 11.2471 11.2503 11.6666C11.2503 11.9238 10.9152 12.5 10.0003 12.5C9.12098 12.5 8.88921 11.955 8.88921 11.8055C8.88921 11.3453 8.51612 10.9722 8.05588 10.9722C7.59564 10.9722 7.22255 11.3453 7.22255 11.8055C7.22255 12.8197 7.99738 13.7551 9.16699 14.0616V14.5833C9.16699 15.0435 9.54009 15.4166 10.0003 15.4166C10.4606 15.4166 10.8337 15.0435 10.8337 14.5833V14.063C12.0258 13.7593 12.917 12.8197 12.917 11.6666C12.917 10.8361 12.6258 10.1369 11.9951 9.68643C11.4204 9.27596 10.6893 9.16663 10.0003 9.16663C9.43927 9.16663 9.12878 9.06763 8.97427 8.95726C8.8758 8.88692 8.75033 8.7528 8.75033 8.33329C8.75033 8.07609 9.08542 7.49996 10.0003 7.49996C10.4382 7.49996 10.7731 7.64513 10.9864 7.8178C11.2186 8.0058 11.2503 8.16992 11.2503 8.1944C11.2503 8.65464 11.6234 9.02774 12.0837 9.02774C12.5439 9.02774 12.917 8.65464 12.917 8.1944C12.917 7.52444 12.532 6.92467 12.0351 6.52239C11.7052 6.25536 11.2973 6.04807 10.8337 5.9333V5.41663Z"
                                            fill="#079455" />
                                    </svg>

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
                                    <!-- <el-icon class="ehxdo_icon">
                                        <RemoveFilled />
                                    </el-icon> -->
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.0003 18.3333C5.39795 18.3333 1.66699 14.6023 1.66699 9.99996C1.66699 5.39759 5.39795 1.66663 10.0003 1.66663C14.6027 1.66663 18.3337 5.39759 18.3337 9.99996C18.3337 14.6023 14.6027 18.3333 10.0003 18.3333ZM6.25033 9.16663C5.79009 9.16663 5.41699 9.53972 5.41699 9.99996C5.41699 10.4602 5.79009 10.8333 6.25033 10.8333H13.7503C14.2106 10.8333 14.5837 10.4602 14.5837 9.99996C14.5837 9.53972 14.2106 9.16663 13.7503 9.16663H6.25033Z"
                                            fill="#079455" />
                                    </svg>
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
                                    <!-- <el-icon class="ehxdo_icon">
                                        <SuccessFilled />
                                    </el-icon> -->

                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.0003 18.3333C5.39795 18.3333 1.66699 14.6023 1.66699 9.99996C1.66699 5.39759 5.39795 1.66663 10.0003 1.66663C14.6027 1.66663 18.3337 5.39759 18.3337 9.99996C18.3337 14.6023 14.6027 18.3333 10.0003 18.3333ZM14.3396 8.50588C14.665 8.18045 14.665 7.65281 14.3396 7.32737C14.0141 7.00193 13.4865 7.00193 13.1611 7.32737L8.75033 11.7381L6.83958 9.82737C6.51414 9.50193 5.98651 9.50193 5.66107 9.82737C5.33563 10.1528 5.33563 10.6804 5.66107 11.0059L8.16107 13.5059C8.48651 13.8313 9.01414 13.8313 9.33958 13.5059L14.3396 8.50588Z"
                                            fill="#079455" />
                                    </svg>

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
                    <el-button @click="getAllCampaigns()" class="ehxdo_export_btn" size="medium" type="info" style="">
                        <!-- <el-icon class="ehxdo_ex_icon"><Bottom /></el-icon> -->

                        Export CSV</el-button>
                </div>

            </template>

            <template #columns>
                <el-table-column label="ID" width="80">
                    <template #default="scope">
                        {{ scope.$index + 1 }}
                    </template>
                </el-table-column>
                <el-table-column prop="title" label="Title">
                    <template #default="{ row }">
                        <router-link :to="{ name: 'edit_campaign', params: { id: row.id } }" class="ehxdo-title-link">
                            {{ row.title }}
                        </router-link>
                    </template>
                </el-table-column>
                <el-table-column prop="goal_amount" label="Goal Amount" />
                <el-table-column prop="donation" label="Donation">
                    <template #default="{ row }">
                        {{ row.donation ?? 0 }}
                    </template>
                </el-table-column>

                <el-table-column prop="raised" label="Raised">
                    <template #default="{ row }">
                        {{ row.raised ?? 0 }}
                    </template>
                </el-table-column>
                <!-- Status column -->
                <el-table-column prop="status" label="Status">
                    <template #default="{ row }">
                        <el-switch v-model="row.status" v-if="row.status !== 'completed'" @change="updateStatus(row)"
                            active-value="active" inactive-value="pending" size="small"
                            style="--el-switch-on-color: #00A63E; --el-switch-off-color: #D39C3D" />

                        <span :class="[
                            'status-badge',
                            row.status === 'active' ? 'ehxdo_status-active' :
                                row.status === 'pending' ? 'ehxdo_status-pending' :
                                    row.status === 'completed' ? 'ehxdo_status-complete' : ''
                        ]">
                            {{ row.status }}
                        </span>

                    </template>
                </el-table-column>
                <el-table-column label="Actions" width="75">
                    <template #default="{ row }">
                        <div class="ehxdo_action_section">
                            <el-popover placement="bottom-end" width="100"
                                :popper-style="{ minWidth: '100px', borderRadius: '16px' }"
                                popper-class="ehxdo-action-popover" trigger="click" v-model:visible="row.showActions">
                                <div class="action-popup">
                                    <el-button type="text" @click="editCampaign(row)" class="ehxdo_edit"> <el-icon>
                                            <EditPen />
                                        </el-icon> Edit</el-button>
                                    <el-button type="text" @click="viewCampaign(row)" class="ehxdo_view"> <el-icon>
                                            <View />
                                        </el-icon> View</el-button>
                                    <el-button type="text" @click="openDeleteCampaignModal(row)" class="ehxdo_delete">
                                        <el-icon>
                                            <DeleteFilled />
                                        </el-icon> Delete</el-button>
                                </div>
                                <template #reference>
                                    <el-button icon="More" circle size="small"></el-button>
                                </template>
                            </el-popover>
                        </div>
                    </template>
                </el-table-column>
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


        <AppModal :title="'Delete campaign'" :width="500" :showFooter="false" ref="delete_campaign_modal">
            <template #body>

                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this campaign</p>
                </div>
            </template>
            <template #footer>
                <div class="exd-modal-footer" style="text-align: center;">
                    <el-button @click="$refs.delete_campaign_modal.handleClose()" type="info"
                        size="medium">Cancel</el-button>
                    <el-button @click="deleteCampaign" type="primary" size="medium"
                        style="background: #DF1C41 !important ;">Delete</el-button>
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
import AddCampaignModal from "./AddCampaignModal.vue";
export default {
    components: {
        AppTable,
        Icon,
        AppModal,
        AddCampaignModal
    },
    data() {
        return {
            search: '',
            campaigns: [],
            campaign: {},
            total_campaign: 0,
            loading: false,
            currentPage: 1,
            last_page: 1,
            pageSize: 10,
            active_id: null,
            add_campaign_modal: false,
            nonce: window.EHXDonate.restNonce,
            rest_api: window.EHXDonate.restUrl,
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
        formatAddedDate(date) {
            if (!date) return '';
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },
        editCampaign(row) {
            // Assuming row contains the campaign data
            this.$router.push({
                name: 'edit_campaign',
                params: { id: row.id }
            });
        },
        viewCampaign(row) {
            const url = row.post_url || `/campaign/${row.id}`;
            window.open(url, '_blank');
        },
        openCampaignAddPage() {
            this.$router.push({ name: 'add_campaign' });
        },

        async getAllCampaigns() {
            this.loading = true;
            try {
                const response = await axios.get(`${this.rest_api}api/getAllCampaigns`, {
                    params: {
                        page: this.currentPage,
                        limit: this.pageSize,
                        search: this.search || '',
                        status: this.status_filter || '',
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    },
                    withCredentials: true
                });
                console.log('Campaigns response:', response.data.data.campaigns);
                this.campaigns = response?.data?.data?.campaigns;
                // this.total_campaign = response?.data?.total || 0;
                // this.last_page = response?.data?.last_page || 1;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.error('Error fetching couriers:', error);
            }
        },

        openDeleteCampaignModal(row) {
            this.active_id = row.id;
            this.$refs.delete_campaign_modal.openModel();
        },
        async deleteCampaign() {
            // this.loading = true;
            const id = this.active_id;

            try {
                const response = await axios.delete(`${this.rest_api}api/deleteCampaign/${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    }
                });

                if (response.data.success === true) {
                    this.$notify({
                        title: 'Success',
                        message: 'campaign deleted successfully',
                        type: 'success',
                    });
                    this.getAllCampaigns();
                    this.$refs.delete_campaign_modal.handleClose();
                } else {
                    this.$notify({
                        title: 'Error',
                        message: 'Failed to delete campaign',
                        type: 'error',
                    });
                }

            } catch (error) {
                console.error('Error deleting campaign:', error);
                this.$notify({
                    title: 'Error',
                    message: 'An unexpected error occurred while deleting the campaign.',
                    type: 'error',
                });
            } finally {
                this.loading = false;
            }
        },

        async updateStatus(row) {
            try {
                await this.$confirm(
                    `Are you sure you want to change the status to "${row.status}"?`,
                    'Confirm Status Update',
                    {
                        confirmButtonText: 'Yes, Update',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                    }
                );

                console.log('Updating status for row:', row);
                const response = await axios.post(
                    `${this.rest_api}api/updateCampaignStatus/${row.id}`,
                    {
                        status: row.status
                    },
                    {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': this.nonce
                        }
                    }
                );

                if (response.data.success) {
                    this.$notify({
                        title: 'Success',
                        message: 'Status updated!',
                        type: 'success'
                    });
                } else {
                    throw new Error("Failed");
                }
            } catch (error) {
                this.$notify({
                    title: 'Error',
                    message: 'Could not update status!',
                    type: 'error'
                });

                // revert switch UI
                row.status = row.status === "active" ? "pending" : "active";
            }
        },

        handleAddedCampaign(newCampaign) {
            this.getAllCampaigns();
            // this.$refs.add_campaign_modal.handleClose();
        }

    },

    mounted() {
        console.log('window', this.campaigns);
        this.getAllCampaigns();
    },

}
</script>

<style lang="scss" scoped>
//action popup styles============
.ehxdo_action_section {
    .el-button {
        transition: all 0.3s ease;
        background: #F8F9FC;
        color: #0D0D12;
    }

    .el-button:hover {
        background-color: #fff;
        border: 1px solid #DFE1E7;
    }
}


.action-popup {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;

    .el-button+.el-button {
        margin-left: 0px;
    }

    .el-button--text {
        color: #666D80;
        font-weight: 500;
        font-size: 12px;
        width: 100%;
        justify-content: flex-start;
        display: flex;
        transition: background-color 0.3s ease;
    }

    span {
        i {
            margin-right: 8px;
        }
    }


    .ehxdo_edit {
        &:hover {
            color: #079455;
        }
    }

    .ehxdo_view {
        &:hover {
            color: #3366FF;
        }
    }

    .ehxdo_delete {
        &:hover {
            color: #DF1C41;
        }
    }
}


//status===============
.status-badge {
    border-radius: 16px;
    text-transform: capitalize;
    font-size: 12px;
    font-weight: 500;
    margin-left: 8px;
    padding: 3px 8px 4px 8px;
}

.ehxdo_status-active {
    color: #339D88;
    border: 1px solid #339D88;

    &::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #339D88;
        border-radius: 50%;
        margin-right: 4px;
        margin-bottom: 2px;
        vertical-align: middle;
    }
}

.ehxdo_status-pending {
    border: 1px solid #D39C3D;
    color: #D39C3D;

    &::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #D39C3D;
        border-radius: 50%;
        margin-right: 4px;
        margin-bottom: 2px;
        vertical-align: middle;
    }
}

.ehxdo_status-complete {
    border: 1px solid #6B39F4;
    color: #6B39F4;

    &::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #6B39F4;
        border-radius: 50%;
        margin-right: 4px;
        margin-bottom: 2px;
        vertical-align: middle;
    }
}

//export button style============
.ehxdo_export_btn {
    margin-left: 8px;
    background: #fff !important;
    color: #666D80 !important;
    font-weight: normal !important;
    font-size: 14px !important;
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
    padding: 16px;
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

.ehxd-table-filter {}

:deep(.el-select__wrapper) {
    min-height: 36px !important;
}

:deep(.el-input__wrapper) {
    height: 38px !important;
}

:deep(.el-input__wrapper .el-input__inner) {
    height: 38px !important;
}
</style>