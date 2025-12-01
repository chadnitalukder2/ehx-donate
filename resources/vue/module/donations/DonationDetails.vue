<template>
    <div class="ehxd_wrapper" v-loading="loading">
        <Breadcrumb :donation="donation" />
        <UserCard :donor="donor" />
        <DonationDetailsCard :donation="donation" />
        <TransactionsHistory :transactions="transactions" />
    </div>
</template>

<script>
import axios from 'axios';
import UserCard from './components/UserCard.vue';
import DonationDetailsCard from './components/DonationDetailsCard.vue';
import TransactionsHistory from './components/TransactionsHistory.vue';
import Breadcrumb from './components/Breadcrumb.vue';

export default {
    name: 'DonationDetails',
    components: {
        UserCard,
        DonationDetailsCard,
        TransactionsHistory,
        Breadcrumb
    },
    data() {
        return {
            donation: {},
            donor: {},
            transactions: [],
            loading: false,
            nonce: window.EHXDonate.restNonce,
            rest_api: window.EHXDonate.restUrl,
        }
    },
    methods: {
        async getDonationDetails() {
            this.loading = true;
            try {
                const response = await axios.get(`${this.rest_api}api/donations/${this.$route.params.id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': this.nonce
                    }
                });
                this.donation = response?.data?.data?.donation;
                this.donor = response?.data?.data?.donor;
                this.transactions = response?.data?.data?.transactions;

                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.error('Error fetching donation:', error);
            }
         
        }
    },
    mounted() {
        this.getDonationDetails();
    }
}
</script>

<style lang="scss" scoped>
.ehxd_wrapper {
    max-width: 1050px;
    margin: 0 auto;
}
</style>