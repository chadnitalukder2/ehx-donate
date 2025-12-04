<template>
    <div class="ehxdo-donation">
        <div class="ehxdo-donation__header">
            <div class="ehxdo-donation__title">
                Donation Details
                <span :class="[
                    'ehxdo-donation__status',
                    donation?.payment_status === 'completed' ? 'completed' :
                        donation?.payment_status === 'pending' ? 'pending' :
                            donation?.payment_status === 'failed' ? 'failed' : ''
                ]">{{ donation?.payment_status }}</span>
            </div>
        </div>

        <hr class="ehxdo-donation__divider" />

        <div class="ehxdo-donation__item">
            <span class="ehxdo-donation__label">Donation</span>
            <span class="ehxdo-donation__value"> {{ getSymbol(donation?.currency) }} {{ donation?.total_payment }}</span>
        </div>
        <div class="ehxdo-donation__item">
            <span class="ehxdo-donation__label">Payment Processing Fee</span>
            <span class="ehxdo-donation__value">{{ getSymbol(donation?.currency) }} {{ donation?.processing_fee || 0 }}</span>
        </div>

        <hr class="ehxdo-donation__divider" />

        <div class="ehxdo-donation__total">
            <span class="ehxdo-donation__label">Total</span>
            <span class="ehxdo-donation__total-value"> {{ getSymbol(donation?.currency) }} {{ getTotal(donation) }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DonationDetails',
    props: {
        donation: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            currencySymbols: window.EHXDonate.currencySymbols,
        }
    },
    methods: {
        getSymbol(currency) {
            return this.currencySymbols[currency] || currency;
        },
        getTotal(donation) {
            return parseFloat(donation?.total_payment) + parseFloat(donation?.processing_fee || 0);
        }
    }
}
</script>

<style lang="scss" scoped>
.ehxdo-donation {
    margin: 20px 0;
    background-color: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 0 0 1px #f3f4f6;
    padding: 1rem;
    font-family: 'Arial', sans-serif;

    &__header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    &__title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    &__status {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        white-space: nowrap;

        &.completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        &.pending {
            background-color: #fde68a;
            color: #f5a623;
        }

        &.failed {
            background-color: #fecaca;
            color: #ef4444;
        }
    }

    &__menu-button {
        background-color: white;
        border: 1px solid #e5e7eb;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
    }

    &__dots {
        font-size: 1rem;
        line-height: 1;
    }

    &__divider {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 1rem 0;
    }

    &__item {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        color: #4b5563;
    }

    &__total {
        display: flex;
        justify-content: space-between;
        font-weight: 600;
        font-size: 1rem;
        color: #111827;
        margin-top: 1rem;
    }

    &__label {
        font-size: 0.875rem;
    }

    &__value,
    &__total-value {
        white-space: nowrap;
    }
}
</style>