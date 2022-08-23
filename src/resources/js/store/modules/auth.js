import axios from 'axios';

const guest = {
  email: 'guest@example.com',
  password: 'guest_user',
};

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
  isInvalid: (state) => (key) => {
    return state.errors?.[key] ? 'invalid' : '';
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
          .then((res) => {
            commit('resetErrors');
          })
          .catch((err) => {
            commit(
              'setErrors',
              err.response.data.errors ?? err.response.data.message
            );
            if (err.response.status === 401) {
              commit(
                'toast/setData',
                {
                  status: err.response.status,
                  content: 'ログインに失敗しました。',
                },
                { root: true }
              );
            }
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
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
