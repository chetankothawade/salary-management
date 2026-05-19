import { api } from './axios'

export const employeeApi = {
  list: (params) => api.get('/employees', { params }),
  dropdown: () => api.get('/employees/list'),
  show: (uuid) => api.get(`/employees/${uuid}`),
  create: (payload) => api.post('/employees', payload),
  update: (uuid, payload) => api.put(`/employees/${uuid}`, payload),
  patch: (uuid, payload) => api.patch(`/employees/${uuid}`, payload),
  remove: (uuid) => api.delete(`/employees/${uuid}`),
  toggleStatus: (uuid) => api.patch(`/employees/${uuid}/active`),
}
