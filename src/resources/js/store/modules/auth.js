import axios from 'axios';

const state = {
  errors: {},
};

const getters = {
  invalidFeedback: (state) => (props) => {
    return state.errors[props] ?? '';
  },
  hasErrors(state) {
    return Object.keys(state.errors).length > 0;
  },
};

const actions = {
  async login({ commit }, data) {
    await axios
      .get('http://localhost:8080/sanctum/csrf-cookie')
      .then(async (res) => {
        await axios
          .post('/login', data)
          .then((res) => {
            commit('resetErrors');
          })
          .catch((err) => {
            commit(
              'setErrors',
              err.response.data.errors ?? err.response.data.message
            );
            commit(
              'toast/setData',
              {
                status: err.response.status,
                content: err.response.data.message,
              },
              { root: true }
            );
          });
      });
  },
  async logout() {
    await axios.post('/logout');
  },
};

const mutations = {
  setErrors(state, data) {
    state.errors = {};
    state.errors = data ?? {};
  },
  resetErrors(state) {
    state.errors = {};
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
