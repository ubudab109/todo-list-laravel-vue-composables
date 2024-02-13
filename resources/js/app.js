import {createApp} from 'vue';
import Vue3TagsInput from 'vue3-tags-input';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import store from './stores';
import router from './routes/index';
import App from './App.vue';
import helper from './helpers/index';
import Alert from './components/AlertModal.vue';
import TaskCard from './components/TaskCard.vue';
import ConfirmationModal from './components/ConfirmationModal.vue';
import Pagination from './components/Pagination.vue';
import './css/app.css';
import './scss/custom.scss';

const app = createApp(App);
app.use(store);
app.use(router);
app.use(VueTailwindDatepicker);
app.component('Alert', Alert);
app.component('TaskCard', TaskCard);
app.component('ConfirmationModal', ConfirmationModal);
app.component('TagInput', Vue3TagsInput);
app.component('Pagination', Pagination);
app.mixin({
    computed: {
        Authenticated() {
            return this.$store.getters.Authenticated;
        },
        AuthUser() {
            return this.$store.getters.AuthUser;
        },
        Token() {
            return this.$store.getters.Token;
        },
        CurrentRoute() {
            return this.$route.name;
        },
    },
    methods: helper
});
app.mount("#app");
