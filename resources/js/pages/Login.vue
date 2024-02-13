<template>
    <div class="container my-12 mx-auto px-4 md:px-12">
        <Alert intent="danger" :show="errorLogin" :on-dismiss="() => errorLogin = false">
            <li v-for="(error, key) in message" :key="key" style="color: red; font-weight: bolder">{{ error }}
            </li>
        </Alert>
        <form @submit="login" method="POST">
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                <input v-model="email" id="email" name="email" type="text" autocomplete="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="name@flowbite.com" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                    password</label>
                <input v-model="password" id="password" name="password" type="password" autocomplete="current-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
            </div>
            <div class="mb-6">
                <router-link to="/register">or Register Here</router-link>
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{
                    loading ? 'Loading...' : 'Sign In' }}</button>
        </form>
    </div>
</template>
<script>
import axios from 'axios';
import { ref } from 'vue';
import { useStore } from 'vuex';
import helpers from '../helpers/index';
export default {
    name: 'Login',
    setup() {
        const email = ref('');
        const password = ref('');
        const message = ref([]);
        const errorLogin = ref(false);
        const loading = ref(false);
        const store = useStore();

        async function login(e) {
            e.preventDefault();
            if (email.value === '' || password.value === '') {
                errorLogin.value = true;
                message.value = ['Email and Password is Required'];
            } else {
                loading.value = true;
                let formData = new FormData();
                formData.append('email', email.value);
                formData.append('password', password.value);
                await axios.post('/api/login', formData)
                    .then(res => {
                        const data = res.data.response;
                        const token = data.token;
                        const user = data.user;
                        store.dispatch('loginOrRegister', {
                            user: user,
                            token: token,
                        });
                        window.location.reload();
                    }).catch(err => {
                        errorLogin.value = true;
                        const errStatus = err.response.status;
                        const messageError = err.response.data.message;
                        message.value = helpers.defaultResponseError(errStatus, messageError);
                    }).finally(() => {
                        loading.value = false;
                    })
            }
        };

        return {
            email,
            password,
            message,
            errorLogin,
            loading,
            login,
        }
    },
}
</script>