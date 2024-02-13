import VuexPersistence from 'vuex-persist';

const vuexLocal = new VuexPersistence({
    storage: window.localStorage,
});

export default {
    state: {
        // Auth User
        AuthUser: [],
        Authenticated: false,
        Token: null,

    },
    actions: {
        loginOrRegister({
            commit
        }, {
            user,
            token,
        }) {
            commit('SET_AUTHUSER', user);
            commit('SET_AUTHENTICATED', true);
            commit('SET_TOKEN', token);
        },
        logout({
            commit
        }) {
            commit('SET_AUTHUSER', []);
            commit('SET_AUTHENTICATED', false);
            commit('SET_TOKEN', null);
        }
    },
    mutations: {
        SET_AUTHUSER(state, user) {
            state.AuthUser = user;
        },
        SET_AUTHENTICATED(state, data) {
            state.Authenticated = data;
        },
        SET_TOKEN(state, data) {
            state.Token = data;
        },
    },
    getters: {
        AuthUser(state) {
            return state.AuthUser;
        },
        Authenticated(state) {
            return state.Authenticated;
        },
        Token(state) {
            return state.Token;
        },
    },
    plugins: [vuexLocal.plugin],
}
