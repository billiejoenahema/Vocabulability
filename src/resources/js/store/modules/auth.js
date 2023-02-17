import axios from 'axios';

const guest = {
  email: 'guest@example.com',
  password: 'guest_user',
};

const state = {
  errors: {},
};

const getters = {
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
};

const actions = {
  async guestLogin({ commit }) {
    await axios
      .get('http://localhost:8080/sanctum/csrf-cookie')
      .then(async (res) => {
        await axios
          .post('/login', guest)
          .then((res) => {
            commit('setErrors', {});
          })
          .catch((err) => {
            commit(
              'setErrors',
              err.response.data.errors ?? err.response.data.message
            );
          });
      });
  },
  async login({ commit }, data) {
    await axios
      .get('http://localhost:8080/sanctum/csrf-cookie')
      .then(async (res) => {
        await axios
          .post('/login', data)
          .then(() => {
            commit('setErrors', {});
          })
          .catch((err) => {
            commit(
              'setErrors',
              err.response.data.errors ?? err.response.data.message
            );
            if (err.response.status !== 401) return;
            commit(
              'toast/setData',
              {
                status: err.response.status,
                content: err.response.data.errors,
              },
              { root: true }
            );
          });
      });
  },
  async logout({ commit }) {
    await axios.post('/logout').then((res) => {
      commit(
        'toast/setData',
        { status: res.status, content: res.data.message },
        { root: true }
      );
    });
  },
  async forgotPassword({ commit }, data) {
    await axios
      .post('/api/forgot-password', data)
      .then((res) => {
        commit('setErrors', {});
        commit(
          'toast/setData',
          { status: res.status, content: res.data.message },
          { root: true }
        );
      })
      .catch((err) => {
        commit(
          'setErrors',
          err.response.data.errors ?? err.response.data.message
        );
      });
  },
  async resetPassword({ commit }, data) {
    await axios
      .post('/api/reset-password', data)
      .then((res) => {
        commit('setErrors', {});
        commit(
          'toast/setData',
          { status: res.status, content: res.data.message },
          { root: true }
        );
      })
      .catch((err) => {
        commit(
          'setErrors',
          err.response.data.errors ?? err.response.data.message
        );
      });
  },
};

const mutations = {
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
