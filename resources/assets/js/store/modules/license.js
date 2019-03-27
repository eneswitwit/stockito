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
    [types.FETCH_LICENSE_SUCCESS](state, {license}) {
        state.license = license;
    },
    [types.FETCH_LICENSES_SUCCESS](state, {licenses}) {
        state.licenses = licenses;
    },
    [types.UPDATE_LICENSE](state, {data}) {
        state.license = data;
    },
    [types.CREATE_LICENSE](state, {license}) {
        state.license = license;
    },
}

// actions
export const actions = {
    async fetchLicenses({commit}) {
        const {licenses} = await axios.get('/api/licenses/get')
        commit(types.FETCH_LICENSES_SUCCESS, {licenses})
    },
    async fetchLicense({commit}, licenseId) {
        const {data} = await axios.get('/api/licenses/get/' + licenseId)
        commit(types.FETCH_LICENSE_SUCCESS, {license: data})
    },
    updateLicense({commit}, {form}) {
        return new Promise((resolve) => {
            if (!form.billFile) {
                delete form.billFile;
            }
            form.post('/api/licenses/update').then(({data}) => {
                commit(types.UPDATE_LICENSE, {data});
                resolve({data: data});
            });
        });
    },
    createLicense({commit}, {form}) {
        return new Promise((resolve) => {
            if (!form.billFile) {
                delete form.billFile;
            }
            form.post('/api/licenses').then(({data}) => {
                commit(types.CREATE_LICENSE, {license: data});
                resolve({data})
            });
        });
    },
    createUsageLicense({commit}, {form}) {
        return new Promise((resolve) => {
            if (!form.billFile) {
                delete form.billFile;
            }
            form.post('/api/usage-license').then(({data}) => {
                commit(types.CREATE_LICENSE, {license: data});
                resolve({data})
            });
        });
    }

}
