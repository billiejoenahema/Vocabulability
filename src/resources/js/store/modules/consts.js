import axios from 'axios';

const state = {
  data: {},
};

const getters = {
  data(state) {
    return state.data ?? [];
  },
  alphabets(state) {
    return state.data?.ALPHABETS;
  },
  genderTextValue: (state) => (id) => {
    const item = state.data.GENDER?.find((v) => {
      return v.id == id;
    });
    return item?.name ?? '';
  },
  genderFormOptions(state) {
    return state.data?.GENDER;
  },
  japaneseSyllabary(state) {
    return state.data?.JAPANESE_SYLLABARY;
  },
};

const actions = {
  async get({ commit }) {
    await axios
      .get('/api/const')
      .then((res) => {
        commit('setData', res.data);
      })
      .catch(() => {
        commit('setData', {});
      });
  },
  async getIfNeeded({ dispatch, getters }) {
    if (Object.keys(getters.data).length) return;
    await dispatch('get');
  },
};

const mutations = {
  setData(state, data) {
    state.data = data;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
