<template>
    <div class="ehxd_wrapper">

        <AppTable :tableData="donors" v-loading="loading">

            <template #header>
                <div class="ehxd_title">
                    <h1 class="table-title">All Donors</h1>
                </div>
            </template>

            <template #filter>
                <el-input class="ehxd-search-input ehxd_input" v-model="search" style="width: 250px" size="medium"
                    placeholder="Search" prefix-icon="Search" />

                <div>
                    <el-button @click="getAllDonors()" class="ehxdo_export_btn" size="medium" type="info" style="">
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
                <el-table-column prop="name" label="Name">
                    <template #default="{ row }">
                        {{ row.first_name }} {{ row.last_name }}
                    </template>
                </el-table-column>
                <el-table-column prop="email" label="Email">
                    <template #default="{ row }">
                        {{ row.email }}
                    </template>
                </el-table-column>


                <el-table-column prop="donation" label="Donation">
                    <template #default="{ row }">
                        {{ row.donation ?? 0 }}
                    </template>
                </el-table-column>

                <el-table-column prop="phone" label="Phone">
                    <template #default="{ row }">
                        {{ row.phone ? row.phone : '---' }}
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
                                    <el-button type="text" @click="opendeleteDonorModal(row)" class="ehxdo_delete">
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


        <AppModal :title="'Delete Donor'" :width="500" :showFooter="false" ref="delete_campaign_modal">
            <template #body>

                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this donor</p>
                </div>
            </template>
            <template #footer>
                <div class="exd-modal-footer" style="text-align: center;">
                    <el-button @click="$refs.delete_campaign_modal.handleClose()" type="info"
                        size="medium">Cancel</el-button>
                    <el-button @click="deleteDonor" type="primary" size="medium"
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
export default {
    components: {
        AppTable,
        Icon,
        AppModal,
    },
    data() {
        return {
            search: '',
            donors: [],
            generalSettings: {},
            currencies: window.EHXDonate.currencies,
            currencySymbols: window.EHXDonate.currencySymbols,
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
            this.getAllDonors();
        },
        pageSize() {
            this.currentPage = 1;
            this.getAllDonors();
        },
        search: {
            handler() {
                this.currentPage = 1;
                this.getAllDonors();
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
        formatCurrency(amount) {
            const currency = this.generalSettings?.currency || 'GBP';
            const position = this.generalSettings?.currency_position || 'Before';

            const symbol = this.currencySymbols[currency] || currency;
            const formattedAmount = parseFloat(amount || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            if (position === 'Before') {
                return `${symbol} ${formattedAmount}`;
            } else {
                return `${formattedAmount} ${symbol}`;
            }
        },

        viewCampaign(row) {
            const url = row.post_url || `/campaign/${row.id}`;
            window.open(url, '_blank');
        },


        async getAllDonors() {
            this.loading = true;
            try {
                const response = await axios.get(`${this.rest_api}api/getAllDonors`, {
                    params: {
                        page: this.currentPage,
                        limit: this.pageSize,
                        search: this.search || '',
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    },
                    withCredentials: true
                });
                console.log('donors response:', response.data.data);
                this.donors = response?.data?.data?.donors;
                this.generalSettings = response?.data?.data?.generalSettings || {};
                // this.total_campaign = response?.data?.total || 0;
                // this.last_page = response?.data?.last_page || 1;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.error('Error fetching donors:', error);
            }
        },

        opendeleteDonorModal(row) {
            this.active_id = row.id;
            this.$refs.delete_campaign_modal.openModel();
        },
        async deleteDonor() {
            // this.loading = true;
            const id = this.active_id;

            try {
                // const response = await axios.delete(`${this.rest_api}api/deleteDonor/${id}`, {
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-WP-Nonce': this.nonce
                //     }
                // });

                if (response.data.success === true) {
                    this.$notify({
                        title: 'Success',
                        message: 'campaign deleted successfully',
                        type: 'success',
                    });
                    this.getAllDonors();
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
                    `Are you sure you want to change the status.`,
                    'Confirm Status Update',
                    {
                        confirmButtonText: 'Yes, Update',
                        cancelButtonText: 'Cancel',
                        type: 'warning',
                        iconClass: '',
                    }
                );

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
            this.getAllDonors();
        }

    },

    mounted() {
        console.log('Mounted AllCampaign.vue', window.EHXDonate);
        this.getAllDonors();
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

.custom-confirm-dialog .el-message-box__message {
    padding: 24px 32px;
    /* increase padding */
}

.custom-confirm-dialog .el-message-box__status {
    display: none;
    /* hides the icon */
}
</style>