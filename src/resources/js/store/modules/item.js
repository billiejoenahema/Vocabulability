import axios from 'axios';

const defaultParams = {
  column: '',
  is_asc: true,
  keyword: '',
  filter: '',
  created_at_from: null,
};

const state = {
  data: [],
  params: {
    ...defaultParams,
  },
  errors: {},
};

const getters = {
  data(state) {
    return state.data?.data ?? [];
  },
  params(state) {
    return state.params ?? {};
  },
  hasErrors(state) {
    return Object.keys(state.errors).length > 0;
  },
  invalidFeedback: (state) => (props) => {
    return state.errors[props] ?? [];
  },
  isInvalid: (state) => (key) => {
    return state.errors?.[key] ? 'invalid' : '';
  },
  links(state) {
    return state.data?.meta?.links ?? [];
  },
};

const actions = {
  async get({ commit }, params) {
    await axios
      .get('/api/items', { params })
      .then((res) => {
        commit('setErrors', {});
        commit('setData', res);
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
      });
  },
  async post({ commit }, data) {
    await axios
      .post('/api/items', data)
      .then((res) => {
        commit('setErrors', {});
        commit(
          'toast/setData',
          { status: res.status, content: res.data.message },
          { root: true }
        );
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
        if (err.response.status === 403) {
          commit(
            'toast/setData',
            { status: err.response.status, content: err.response.data.message },
            { root: true }
          );
        }
      });
  },
  async importCSV({ commit }, formData) {
    await axios
      .post('/api/items/import', formData)
      .then((res) => {
        commit('setErrors', {});
        commit(
          'toast/setData',
          { status: res.status, content: res.data.message },
          { root: true }
        );
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
        if (err.response.status === 403) {
          commit(
            'toast/setData',
            { status: err.response.status, content: err.response.data.message },
            { root: true }
          );
        }
      });
  },
  async update({ commit }, data) {
    await axios
      .patch(`/api/items/${data.id}`, data)
      .then((res) => {
        commit('setErrors', {});
        commit(
          'toast/setData',
          { status: res.status, content: res.data.message },
          { root: true }
        );
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
        if (err.response.status === 403) {
          commit(
            'toast/setData',
            { status: err.response.status, content: err.response.data.message },
            { root: true }
          );
        }
      });
  },
  async delete({ commit }, id) {
    await axios
      .delete(`/api/items/${id}`)
      .then((res) => {
        commit('setErrors', {});
        commit(
          'toast/setData',
          { status: res.status, content: res.data.message },
          { root: true }
        );
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
        if (err.response.status !== 403) return;
        commit(
          'toast/setData',
          { status: err.response.status, content: err.response.data.message },
          { root: true }
        );
      });
  },
};

const mutations = {
  setData(state, data) {
    state.data = data.data;
  },
  setErrors(state, data) {
    state.errors = {};
    state.errors = data;
  },
  resetParams(state) {
    state.errors = {};
    state.params = { ...defaultParams };
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
