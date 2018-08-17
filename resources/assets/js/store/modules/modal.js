import * as types from '../mutation-types'

// state
export const state = {
  show: false
}

// getters
export const getters = {
  show: state => state.show
}

// mutations
export const mutations = {
  [types.SHOW_MODAL] (state) {
    state.show = true
  },
  [types.HIDE_MODAL] (state) {
    state.show = false
  }
}

// actions
export const actions = {
  showModal ({ commit }) {
    commit(types.SHOW_MODAL)
  },
  hideModal ({ commit }) {
    commit(types.HIDE_MODAL)
  }
}
