import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
    medias: [],
    media: undefined,
    uploads: [],
    filter: {},
    creativeRole: null,
    selectedMedia: null,
    processing: null,
    requestingProcessing: false
}

// getters
export const getters = {
    medias: state => state.medias,
    media: state => state.media,
    uploads: state => state.uploads,
    filter: state => state.filter,
    selectedMedia: state => state.selectedMedia,
    creativeRole: state => state.creativeRole ? state.creativeRole.role : null,
    processing: state => state.processing,
    requestingProcessing: state => state.requestingProcessing
}

// mutations
export const mutations = {
    [types.ADD_MEDIA](state, {media}) {
        state.medias.push(media)
    },
    [types.REMOVE_MEDIA](state, {media}) {
        state.medias = state.medias.filter(m => m.id !== media.id);
    },
    [types.REMOVE_MEDIAS](state, {medias}) {
        medias.forEach(function(media){
            state.medias = state.medias.filter(m => m.id !== media);
        });
    },
    [types.REMOVE_UPLOAD](state, {media}) {
        state.uploads = state.uploads.filter(m => m.id !== media.id);
    },
    [types.ADD_UPLOAD](state, {media}) {
        state.uploads.unshift(media)
    },
    [types.UPDATE_UPLOAD](state, {upload}) {
        let media = state.uploads.find(m => m.id === upload.id);
        media.license = upload.license;
    },
    [types.UPDATE_MEDIA](state, {media}) {
        state.media = media;
    },
    [types.ATTACH_UPLOAD_LICENSES](state, {uploads, licenses}) {
        let i = 0;
        uploads.forEach(function(upload){
            let media = state.uploads.find(m => m.id === upload);
            console.log('license: ' + licenses[i]);
            media.license = licenses[i];
            i++;
        });
    },
    [types.FETCH_MEDIA_SUCCESS](state, {media}) {
        state.media = media
    },
    [types.FETCH_MEDIAS_SUCCESS](state, {medias, creativeRole}) {
        state.medias = medias;
        state.creativeRole = creativeRole;
    },
    [types.FETCH_UPLOADS_SUCCESS](state, {uploads}) {
        state.uploads = uploads
    },
    [types.SUBMITTED_UPLOAD](state, {upload}) {
        state.uploads = state.uploads.filter(up => up.id !== upload.id);
    },
    [types.SUBMITTED_MULTIPLE_UPLOAD](state, {uploads}) {
        uploads.forEach(function(upload){
            state.uploads = state.uploads.filter(up => up.id !== upload.id);
        });
    },
    [types.RESET_MEDIAS](state) {
        state.medias = []
    },
    [types.SET_FILTER](state, {filter}) {
        state.filter = filter;
    },
    [types.SET_SELECTED_MEDIA](state, {selectedMedia}) {
        state.selectedMedia = selectedMedia;
    },
    [types.SET_FILTER_QUERY](state, {query}) {
        console.log('Set Filter query ' + query);
        state.filter = query;
    },
    [types.FETCH_PROCESSING_SUCCESS](state, {processing}) {
        state.processing = processing;
    },
    [types.SET_REQUESTING_PROCESSING](state, {status}) {
        state.requestingProcessing = status;
    }
}

// actions
export const actions = {
    async fetchMedia({commit}, mediaId) {
        let {data} = await axios.get('/api/medias/' + mediaId);
        commit(types.FETCH_MEDIA_SUCCESS, {media: data});
    },
    addMedia({commit}, {media}) {
        commit(types.ADD_MEDIA, {media: media})
    },
    removeMedia({commit}, {media}) {
        commit(types.REMOVE_MEDIA, {media: media})
    },
    removeMedias({commit}, {medias}) {
        commit(types.REMOVE_MEDIAS, {medias: medias})
    },
    removeUpload({commit}, {media}) {
        commit(types.REMOVE_UPLOAD, {media: media})
    },
    addUpload({commit}, {media}) {
        commit(types.ADD_UPLOAD, {media: media})
    },
    async getMedias({commit, state}) {
        const {data} = await axios.get('/api/medias', {params: state.filter});
        commit(types.FETCH_MEDIAS_SUCCESS, {medias: data})
    },
    async getBrandMedias({commit}, {creative_brand_id}) {
        const {data} = await axios.get('/api/medias/brand/' + creative_brand_id);
        commit(types.FETCH_MEDIAS_SUCCESS, {medias: data.medias, creativeRole: data.creativeRole})
    },
    async getUploads({commit}, {selectedBrandId}) {
        let url = selectedBrandId ? ('/api/medias/uploads' + '/' + selectedBrandId) : '/api/medias/uploads';
        const {data} = await axios.get(url);
        commit(types.FETCH_UPLOADS_SUCCESS, {uploads: data})
    },
    async getProcessing({commit}, {selectedBrandId}) {
        commit(types.SET_REQUESTING_PROCESSING, {status: true})
        let url = selectedBrandId ? ('/api/medias/processing' + '/' + selectedBrandId) : '/api/medias/processing';
        const {data} = await axios.get(url);
        commit(types.FETCH_PROCESSING_SUCCESS, {processing: data})
        commit(types.SET_REQUESTING_PROCESSING, {status: false})
    },
    async submitUpload({commit}, {media, form}) {
        let {data} = await form.post('/api/medias/' + media.id + '/submit');
        commit(types.SUBMITTED_UPLOAD, {upload: data})
    },
    async submitMultipleUpload({commit}, {uploads}) {
        commit(types.SUBMITTED_MULTIPLE_UPLOAD, {uploads: uploads})
    },
    async setSelectedMedia({commit}, {mediaId}) {
        let {data} = await axios.get(`/api/medias/${mediaId}`);
        commit(types.SET_SELECTED_MEDIA, {selectedMedia: data})
    },
    updateUpload({commit}, {upload}) {
        commit(types.UPDATE_UPLOAD, {upload: upload});
    },
    updateMedia({commit}, {form}) {
        return new Promise(async (resolve, reject) => {
            let {data} = await form.put('/api/medias/' + form.id);
            commit(types.UPDATE_MEDIA, {media: data});
            commit(types.RESET_MEDIAS);
            resolve();
        });
    },
    attachLicenses({commit}, {uploads, licenses}) {
        commit(types.ATTACH_UPLOAD_LICENSES, {uploads, licenses});
    },
    async setLicenseUpload({commit, dispatch}, {upload, form}) {
        return new Promise((resolve, reject) => {
            form.post('/api/medias/' + upload.id + '/add-license').then(({data}) => {
                dispatch('updateUpload', {upload: data});
                resolve();
            }).catch(({response}) => {
                reject(response.data.errors);
            });
        });
    },
    setFilter({commit, dispatch}, {filter}) {
        commit(types.SET_FILTER, {filter});
        dispatch('getMedias');
    },
    setFilterQuery({commit, dispatch}, {query}) {
        commit(types.SET_FILTER_QUERY, {query});
        dispatch('getMedias');
    }
}
