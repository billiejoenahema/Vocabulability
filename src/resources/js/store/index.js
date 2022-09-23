import { createStore } from 'vuex';
import auth from './modules/auth';
import consts from './modules/consts';
import item from './modules/item';
import loading from './modules/loading';
import precedent from './modules/precedent';
import profile from './modules/profile';
import question from './modules/question';
import toast from './modules/toast';

export const store = createStore({
  modules: {
    auth,
    consts,
    item,
    loading,
    precedent,
    profile,
    question,
    toast,
  },
});
