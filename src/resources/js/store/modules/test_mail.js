import { shuffle } from '../../functions/shuffle';
import axios from 'axios';

const state = {
  data: [],
  errors: {},
};

const getters = {
  data(state) {
    return state.data ?? [];
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
  async send({ commit }) {
    await axios
      .get('/api/test-mail')
      .then((res) => {
        commit('setErrors', {});
        commit('setData', res.data);
      })
      .catch((err) => {
        commit('setErrors', err.response.data.errors);
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
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
