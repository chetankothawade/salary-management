import axios from 'axios'

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

api.interceptors.response.use(
  (response) => response.data,
  (error) => {
    const message =
      error.response?.data?.message ?? 'Unable to complete the request.'

    return Promise.reject({
      message,
      errors: error.response?.data?.errors ?? {},
      status: error.response?.status,
    })
  },
)
