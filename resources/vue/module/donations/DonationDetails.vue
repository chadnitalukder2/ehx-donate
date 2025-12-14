<template>
    <div class="ehxd_wrapper" v-loading="loading">
        <Breadcrumb :donation="donation" />
        <UserCard :donor="donor" />
        <DonationDetailsCard :donation="donation" />

        <div v-if="donation.donation_type !== 'one-time'">
            <SubscriptionDetails :subscription="subscription" :currency="donation.currency" />
        </div>

        <div v-if="Number(donation.gift_aid) === 1">
            <GiftAidDetails :donation="donation" />
        </div>

        <TransactionsHistory :transactions="transactions" />
    </div>
</template>

<script>
import axios from 'axios';
import UserCard from './components/UserCard.vue';
import DonationDetailsCard from './components/DonationDetailsCard.vue';
import TransactionsHistory from './components/TransactionsHistory.vue';
import SubscriptionDetails from './components/SubscriptionDetails.vue';
import GiftAidDetails from './components/GiftAidDetails.vue';

import Breadcrumb from './components/Breadcrumb.vue';

export default {
    name: 'DonationDetails',
    components: {
        UserCard,
        DonationDetailsCard,
        TransactionsHistory,
        SubscriptionDetails,
        Breadcrumb,
        GiftAidDetails,
    },
    data() {
        return {
            donation: {},
            donor: {},
            transactions: [],
            subscription: {},
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
                this.subscription = response?.data?.data?.subscription;

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