import * as types from '../mutation-types'

// state
export const state = {
  results: false,
  query: ''
}

// getters
export const getters = {
  results: state => state.results,
  query: state => state.query,
}

// mutations
export const mutations = {
  [types.SEARCH_SET_QUERY] (state, { query }) {
    state.query = query
  },
  [types.SEARCH_SET_RESULTS] (state, { results }) {
    state.results = results
  },
  [types.SEARCH_RESET_RESULTS] (state) {
    state.results = false
  }
}

// actions
export const actions = {
  search ({ commit, state }, query) {
    commit(types.SEARCH_SET_QUERY, { query })
    axios.get('/api/search', { params: { q: state.query } }).then(({ data }) => {

      commit(types.SEARCH_SET_RESULTS, { query: data})
    });
  },
  resetResults({ commit }) {
    commit(types.SEARCH_RESET_RESULTS)
  }
}
