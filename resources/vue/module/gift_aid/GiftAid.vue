<template>
    <div class="ehxd_wrapper">
        <AppTable :tableData="donations" v-loading="loading">

            <template #header>
                <div class="ehxd_title">
                    <h1 class="table-title">All Gift Aid</h1>
                </div>
            </template>

            <template #filter>
                <el-input class="ehxd-search-input ehxd_input" v-model="search" style="width: 250px" size="medium"
                    placeholder="Search" prefix-icon="Search" />

                <div>
                   
                    <el-button @click="exportCSV()" class="ehxdo_export_btn" :loading="export" size="medium" type="info"
                        style="">
                        <!-- <el-icon class="ehxdo_ex_icon"><Bottom /></el-icon> -->

                        Export CSV</el-button>
                </div>

            </template>

            <template #columns>
                <el-table-column prop="title" label="Title" width="80px">
                    <template #default="{ row }">
                        {{ row.title }}
                        <p class="recurring-badge" v-if="row.donation_type === 'recurring'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-refresh-cw w-3 h-3">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                <path d="M21 3v5h-5"></path>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                <path d="M8 16H3v5"></path>
                            </svg>
                            {{ getIntervalLabel(row.interval, row.interval_count) }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column prop="first_name" label="First Name" width="120px">
                    <template #default="{ row }">
                        {{ row.first_name }}
                    </template>
                </el-table-column>
                <el-table-column prop="last_name" label="Last Name" width="120px">
                    <template #default="{ row }">
                        {{ row.last_name }}
                    </template>
                </el-table-column>
                <el-table-column label="House Name or Number" width="auto">
                    <template #default="{ row }">
                        <span v-if="row.address_line_1">
                            {{ row.address_line_1 }} {{ row.city }} {{ row.state }} {{ row.country }}
                        </span>
                        <span v-else>
                            {{ row.address_line_2 }} {{ row.city }} {{ row.state }} {{ row.country }}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column prop="postal_code" label="Post Code" width="100px">
                    <template #default="{ row }">
                        {{ row.post_code ?? '---' }}
                    </template>
                </el-table-column>

                <el-table-column prop="donation_type" label="Aggregated Donations" width="120">
                    <template #default="{ row }">
                        {{ row.donation_type }}
                    </template>
                </el-table-column>

                <el-table-column prop="amount" label="Amount" width="120">
                    <template #default="{ row }">
                        {{ row.net_amount ? formatCurrency(row.net_amount) : formatCurrency(0) }}
                    </template>
                </el-table-column>

                <el-table-column prop="created_at" label="Date" width="100">
                    <template #default="{ row }">
                        {{ formatAddedDate(row.created_at) }}
                    </template>
                </el-table-column>

            </template>

            <template #footer>
                <div class="ehxd_footer_page">
                    <p>Page {{ currentPage }} of {{ last_page }}</p>
                </div>

                <el-pagination v-model:current-page="currentPage" v-model:page-size="pageSize"
                    :page-sizes="[10, 20, 30, 40]" large :disabled="total_donations <= pageSize" background
                    layout="total, sizes, prev, pager, next" :total="+total_donations" />
            </template>

        </AppTable>


        <AppModal :title="'Delete Donation'" :width="500" :showFooter="false" ref="delete_donation_modal">
            <template #body>

                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this Donation</p>
                </div>
            </template>
            <template #footer>
                <div class="exd-modal-footer" style="text-align: center;">
                    <el-button @click="$refs.delete_campaign_modal.handleClose()" type="info"
                        size="medium">Cancel</el-button>
                    <el-button @click="deleteDonation" type="primary" size="medium"
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
            status_filter: '',
            donations: [],
            generalSettings: {},
            currencies: window.EHXDonate.currencies,
            currencySymbols: window.EHXDonate.currencySymbols,
            donation: {},
            total_donations: 0,
            loading: false,
            export: false,
            currentPage: 1,
            last_page: 1,
            pageSize: 10,
            active_id: null,
            add_donation_modal: false,
            nonce: window.EHXDonate.restNonce,
            rest_api: window.EHXDonate.restUrl,
        }
    },
    watch: {
        currentPage() {
            this.getAllDonations();
        },
        pageSize() {
            this.currentPage = 1;
            this.getAllDonations();
        },
        search: {
            handler() {
                this.currentPage = 1;
                this.getAllDonations();
            },
            immediate: false
        },
        status_filter: {
            handler() {
                this.currentPage = 1;
                this.getAllDonations();
            },
            immediate: false
        },
    },

    methods: {
        getIntervalLabel(interval, interval_count) {
            console.log('interval:', interval, 'interval_count:', interval_count);
            if (interval === 'month') {
                return 'Monthly';
            } else if (interval === 'quarter') {
                return 'Quarterly';
            }
            else if (interval === 'year') {
                return 'Yearly';
            } else if (interval === 'week') {
                return 'Weekly';
            } else if (interval === 'fortnight') {
                return 'Fortnightly';
            } else {
                return 'One-time';
            }
        },
        formatAddedDate(date) {
            if (!date) return '';

            const d = new Date(date);

            const day = d.getDate();
            const month = d.getMonth() + 1;
            const year = d.getFullYear();

            return `${day}-${month}-${year}`;
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

        viewDonation(row) {
            this.$router.push({ name: 'view_donation', params: { id: row.id } });
        },

        async getAllDonations() {
            this.loading = true;
            try {
                const response = await axios.get(`${this.rest_api}api/get-all-gift-aid-donations`, {
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
                console.log('donations response:', response.data.data.total);
                this.donations = response?.data?.data?.donations;
                this.generalSettings = response?.data?.data?.generalSettings || {};
                this.total_donations = response?.data?.data?.total || 0;
                this.last_page = response?.data?.data?.last_page || 1;

                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.error('Error fetching donations:', error);
            }
        },

        async exportCSV() {
            try {
                this.export = true;

                const response = await axios.get(
                    `${this.rest_api}api/export-gift-aid`,
                    {
                        headers: {
                            'X-WP-Nonce': this.nonce
                        },
                        responseType: 'blob'
                    }
                );

                const blob = new Blob([response.data], { type: 'text/csv' });

                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = `gift-aid-${new Date().toISOString().split('T')[0]}.csv`;

                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
                window.URL.revokeObjectURL(link.href);

                this.$notify({
                    title: 'Success',
                    message: 'CSV exported successfully',
                    type: 'success',
                });

            } catch (error) {
                console.error('Export error:', error);
                this.$notify({
                    title: 'Error',
                    message: 'Failed to export CSV',
                    type: 'error',
                });
            } finally {
                this.export = false;
            }
        },

    },

    mounted() {
        console.log('Mounted AllCampaign.vue', window.EHXDonate);
        this.getAllDonations();
    },

}
</script>

<style lang="scss" scoped>
.sub-text {
    margin: 0 !important;
    font-size: 12px;
    color: #666D80;
}

.recurring-badge {
    display: flex;
    align-items: center;
    gap: 4px;
    color: rgb(30 64 175/1);
    background: rgb(219 234 254/1);
    border-radius: 16px;
    padding: 4px 8px;
    font-size: 10px;
    font-weight: 500;
    width: fit-content;

    svg {
        width: 11px;
        height: 11px;
    }
}

.ehxdo-title-link:hover {
    color: #3366FF;
}

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