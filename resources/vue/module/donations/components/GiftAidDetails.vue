<template>
    <div class="ehxdo-gift-aid">
        <h3 class="ehxdo-gift-aid__title">Gift Aid Details</h3>
        <hr class="ehxdo-gift-aid__divider" />

        <div class="ehxdo-gift-aid__body">
            <div class="ehxdo-gift-aid__info-row">
                <span class="ehxdo-gift-aid__label">Gift Aid Claimed:</span>
                <span class="ehxdo-gift-aid__value">
                    <span class="ehxdo-gift-aid__badge is-active">Yes</span>
                </span>
            </div>

            <div class="ehxdo-gift-aid__info-row">
                <span class="ehxdo-gift-aid__label">Gift Aid Amount:</span>
                <span class="ehxdo-gift-aid__value">
                    <strong>{{ formatCurrency(donation?.gift_aid_amount, donation?.currency) }}</strong>
                </span>
            </div>

            <div class="ehxdo-gift-aid__info-row">
                <span class="ehxdo-gift-aid__label">Declaration Date:</span>
                <span class="ehxdo-gift-aid__value">{{ getDate(donation?.created_at) }}</span>
            </div>

            <div class="ehxdo-gift-aid__address">
                <div class="ehxdo-gift-aid__address-title">Registered Address:</div>
                <div class="ehxdo-gift-aid__address-content">
                    <div style="display: flex; justify-content: space-between; gap: 30px; padding-top: 10px;">
                        <div v-if="donation?.address_line_1" style="flex-basis: 35%;"><span
                                style="font-weight: 500; color: #555;">Address Line 1 :</span> {{
                            donation?.address_line_1 }}</div>
                        <div v-if="donation?.address_line_2" style="flex-basis: 35%;"><span
                                style="font-weight: 500; color: #555;">Address Line 2 : </span> {{
                            donation?.address_line_2 }}</div>
                            <div v-if="donation?.post_code" style="flex-basis: 20%;"> <span style="font-weight: 500; color: #555;"> Post code : </span> {{ donation?.post_code }}</div>
                    </div>
                    <div style="display: flex; gap: 30px; justify-content: space-between; padding-top: 10px;">
                        <div v-if="donation?.country" style="flex-basis: 35%;"><span style="font-weight: 500; color: #555;"> Country :  </span> {{ donation?.country }}</div>
                        <div v-if="donation?.city" style="flex-basis: 35%;"> <span style="font-weight: 500; color: #555;"> City :  </span> {{ donation?.city }}</div>
                        <div v-if="donation?.state" style="flex-basis: 20%;"> <span style="font-weight: 500; color: #555;"> State : </span> {{ donation?.state }}</div>
                        
                    </div>

                </div>
            </div>

            <div class="ehxdo-gift-aid__note">
                <svg class="ehxdo-gift-aid__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Gift Aid allows us to claim an extra 25p for every £1 donated at no cost to you.</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'GiftAidDetails',
    props: {
        donation: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            currencySymbols: window.EHXDonate?.currencySymbols || {
                'GBP': '£',
                'USD': '$',
                'EUR': '€'
            }
        }
    },
    methods: {
        getDate(date) {
            if (!date) return '';
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },
        formatCurrency(amount, currency) {
            const symbol = this.currencySymbols[currency] || currency;
            const formattedAmount = parseFloat(amount || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            return `${symbol}${formattedAmount}`;
        }
    }
}
</script>

<style lang="scss" scoped>
.ehxdo-gift-aid {
    background-color: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 0 0 1px #f3f4f6;
    padding: 1.5rem;
    margin-bottom: 20px;

    &__title {
        font-weight: 600;
        font-size: 1rem;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    &__divider {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 0.5rem 0 1rem;
    }

    &__body {
        font-size: 0.875rem;
        color: #4b5563;
    }

    &__info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    &__label {
        font-weight: 500;
        color: #374151;
    }

    &__value {
        color: #111827;

        strong {
            font-weight: 600;
            color: #059669;
        }
    }

    &__badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;

        &.is-active {
            background-color: #d1fae5;
            color: #065f46;
        }
    }

    &__address {
        background-color: #f9fafb;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    &__address-title {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    &__address-content {
        color: #4b5563;
        line-height: 1.6;

        div {
            margin-bottom: 0.125rem;

            &:last-child {
                margin-bottom: 0;
            }
        }
    }

    &__note {
        display: flex;
        gap: 0.5rem;
        padding: 0.75rem;
        background-color: #eff6ff;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        color: #1e40af;
        line-height: 1.5;
    }

    &__icon {
        flex-shrink: 0;
        margin-top: 0.125rem;
    }
}
</style>