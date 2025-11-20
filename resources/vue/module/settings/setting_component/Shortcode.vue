<template>
    <div class="ehxdo-shortcode-container">
        <!-- Donation Form Shortcode -->
        <div class="ehxdo-shortcode-section" style="padding-top: 0px;">
            <h3 class="ehxdo-shortcode-title">Donation Form Shortcode</h3>
            <div class="ehxdo-shortcode-content">
                <div class="ehxdo-shortcode-code">
                    <code class="ehxdo-code-text">[ehxdo_donation_form /]</code>
                </div>
                <p class="ehxdo-shortcode-description">
                    Insert this shortcode on any page or post to display the donation form. Simply copy and paste it
                    into the content editor.
                </p>
            </div>
        </div>

        <!-- Donation Table Shortcode -->
        <div class="ehxdo-shortcode-section">
            <h3 class="ehxdo-shortcode-title">Donation Table Shortcode</h3>
            <div class="ehxdo-shortcode-content">
                <div class="ehxdo-shortcode-code">
                    <code class="ehxdo-code-text">[ehxdo_donation_table /]</code>
                </div>
                <p class="ehxdo-shortcode-description">
                    Insert this shortcode on any page or post to display the donation table. Simply copy and paste it
                    into the content editor.
                </p>
            </div>
        </div>

        <!-- Campaign Lists Shortcode -->
        <div class="ehxdo-shortcode-section">
            <h3 class="ehxdo-shortcode-title">Campaign Lists Shortcode</h3>
            <div class="ehxdo-shortcode-content">
                <div class="ehxdo-shortcode-code">
                    <code class="ehxdo-code-text">[ehxdo_campaign_lists /]</code>
                </div>
                <p class="ehxdo-shortcode-description">
                    Insert this shortcode on any page or post to display the donation campaigns. Simply copy and paste
                    it into the content editor.
                </p>

                
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ShortcodeSettings'
};
</script>

<style scoped lang="scss">
.ehxdo-shortcode-container {
    max-width: 100%;
    background-color: #ffffff;
    padding: 2rem;
    border-radius:16px;
    font-family: Inter Tight, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.ehxdo-shortcode-section {
    padding: 24px 0;
    border-bottom: 1px solid #e5e7eb;
}

.ehxdo-shortcode-section:last-child {
    border-bottom: none;
}

.ehxdo-shortcode-title {
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 16px 0;
}

.ehxdo-shortcode-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.ehxdo-shortcode-code {
    background-color: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    padding: 6px 14px;
    display: inline-block;
    align-self: flex-start;
}

.ehxdo-code-text {
    font-family: 'Courier New', Courier, monospace;
    font-size: 13px;
    color: #374151;
    background: none;
    padding: 0;
}

.ehxdo-shortcode-description {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
}

.ehxdo-parameters-section {
    margin-top: 8px;
}

.ehxdo-parameters-title {
    font-size: 14px;
    color: #374151;
    margin: 0 0 12px 0;
}

.ehxdo-parameter-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding-left: 20px;
}

.ehxdo-parameter-item {
    font-size: 14px;
    line-height: 1.6;
    color: #6b7280;
}

.ehxdo-parameter-name {
    font-family: 'Courier New', Courier, monospace;
    font-size: 13px;
    color: #374151;
    background-color: #f3f4f6;
    padding: 2px 6px;
    border-radius: 3px;
}

.ehxdo-parameter-description {
    color: #6b7280;
    margin-left: 4px;
}
</style>