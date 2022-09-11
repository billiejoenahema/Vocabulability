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
};

const actions = {
  async delete({ commit }, id) {
    await axios
      .delete(`/api/precedents/${id}`)
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
    state.data = data.data ?? [];
  },
  setErrors(state, data) {
    state.errors = [];
    state.errors = data ?? [];
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
