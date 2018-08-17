import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  subscription: null,
  subscriptions: null
}

// getters
export const getters = {
  subscriptions: state => !!state.subscriptions,
  check: state => state => !!state.subscription
}

// mutations
export const mutations = {
  // user was been subscribed
  [types.USER_SUBSCRIBED] (state, { subscription }) {
    state.subscription = subscription
  }
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },

  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/api/user')

      commit(types.FETCH_USER_SUCCESS, { user: data })
    } catch (e) {
      commit(types.FETCH_USER_FAILURE)
    }
  },

  updateUser ({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/api/logout')
    } catch (e) { }

    commit(types.LOGOUT)
  },

  async delete ({ commit }) {
    try {
      await axios.delete('/api/settings/profile/creative')
    } catch (e) { }

    commit(types.DELETE)
  },

  async fetchOauthUrl (ctx, { provider }) {
    const { data } = await axios.post(`/api/oauth/${provider}`)

    return data.url
  }
}
