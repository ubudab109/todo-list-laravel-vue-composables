import { createStore } from 'vuex'
import VuexPersistence from 'vuex-persist';
import auth from './auth';

const vuexLocal = new VuexPersistence({
    storage: window.localStorage,
});

const store = createStore({
    plugins: [vuexLocal.plugin],
    modules: {
        auth,
    }
});

export default store;

