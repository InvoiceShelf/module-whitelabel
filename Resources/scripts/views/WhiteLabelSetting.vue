<template>
  <div class="relative">
    <BaseCard container-class="px-4 py-5 sm:px-8 sm:py-8">
      <h6 class="text-gray-900 text-lg font-medium">
        {{ $t('white_label.white_label_title') }}
      </h6>

      <p class="mt-1 text-sm text-gray-500 mb-2">
        {{ $t('white_label.white_label_description') }}
      </p>

      <BaseTabGroup class="pb-0 sm:pb-6">
        <BaseTab tab-panel-container="pt-4 mt-px" title="Customer Portal">
          <div>
            <BaseInputGrid class="mt-8">
              <BaseInputGroup :label="$t('white_label.customer_portal_logo')">
                <BaseFileUploader
                  v-model="logo"
                  accept="image/*"
                  recommended-text="(Recommended Height: 100px)"
                  @change="onCustomerLogoChange"
                  @remove="onCustomerLogoRemove"
                />
              </BaseInputGroup>
            </BaseInputGrid>

            <BaseInputGroup label="Customer Portal Page Title" class="mt-6">
              <BaseInput
                v-model="customerPortalTitle"
                placeholder="Default: InvoiceShelf - Self Hosted Invoicing Platform"
              />
            </BaseInputGroup>

            <ThemeSelector
              v-model="customerPortalSettings.customer_portal_theme"
              class="mt-6"
            />

            <BaseButton
              :loading="isSavingCustomerSettings"
              :disabled="isSavingCustomerSettings"
              class="mt-6"
              @click="saveCustomerPortalSettings"
            >
              <template #left="slotProps">
                <BaseIcon :class="slotProps.class" name="SaveIcon" />
              </template>
              {{ $t('general.save') }}
            </BaseButton>
          </div>
        </BaseTab>

        <BaseTab tab-panel-container="pt-4 mt-px" title="Admin Portal">
          <div>
            <BaseInputGrid class="mt-8">
              <BaseInputGroup :label="$t('white_label.admin_portal_logo')">
                <BaseFileUploader
                  v-model="adminLogo"
                  accept="image/*"
                  recommended-text="(Recommended Height: 100px, Color: White)"
                  @change="onAdminLogoChange"
                  @remove="onAdminLogoRemove"
                />
              </BaseInputGroup>

              <BaseInputGroup :label="$t('white_label.login_page_logo')">
                <BaseFileUploader
                  v-model="loginPageLogo"
                  accept="image/*"
                  recommended-text="(Recommended Height: 100px)"
                  @change="onLoginPageLogoChange"
                  @remove="onLoginPageLogoRemove"
                />
              </BaseInputGroup>
            </BaseInputGrid>

            <BaseInputGrid class="mt-6">
              <BaseInputGroup label="Login Page Heading">
                <BaseInput
                  v-model="loginPageHeading"
                  placeholder="Default: Simple Invoicing for Individuals Small Businesses"
                />
              </BaseInputGroup>

              <BaseInputGroup label="Login Page Description">
                <BaseInput
                  v-model="loginPageDescription"
                  placeholder="Default: InvoiceShelf helps you track expenses, record payments & generate beautiful invoices & estimates."
                />
              </BaseInputGroup>
            </BaseInputGrid>

            <BaseInputGrid class="mt-6">
              <BaseInputGroup label="Admin Portal Page Title">
                <BaseInput
                  v-model="adminPageTitle"
                  placeholder="Default: InvoiceShelf - Self Hosted Invoicing Platform"
                />
              </BaseInputGroup>
              <BaseInputGroup label="Copyright Text">
                <BaseInput
                  v-model="copyrightText"
                  placeholder="Default: Copyright @ Invoice Shelf."
                />
              </BaseInputGroup>
            </BaseInputGrid>

            <ThemeSelector
              v-model="adminPortalSettings.admin_portal_theme"
              :update-body-theme="true"
              class="mt-6"
            />

            <BaseButton
              :loading="isSavingAdminSettings"
              :disabled="isSavingAdminSettings"
              class="mt-6"
              @click="saveAdminPortalSettings"
            >
              <template #left="slotProps">
                <BaseIcon :class="slotProps.class" name="SaveIcon" />
              </template>
              {{ $t('general.save') }}
            </BaseButton>
          </div>
        </BaseTab>
      </BaseTabGroup>

      <!-- Admin Portal -->
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, reactive, inject } from 'vue'
import { useWhiteLabelStore } from '../stores/white-label'
import { useCompanyStore } from '@/scripts/admin/stores/company'
import { useGlobalStore } from '@/scripts/admin/stores/global'

import ThemeSelector from '~/scripts/components/ThemeSelector.vue'

const utils = inject('utils')
const whiteLogoStore = useWhiteLabelStore()
const companyStore = useCompanyStore(true)
const globalStore = useGlobalStore(true)

let logo = ref([])
let adminLogo = ref([])
let loginPageLogo = ref([])
let customerPortalTitle = ref(null)
let loginPageHeading = ref(null)
let loginPageDescription = ref(null)
let adminPageTitle = ref(null)
let copyrightText = ref(null)

setInitialData()

function setInitialData() {
  let globalSettings = globalStore.globalSettings
  let companySettings = companyStore.selectedCompanySettings

  loginPageLogo.value = globalSettings.login_page_logo
    ? setDisplayImage(globalSettings.login_page_logo)
    : []

  adminLogo.value = globalSettings.admin_portal_logo
    ? setDisplayImage(globalSettings.admin_portal_logo)
    : []

  logo.value = companySettings.customer_portal_logo
    ? setDisplayImage(companySettings.customer_portal_logo)
    : []

  customerPortalTitle.value = companySettings.customer_portal_page_title
    ? companySettings.customer_portal_page_title
    : null

  loginPageHeading.value = globalSettings.login_page_heading
    ? globalSettings.login_page_heading
    : null

  loginPageDescription.value = globalSettings.login_page_description
    ? globalSettings.login_page_description
    : null

  adminPageTitle.value = globalSettings.admin_page_title
    ? globalSettings.admin_page_title
    : null

  copyrightText.value = globalSettings.copyright_text
  ? globalSettings.copyright_text : null
}

function setDisplayImage(image) {
  return [
    {
      image: '/storage/' + image,
    },
  ]
}

const customerPortalSettings = reactive({
  customer_portal_theme: "invoiceShelf"
})

const adminPortalSettings = reactive({
  admin_portal_theme: "invoiceShelf",
})

utils.mergeSettings(customerPortalSettings, {
  ...companyStore.selectedCompanySettings,
})

utils.mergeSettings(adminPortalSettings, {
  ...globalStore.globalSettings,
})

let customerLogoBlob = ref(null)
let isCustomerLogoRemoved = ref(false)
let adminLogoBlob = ref(null)
let isAdminLogoRemoved = ref(false)
let loginPageLogoBlob = ref(null)
let isLoginPageLogoRemoved = ref(false)
let isSavingCustomerSettings = ref(false)
let isSavingAdminSettings = ref(false)

function onCustomerLogoChange(fileName, file, fileCount, fileList) {
  customerLogoBlob.value = file
  isCustomerLogoRemoved.value = false
}

function onCustomerLogoRemove() {
  customerLogoBlob.value = null
  isCustomerLogoRemoved.value = true
}

function onAdminLogoChange(fileName, file, fileCount, fileList) {
  adminLogoBlob.value = file
  isAdminLogoRemoved.value = false
}

function onAdminLogoRemove() {
  adminLogoBlob.value = null
  isAdminLogoRemoved.value = true
}

function onLoginPageLogoChange(fileName, file, fileCount, fileList) {
  loginPageLogoBlob.value = file
  isLoginPageLogoRemoved.value = false
}

function onLoginPageLogoRemove() {
  loginPageLogoBlob.value = null
  isLoginPageLogoRemoved.value = true
}

async function saveCustomerPortalSettings() {
  isSavingCustomerSettings.value = true

  let logoData = new FormData()

  if (customerLogoBlob.value) {
    logoData.append('customer_portal_logo', customerLogoBlob.value)
  }

  if (isCustomerLogoRemoved.value) {
    logoData.append("is_customer_portal_logo_removed", isCustomerLogoRemoved.value)
  }

  let logoRes = await whiteLogoStore.updateLogo(logoData)

  companyStore.selectedCompanySettings.customer_portal_logo = logoRes.data.url

  await companyStore.updateCompanySettings({
    data: {
      settings: {
        ...customerPortalSettings,
        customer_portal_page_title: customerPortalTitle.value
      },
    },
    message: 'settings.preferences.updated_message',
  })

  isSavingCustomerSettings.value = false
}

async function saveAdminPortalSettings() {
  isSavingAdminSettings.value = true

  let logoData = new FormData()

  if (adminLogoBlob.value) {
    logoData.append('admin_portal_logo', adminLogoBlob.value)
  }

  if (isAdminLogoRemoved.value) {
    logoData.append('is_admin_portal_logo_removed', isAdminLogoRemoved.value)
  }

  if (loginPageLogoBlob.value) {
    logoData.append('login_page_logo', loginPageLogoBlob.value)
  }

  if (isLoginPageLogoRemoved.value) {
    logoData.append('is_login_page_logo_removed', isLoginPageLogoRemoved.value)
  }

  let logoRes = await whiteLogoStore.updateLogo(logoData)

  if (logoRes.data.adminPortalLogoUrl) {
    globalStore.globalSettings.admin_portal_logo =
      logoRes.data.adminPortalLogoUrl
  }

  let response = await globalStore.updateGlobalSettings({
    data: {
      settings: {
        ...adminPortalSettings,
        login_page_heading: loginPageHeading.value,
        login_page_description: loginPageDescription.value,
        admin_page_title: adminPageTitle.value,
        copyright_text: copyrightText.value
      },
    },
    message: 'settings.preferences.updated_message',
  })

  if (response.data.success) {
    globalStore.globalSettings.login_page_heading =
      loginPageHeading.value
    globalStore.globalSettings.login_page_description =
      loginPageDescription.value
    globalStore.globalSettings.admin_page_title =
      adminPageTitle.value
    globalStore.globalSettings.copyright_text =
      copyrightText.value
  }

  isSavingAdminSettings.value = false
}
</script>
