import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  licenses: [],
  license: undefined
}

// getters
export const getters = {
  licenses: state => state.licenses,
  license: state => state.license,
}

// mutations
export const mutations = {
  [types.FETCH_LICENSE_SUCCESS] (state, { license }) {
    state.license = license;
  },
  [types.FETCH_LICENSES_SUCCESS] (state, { licenses }) {
    state.licenses = licenses;
  },
  [types.UPDATE_LICENSE] (state, { data }) {
    state.license = data;
  },
  [types.CREATE_LICENSE] (state, { license }) {
    state.license = license;
  },
}

// actions
export const actions = {
  async fetchLicenses ({ commit }) {
    const { licenses } = await axios.get('/api/licenses')
    commit(types.FETCH_LICENSES_SUCCESS, { licenses })
  },
  async fetchLicense ({ commit }, licenseId) {
    const { data } = await axios.get('/api/licenses/'+licenseId)
    commit(types.FETCH_LICENSE_SUCCESS, { license: data })
  },
  updateLicense({ commit }, { form }) {
    return new Promise((resolve) => {
      form['_method'] = 'PUT';
      if (!form.billFile) {
        delete form.billFile;
      }
      form.post('/api/licenses/'+form.id).then(({ data }) => {
        commit(types.UPDATE_LICENSE, { data });
        resolve({ data: data });
      });
    });
  },
  createLicense({ commit }, { form }) {
    return new Promise((resolve) => {
      form.post('/api/licenses').then(({ data }) => {
        commit(types.CREATE_LICENSE, { license: data });
        resolve({ data })
      });
    });
  }
}
