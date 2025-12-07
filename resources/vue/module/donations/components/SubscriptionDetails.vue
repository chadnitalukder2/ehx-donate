<template>
    <div class="ehxdo-recurring">
        <h3 class="ehxdo-recurring__title">Recurring Subscriptions</h3>
        <hr class="ehxdo-recurring__divider" />

        <div class="ehxdo-recurring__body">
            <div class="ehxdo-recurring__plan">Standard Donation</div>

            <div class="ehxdo-recurring__amount">
                <strong>{{ subscription?.amount }} {{ currency }}</strong>
                <span> / Billed {{ subscription?.interval }}</span>
            </div>

            <div class="ehxdo-recurring__next-bill">
                Your next bill is for {{ subscription?.amount }} {{ currency }} on {{ subscription?.next_payment_date }}
            </div>

            <div class="ehxdo-recurring__status">
                <span class="ehxdo-recurring__status-label">Status:</span>
                <span :class="[
                    'ehxdo-recurring__status-badge',
                    subscription?.status === 'active' ? 'is-active' :
                        subscription?.status === 'cancelled' ? 'is-cancelled' : ''
                ]">{{ subscription?.status }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RecurringSubscriptions',
    props: {
        subscription: {
            type: Object,
            default: {}
        },
        currency: {
            type: String,
            default: 'USD'
        }
    }
}
</script>

<style lang="scss" scoped>
.ehxdo-recurring {
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

    &__plan {
        font-size: 1rem;
        font-weight: 500;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    &__amount {
        font-size: 0.875rem;
        margin-bottom: 0.75rem;

        strong {
            font-weight: 600;
            color: #111827;
            margin-right: 4px;
        }

        span {
            color: inherit;
        }
    }

    &__next-bill {
        margin-bottom: 1rem;
    }

    &__status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    &__status-label {
        font-weight: 500;
        color: #374151;
    }

    &__status-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;

        &.is-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        &.is-cancelled {
            background-color: #fef2f2;
            color: #991b1b;
        }
    }
}
</style>