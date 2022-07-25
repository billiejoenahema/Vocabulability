import { createStore } from 'vuex';
import auth from './modules/auth';
import question from './modules/question';
import profile from './modules/profile';
import toast from './modules/toast';

export const store = createStore({
  modules: {
    auth,
    question,
    profile,
    toast,
  },
});
