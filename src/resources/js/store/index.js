import { createStore } from 'vuex';
import auth from './modules/auth';
import consts from './modules/consts';
import item from './modules/item';
import loading from './modules/loading';
import precedent from './modules/precedent';
import profile from './modules/profile';
import question from './modules/question';
import test_mail from './modules/test_mail';
import toast from './modules/toast';
import upload_file from './modules/upload_file';

export const store = createStore({
  modules: {
    auth,
    consts,
    item,
    loading,
    precedent,
    profile,
    question,
    test_mail,
    toast,
    upload_file,
  },
});
