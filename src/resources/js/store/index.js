import { createStore } from 'vuex';
import auth from './modules/auth';
import item from './modules/item';
import loading from './modules/loading';
import profile from './modules/profile';
import question from './modules/question';
import toast from './modules/toast';

export const store = createStore({
  modules: {
    auth,
    item,
    loading,
    profile,
    question,
    toast,
  },
});
