import axios from 'axios';

const state = {
  data: {},
  errors: {},
};

const getters = {
  profile(state) {
    return Object.entries(state.data).length > 0;
  },
  isLogin(state) {
    return state.data?.id > 0;
  },
  hasErrors(state) {
    return Object.keys(state.errors).length > 0;
  },
  errors(state) {
    return state.errors ?? {};
  },
};

const actions = {
  async get({ commit }) {
    await axios
      .get('/api/profile')
      .then((res) => {
        commit('setErrors', {});
        commit('setData', res.data.data);
      })
      .catch((err) => {
        commit('setErrors', err.message);
        commit('setData', {});
      });
  },
  async getIfNeeded({ dispatch, getters }) {
    if (getters.isLogin) return;
    await dispatch('get');
  },
};

const mutations = {
  setData(state, data) {
    state.data = data;
  },
  setErrors(state, data) {
    state.errors = {};
    state.errors = data;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
