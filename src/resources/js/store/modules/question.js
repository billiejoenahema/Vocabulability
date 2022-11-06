import { shuffle } from '../../functions/shuffle';
import axios from 'axios';

const defaultParams = {
  column: '',
  is_asc: true,
  keyword: '',
  filter: '',
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
    return state.data ?? [];
  },
  params(state) {
    return state.params ?? {};
  },
  randomData(state) {
    return shuffle(state.data);
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
};

const actions = {
  async get({ commit }, params) {
    await axios
      .get('/api/questions', { params })
      .then((res) => {
        commit('setErrors', {});
        commit('setData', res.data);
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
      });
  },
  async post({ commit }, data) {
    await axios
      .post('/api/questions', data)
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
      .post('/api/questions/import', formData)
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
      .patch(`/api/questions/${data.id}`, data)
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
      .delete(`/api/questions/${id}`)
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
    state.params = defaultParams;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
