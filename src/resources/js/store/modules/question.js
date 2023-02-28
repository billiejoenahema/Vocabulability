import axios from 'axios';
import { shuffle } from '../../functions/shuffle';

const setLoading = (commit, bool) =>
  commit('loading/setLoading', bool, { root: true });

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
    page: 1,
  },
  errors: {},
};

const getters = {
  data(state) {
    return state.data.data ?? [];
  },
  params(state) {
    return state.params ?? {};
  },
  randomData(state) {
    return shuffle(Object.entries(state.data?.data));
  },
  hasErrors(state) {
    return Object.keys(state.errors).length > 0;
  },
  invalidFeedback: (state) => (key) => {
    return state.errors?.[key]?.reduce((acc, cur) => {
      if (acc === '') return cur;
      return `${acc}\n${cur}`;
    }, '');
  },
  isInvalid: (state) => (key) => {
    return state.errors?.[key] ? 'is-invalid' : '';
  },
  meta(state) {
    return {
      current_page: state.data?.meta?.current_page ?? 0,
      from: state.data?.meta?.from ?? 0,
      last_page: state.data?.meta?.last_page ?? 0,
      links: state.data?.meta?.links ?? [],
      to: state.data?.meta?.to ?? 0,
      total: state.data?.meta?.total ?? 0,
    };
  },
};

const actions = {
  async get({ commit }, params) {
    setLoading(commit, true);
    await axios
      .get('/api/questions', { params })
      .then((res) => {
        commit('setErrors', {});
        commit('setData', res);
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
      });
    setLoading(commit, false);
  },
  async post({ commit }, data) {
    setLoading(commit, true);
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
    setLoading(commit, false);
  },
  async importCSV({ commit }, formData) {
    setLoading(commit, true);
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
    setLoading(commit, false);
  },
  async update({ commit }, data) {
    setLoading(commit, true);
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
    setLoading(commit, false);
  },
  async delete({ commit }, id) {
    setLoading(commit, true);
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
    setLoading(commit, false);
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
