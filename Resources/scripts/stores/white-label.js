import { useNotificationStore } from '@/scripts/stores/notification'
// InvoiceShelf 2.3.0 stopped exposing window.axios; its HTTP client (auth
// token, company header, credentials) is bundled from the host instead.
import http from '@/scripts/http'
const { defineStore } = window.pinia

// The id is the first argument: Pinia 3, which InvoiceShelf ships from 2.3.0,
// dropped the defineStore({ id, ... }) form.
export const useWhiteLabelStore = defineStore('white-label', {
  actions: {
    updateLogo(data) {
      const notificationStore = useNotificationStore(true)
      return new Promise((resolve, reject) => {
        http
          .post('/api/m/white-label/upload-logos', data)
          .then((response) => {
            resolve(response)
          })
          .catch((err) => {
            reject(err)
          })
      })
    },
  },
})
