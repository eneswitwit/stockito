import * as types from '../mutation-types'
import axios from "axios/index";

// state
export const state = {
    selectedBrand: null,
    brands: [],
    editCreative: {
        id: '',
        role: '',
        position: '',
        brandCreativeId: ''
    },
    showEditModal: false,
}

// getters
export const getters = {
    selectedBrand: state => state.selectedBrand,
    getBrands: state => state.brands,
    editCreative: state => state.editCreative,
    showEditModal: state => state.showEditModal,
}

// mutations
export const mutations = {
    [types.SET_SELECTED_BRAND](state, {selectedBrand}) {
        state.selectedBrand = selectedBrand
    },
    [types.SET_BRANDS](state, {brands}) {
        state.brands = brands
    },
    [types.SET_EDIT_CREATIVE](state, {editCreative}) {
        state.editCreative.id = editCreative.id,
            state.editCreative.brandCreativeId = editCreative.brandCreativeId,
            state.editCreative.role = editCreative.role,
            state.editCreative.position = editCreative.position,
            state.showEditModal = true
    },
    [types.HIDE_EDIT_MODAL](state) {
        state.showEditModal = false
    },
}

// actions
export const actions = {
    setSelectedBrand({commit}, {selectedBrand}) {
        commit(types.SET_SELECTED_BRAND, {selectedBrand})
    },
    async setSelectedBrandId({commit}, {selectedBrandId}) {
        const {data} = await axios.post(`/api/brands/${selectedBrandId}`);
        commit(types.SET_SELECTED_BRAND, {selectedBrand: data})
    },
    setBrands({commit}, {brands}) {
        commit(types.SET_BRANDS, {brands: brands})
    },
    async setEditCreative({commit}, {creativeId, brandId}) {
        const {data} = await axios.post(`https://stockito.com/api/brand/creatives/${creativeId}/${brandId}`);
        commit(types.SET_EDIT_CREATIVE, {editCreative: data})
    },
    hideEditModal({commit}) {
        commit(types.HIDE_EDIT_MODAL)
    },
    async setApiBrands({commit}) {
        const {data} = await axios.get('api/brands');
        commit(types.SET_BRANDS, {brands: data})
    },
}