<template>
  <div class="ehxdo-container">
    <div class="ehxdo-main-content">
      <!-- Header Section -->
      <div class="ehxdo-header">
        <h1 class="ehxdo-title">Creative new campaign</h1>
        <div class="ehxdo-header-actions">
          <el-button class="ehxdo-preview-btn" plain>Preview Camp</el-button>
          <el-button class="ehxdo-launch-btn" type="success">Launch now</el-button>
        </div>
      </div>

      <!-- Basic Details Section -->
      <div class="ehxdo-section">
        <h2 class="ehxdo-section-title">Basic Details</h2>
        <p class="ehxdo-section-subtitle">
          This information will help you choose the best campaign to suit your requirements
        </p>

        <div class="ehxdo-form-group">
          <label class="ehxdo-label">Enter Campaign title</label>
          <el-input 
            v-model="campaignTitle" 
            class="ehxdo-input"
            placeholder="Enter campaign title"
          />
        </div>

        <div class="ehxdo-form-group">
          <label class="ehxdo-label">Tell us about your campaign. What are you looking forward of? Buy a timepiece?</label>
          <el-input 
            v-model="campaignDescription"
            type="textarea"
            :rows="4"
            class="ehxdo-textarea"
            placeholder="Enter campaign description"
          />   
        </div>

        <div class="ehxdo-form-row">
          <div class="ehxdo-form-col">
            <label class="ehxdo-label">Audience</label>
            <el-select v-model="audience" class="ehxdo-select" placeholder="Marketing" >
              <el-option label="Marketing" value="marketing" />
              <el-option label="Sales" value="sales" />
              <el-option label="Support" value="support" />
            </el-select>
          </div>
        </div>

        <div class="ehxdo-form-row">
          <div class="ehxdo-form-col">
            <label class="ehxdo-label">Start Date</label>
            <el-date-picker
              v-model="startDate"
              type="date"
              class="ehxdo-date-picker"
              placeholder="Pick a date"
              format="DD.MM.YYYY"
            />
          </div>
          <div class="ehxdo-form-col">
            <label class="ehxdo-label">End Date</label>
            <el-date-picker
              v-model="endDate"
              type="date"
              class="ehxdo-date-picker"
              placeholder="Pick a date"
              format="DD.MM.YYYY"
            />
          </div>
        </div>
      </div>

      <!-- Campaign Image Section -->
      <div class="ehxdo-section">
        <h2 class="ehxdo-section-title">Campaign Image</h2>
        <p class="ehxdo-section-subtitle">Upload an image for your email campaign</p>

        <div class="ehxdo-upload-area">
          <el-upload
            class="ehxdo-uploader"
            drag
            :show-file-list="false"
            action="#"
          >
            <el-icon class="ehxdo-upload-icon"><UploadFilled  /></el-icon>
            <div class="ehxdo-upload-text">Click to upload or just drag</div>
            <div class="ehxdo-upload-formats">PNG, JPG, PNG, GIF</div>
          </el-upload>
        </div>

        <div class="ehxdo-powered-by">
          Powered By
          <img src="/api/placeholder/80/20" alt="Logo" class="ehxdo-powered-logo" />
        </div>
      </div>

      <!-- Donation Information Section -->
      <div class="ehxdo-section">
        <h2 class="ehxdo-section-title">Donation Information</h2>
        <p class="ehxdo-section-subtitle">Three Sections</p>

        <div class="ehxdo-donation-item">
          <el-checkbox v-model="alexKrizenActive" class="ehxdo-checkbox" />
          <div class="ehxdo-donation-content">
            <div class="ehxdo-donation-header">
              <h3 class="ehxdo-donation-title">Alexa Krizien wizard</h3>
              <el-switch v-model="alexKrizenActive" class="ehxdo-switch" />
            </div>
            <p class="ehxdo-donation-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
            <div class="ehxdo-donation-meta">
              <span class="ehxdo-donation-goal">Goal: <strong>$200</strong></span>
              <span class="ehxdo-donation-separator">|</span>
              <span class="ehxdo-donation-pledged">Pledged: <strong>$10,080</strong></span>
            </div>
          </div>
        </div>

        <div class="ehxdo-donation-item">
          <el-checkbox v-model="breakfastActive" class="ehxdo-checkbox" />
          <div class="ehxdo-donation-content">
            <div class="ehxdo-donation-header">
              <h3 class="ehxdo-donation-title">Breakfast Policy</h3>
              <el-switch v-model="breakfastActive" class="ehxdo-switch" />
            </div>
            <p class="ehxdo-donation-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
            <div class="ehxdo-donation-actions">
              <span class="ehxdo-donation-time">Time:</span>
              <el-time-picker
                v-model="breakfastTime"
                class="ehxdo-time-picker"
                placeholder="Select time"
              />
              <el-button class="ehxdo-add-btn" type="primary" plain size="small">
                <el-icon><plus /></el-icon> Add More
              </el-button>
            </div>
          </div>
        </div>

        <div class="ehxdo-donation-item">
          <el-checkbox v-model="recurringActive" class="ehxdo-checkbox" />
          <div class="ehxdo-donation-content">
            <div class="ehxdo-donation-header">
              <h3 class="ehxdo-donation-title">Allow Recurring Amount</h3>
              <el-switch v-model="recurringActive" class="ehxdo-switch" />
            </div>
            <p class="ehxdo-donation-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been standard text</p>
          </div>
        </div>
      </div>

      <!-- Campaign Settings Section -->
      <div class="ehxdo-section">
        <h2 class="ehxdo-section-title">Campaign Settings</h2>
        <p class="ehxdo-section-subtitle">This Thing is Global Parameter Information</p>

        <div class="ehxdo-settings-item">
          <label class="ehxdo-label">Sending Settings</label>
          <el-radio-group v-model="sendingType" class="ehxdo-radio-group">
            <el-radio label="public">Public - Visible to everyone</el-radio>
            <el-radio label="private">Private - Hidden to public and investor only</el-radio>
          </el-radio-group>
        </div>

        <div class="ehxdo-settings-item">
          <label class="ehxdo-label">Upload</label>
          <el-input v-model="uploadLink" class="ehxdo-input" placeholder="Link to Google drive or link" />
        </div>

        <div class="ehxdo-settings-item">
          <label class="ehxdo-label">Text</label>
          <el-input 
            v-model="textContent"
            type="textarea"
            :rows="3"
            class="ehxdo-textarea"
            placeholder="Your feedback is very to us very"
          />
        </div>

        <div class="ehxdo-settings-item">
          <label class="ehxdo-label">val</label>
          <el-input-number 
            v-model="valNumber" 
            class="ehxdo-input-number"
            :min="0"
          />
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="ehxdo-sidebar">
      <!-- Live Preview Card -->
      <div class="ehxdo-card">
        <h3 class="ehxdo-card-title">Live Preview</h3>
        <p class="ehxdo-card-subtitle">How Your Campaign Will Look Online</p>
        <div class="ehxdo-preview-placeholder">
          <el-icon class="ehxdo-preview-icon"><picture /></el-icon>
        </div>
      </div>

      <!-- Campaign Tips Card -->
      <div class="ehxdo-card">
        <h3 class="ehxdo-card-title">Campaign Tips</h3>
        <div class="ehxdo-tips-list">
          <div class="ehxdo-tip-item">
            <span class="ehxdo-tip-label">Name</span>
            <el-slider v-model="nameProgress" class="ehxdo-slider" :show-tooltip="false" />
          </div>
          <div class="ehxdo-tip-item">
            <span class="ehxdo-tip-label">Subtitle</span>
            <el-slider v-model="subtitleProgress" class="ehxdo-slider" :show-tooltip="false" />
          </div>
          <div class="ehxdo-tip-item">
            <span class="ehxdo-tip-label">How You Fit In</span>
            <el-slider v-model="fitProgress" class="ehxdo-slider" :show-tooltip="false" />
          </div>
        </div>
      </div>

      <!-- Actions Card -->
      <div class="ehxdo-card ehxdo-actions-card">
        <h3 class="ehxdo-card-title">Actions</h3>
        <el-button type="success" class="ehxdo-action-btn ehxdo-action-btn-primary">
          <el-icon><check /></el-icon> Save Launch
        </el-button>
        <el-button plain class="ehxdo-action-btn ehxdo-action-btn-secondary">
          <el-icon><download /></el-icon> Save as Draft
        </el-button>
        <el-button text class="ehxdo-action-btn ehxdo-action-btn-text">Cancel</el-button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { 
  UploadFilled, 
  Plus, 
  Check, 
  Download, 
  Picture 
} from '@element-plus/icons-vue';

// Form data
const campaignTitle = ref('');
const campaignDescription = ref('');
const audience = ref('');
const startDate = ref('');
const endDate = ref('');

// Donation switches
const alexKrizenActive = ref(true);
const breakfastActive = ref(false);
const recurringActive = ref(false);
const breakfastTime = ref('');

// Settings
const sendingType = ref('public');
const uploadLink = ref('');
const textContent = ref('');
const valNumber = ref(0);

// Progress sliders
const nameProgress = ref(60);
const subtitleProgress = ref(40);
const fitProgress = ref(80);
</script>

<style lang="scss" scoped>
.ehxdo-container {
  display: flex;
  gap: 24px;
  padding: 24px;
  background-color: #f5f7fa;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.ehxdo-main-content {
  flex: 1;
  max-width: 800px;
}

.ehxdo-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;

  .ehxdo-title {
    font-size: 24px;
    font-weight: 600;
    color: #303133;
    margin: 0;
  }

  .ehxdo-header-actions {
    display: flex;
    gap: 12px;

    .ehxdo-preview-btn {
      border-color: #dcdfe6;
      color: #606266;
    }

    .ehxdo-launch-btn {
      background-color: #67c23a;
      border-color: #67c23a;
    }
  }
}

.ehxdo-section {
  background: white;
  border-radius: 8px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);

  .ehxdo-section-title {
    font-size: 18px;
    font-weight: 600;
    color: #303133;
    margin: 0 0 8px 0;
  }

  .ehxdo-section-subtitle {
    font-size: 14px;
    color: #909399;
    margin: 0 0 24px 0;
  }
}

.ehxdo-form-group {
  margin-bottom: 20px;

  .ehxdo-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #606266;
    margin-bottom: 8px;
  }

  .ehxdo-input,
  .ehxdo-textarea {
    width: 100%;
  }
}

.ehxdo-form-row {
  display: flex;
  gap: 16px;
  margin-bottom: 20px;

  .ehxdo-form-col {
    flex: 1;

    .ehxdo-label {
      display: block;
      font-size: 14px;
      font-weight: 500;
      color: #606266;
      margin-bottom: 8px;
    }

    .ehxdo-select,
    .ehxdo-date-picker {
      width: 100%;
    }
  }
}

.ehxdo-upload-area {
  margin-bottom: 16px;

  .ehxdo-uploader {
    width: 100%;

    :deep(.el-upload) {
      width: 100%;
    }

    :deep(.el-upload-dragger) {
      width: 100%;
      height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border: 2px dashed #dcdfe6;
      border-radius: 8px;
      background-color: #fafafa;
      transition: all 0.3s;

      &:hover {
        border-color: #409eff;
      }
    }

    .ehxdo-upload-icon {
      font-size: 48px;
      color: #c0c4cc;
      margin-bottom: 16px;
    }

    .ehxdo-upload-text {
      font-size: 14px;
      color: #606266;
      margin-bottom: 8px;
    }

    .ehxdo-upload-formats {
      font-size: 12px;
      color: #909399;
    }
  }
}

.ehxdo-powered-by {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: #909399;

  .ehxdo-powered-logo {
    height: 20px;
  }
}

.ehxdo-donation-item {
  display: flex;
  gap: 12px;
  padding: 16px;
  border: 1px solid #ebeef5;
  border-radius: 8px;
  margin-bottom: 16px;

  .ehxdo-checkbox {
    margin-top: 4px;
  }

  .ehxdo-donation-content {
    flex: 1;

    .ehxdo-donation-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;

      .ehxdo-donation-title {
        font-size: 16px;
        font-weight: 600;
        color: #303133;
        margin: 0;
      }
    }

    .ehxdo-donation-desc {
      font-size: 14px;
      color: #606266;
      margin: 0 0 12px 0;
      line-height: 1.5;
    }

    .ehxdo-donation-meta {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      color: #909399;

      .ehxdo-donation-separator {
        color: #dcdfe6;
      }

      strong {
        color: #303133;
        font-weight: 600;
      }
    }

    .ehxdo-donation-actions {
      display: flex;
      align-items: center;
      gap: 12px;

      .ehxdo-donation-time {
        font-size: 14px;
        color: #606266;
      }

      .ehxdo-time-picker {
        flex: 1;
        max-width: 200px;
      }
    }
  }
}

.ehxdo-settings-item {
  margin-bottom: 20px;

  .ehxdo-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #606266;
    margin-bottom: 8px;
  }

  .ehxdo-radio-group {
    display: flex;
    flex-direction: column;
    gap: 8px;

    :deep(.el-radio) {
      margin-right: 0;
      margin-bottom: 8px;

      .el-radio__label {
        font-size: 14px;
        color: #606266;
      }
    }
  }

  .ehxdo-input,
  .ehxdo-textarea {
    width: 100%;
  }

  .ehxdo-input-number {
    width: 100%;
  }
}

// Sidebar styles
.ehxdo-sidebar {
  width: 320px;
  flex-shrink: 0;

  .ehxdo-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);

    .ehxdo-card-title {
      font-size: 16px;
      font-weight: 600;
      color: #303133;
      margin: 0 0 8px 0;
    }

    .ehxdo-card-subtitle {
      font-size: 13px;
      color: #909399;
      margin: 0 0 16px 0;
    }
  }
}

.ehxdo-preview-placeholder {
  width: 100%;
  height: 180px;
  background-color: #f5f7fa;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;

  .ehxdo-preview-icon {
    font-size: 48px;
    color: #c0c4cc;
  }
}

.ehxdo-tips-list {
  .ehxdo-tip-item {
    margin-bottom: 20px;

    &:last-child {
      margin-bottom: 0;
    }

    .ehxdo-tip-label {
      display: block;
      font-size: 13px;
      color: #606266;
      margin-bottom: 8px;
    }

    .ehxdo-slider {
      :deep(.el-slider__runway) {
        height: 6px;
      }

      :deep(.el-slider__bar) {
        background-color: #67c23a;
      }

      :deep(.el-slider__button) {
        width: 14px;
        height: 14px;
        border-color: #67c23a;
      }
    }
  }
}

.ehxdo-actions-card {
  .ehxdo-action-btn {
    width: 100%;
    margin-bottom: 12px;
    justify-content: center;

    &:last-child {
      margin-bottom: 0;
    }

    &.ehxdo-action-btn-primary {
      background-color: #67c23a;
      border-color: #67c23a;
      color: white;

      &:hover {
        background-color: #85ce61;
        border-color: #85ce61;
      }
    }

    &.ehxdo-action-btn-secondary {
      border-color: #dcdfe6;
      color: #606266;

      &:hover {
        color: #409eff;
        border-color: #c6e2ff;
        background-color: #ecf5ff;
      }
    }

    &.ehxdo-action-btn-text {
      color: #909399;

      &:hover {
        color: #606266;
      }
    }
  }
}

// Responsive design
@media (max-width: 1200px) {
  .ehxdo-container {
    flex-direction: column;
  }

  .ehxdo-sidebar {
    width: 100%;
  }
}

@media (max-width: 768px) {
  .ehxdo-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .ehxdo-form-row {
    flex-direction: column;
  }
}
</style> 