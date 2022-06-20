import { createStore } from 'vuex';
import question from './modules/question';
import toast from './modules/toast';

export const store = createStore({
  modules: {
    question,
    toast,
  },
});
