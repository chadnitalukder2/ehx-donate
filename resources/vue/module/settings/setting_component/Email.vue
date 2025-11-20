<template>
  <div class="ehxdo-email-settings-container">
    <!-- Email Notifications Section -->
    <div class="ehxdo-section">
      <h2 class="ehxdo-section-title">Email notifications</h2>
      <p class="ehxdo-section-description">
        Email notifications sent from Ehx Donate are listed below. Click on an email to configure it. Emails should be sent from an email using your website's domain name. We highly recommend using a SMTP service email delivery. Please see this doc for more information.
      </p>
    </div>

    <!-- Email Sender Options Section -->
    <div class="ehxdo-section ehxdo-section-spacing">
      <h2 class="ehxdo-section-title">Email sender options</h2>
      <p class="ehxdo-section-subtitle">How the sender appears in outgoing Ehx donate emails.</p>

      <div class="ehxdo-form-container">
        <!-- Admin Email Address -->
        <div class="ehxdo-form-row">
          <label class="ehxdo-form-label">Admin Email Address</label>
          <div class="ehxdo-input-wrapper">
            <!-- <input
              type="email"
              v-model="settings.adminEmail"
              class="ehxdo-input-field"
              placeholder="example@eh.studio"
            /> -->
              <el-input v-model="settings.adminEmail" placeholder="example@eh.studio" />
          </div>
        </div>

        <!-- Mail appears from -->
        <div class="ehxdo-form-row">
          <label class="ehxdo-form-label">Mail appears from</label>
          <div class="ehxdo-input-wrapper">
            <!-- <input
              type="text"
              v-model="settings.mailFromName"
              class="ehxdo-input-field"
              placeholder="EHx Studio"
            /> -->
            <el-input v-model="settings.mailFromName"  placeholder="EHx Studio" />
          </div>
        </div>

        <!-- Mail appears from address -->
        <div class="ehxdo-form-row">
          <label class="ehxdo-form-label">Mail appears from address</label>
          <div class="ehxdo-input-wrapper">
            <!-- <input
              type="email"
              v-model="settings.mailFromAddress"
              class="ehxdo-input-field"
              placeholder="example@eh.studio"
            /> -->
             <el-input v-model="settings.mailFromAddress"   placeholder="example@eh.studio" />
          </div>
        </div>
      </div>
    </div>

    <!-- Email Template Section -->
    <div class="ehxdo-section ehxdo-section-spacing">
      <h2 class="ehxdo-section-title">Email template</h2>
      <p class="ehxdo-section-subtitle">Section to customize email templates settings.</p>

      <div class="ehxdo-form-container">
        <!-- Content Type -->
        <div class="ehxdo-form-row ehxdo-checkbox-row">
          <label class="ehxdo-form-label">Content type</label>
          <div class="ehxdo-checkbox-wrapper">
            <div class="ehxdo-checkbox-container">
              <input
                type="checkbox"
                id="enableHtml"
                v-model="settings.enableHtml"
                class="ehxdo-checkbox-input"
              />
              <label for="enableHtml" class="ehxdo-checkbox-label">
                Enable HTML for Emails
              </label>
            </div>
            <p class="ehxdo-checkbox-description">
              If you plan use emails with HTML, please make sure that this option is enabled. Otherwise, HTML will be displayed as plain text.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
    props: {
        settings: {
            type: Object,
            default: {}
        },
        loading: {
            type: Boolean,
            default: false
        }
    }
};
</script>

<style scoped>
.ehxdo-email-settings-container {
  max-width: 100%;
  padding: 2rem;
  background-color: #ffffff;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  color: #1f2937;
}

.ehxdo-section {
  padding: 0 0 32px 0;
}

.ehxdo-section-spacing {
  padding-top: 32px;
  border-top: 1px solid #e5e7eb;
}

.ehxdo-section-title {
  font-size: 18px;
  font-weight: 600;
  color: #111827;
  margin: 0 0 12px 0;
  line-height: 1.4;
}

.ehxdo-section-description {
  font-size: 14px;
  color: #6b7280;
  line-height: 1.6;
  margin: 0;
}

.ehxdo-section-subtitle {
  font-size: 14px;
  color: #6b7280;
  line-height: 1.6;
  margin: 0 0 24px 0;
}

.ehxdo-form-container {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.ehxdo-form-row {
  display: grid;
  grid-template-columns: 200px 1fr;
  gap: 20px;
  align-items: start;
}

.ehxdo-checkbox-row {
  align-items: flex-start;
}

.ehxdo-form-label {
  font-size: 14px;
  font-weight: 500;
  color: #374151;
}

.ehxdo-input-wrapper {
  width: 100%;
}

.ehxdo-input-field {
  width: 100%;
  padding: 10px 14px;
  font-size: 14px;
  border: 1px solid #d1d5db !important;
  border-radius: 4px;
  background-color: #ffffff;
  color: #111827;
  outline: none;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  box-sizing: border-box;
}

.ehxdo-input-field:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.ehxdo-input-field::placeholder {
  color: #9ca3af;
}

.ehxdo-checkbox-wrapper {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.ehxdo-checkbox-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.ehxdo-checkbox-input {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db !important;
  border-radius: 3px;
  cursor: pointer;
  accent-color: #3b82f6;
}

.ehxdo-checkbox-label {
  font-size: 14px;
  color: #374151;
  font-weight: 400;
  cursor: pointer;
  user-select: none;
}

.ehxdo-checkbox-description {
  font-size: 13px;
  color: #6b7280;
  line-height: 1.5;
  margin: 0;
  padding-left: 28px;
}

@media (max-width: 768px) {
  .ehxdo-form-row {
    grid-template-columns: 1fr;
    gap: 8px;
  }

  .ehxdo-form-label {
    padding-top: 0;
  }
}
</style>