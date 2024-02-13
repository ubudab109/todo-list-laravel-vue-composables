<template>
  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <Alert intent="danger" :show="errorRegister" :on-dismiss="() => errorLogin = false">
      <li v-for="(error, key) in message" :key="key" style="color: red; font-weight: bolder">{{ error }}
      </li>
    </Alert>
    <form class="space-y-6" @submit="register" method="POST">
      <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Fullname</label>
        <div class="mt-2">
          <input v-model="name" id="name" name="name" type="text" required
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
        <div class="mt-2">
          <input v-model="email" id="email" name="email" type="email" autocomplete="email" required
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
        </div>
        <div class="mt-2">
          <input v-model="password" id="password" name="password" type="password" autocomplete="current-password" required
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
        </div>
        <div class="mt-2">
          <input v-model="c_password" id="c_password" name="c_password" type="password" autocomplete="current-password"
            required
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <button type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          {{ loading ? 'Loading...' : 'Register' }}
        </button>
      </div>
    </form>
    <p class="mt-10 text-center text-sm text-gray-500">
      Already have an account?
      <span @click="() => this.$router.push({ name: 'Login' })"
        class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500 cursor-pointer">Login Here</span>
    </p>
  </div>
</template>
<script>
import axios from 'axios';
import { ref } from 'vue';
import { useStore } from 'vuex';
import helpers from '../helpers/index';
export default {
  name: 'RegisterPage',
  setup() {
    const email = ref('');
    const name = ref('');
    const password = ref('');
    const c_password = ref('');
    const message = ref([]);
    const errorRegister = ref(false);
    const loading = ref(false);
    const store = useStore();

    async function register(e) {
      e.preventDefault();
      loading.value = true;
      let formData = new FormData();
      formData.append('name', name.value);
      formData.append('email', email.value);
      formData.append('password', password.value);
      formData.append('c_password', c_password.value);
      await axios.post('api/register', formData)
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
          errorRegister.value = true;
          const errStatus = err.response.status;
          const messageError = err.response.data.message;
          message.value = helpers.defaultResponseError(errStatus, messageError);
        }).finally(() => {
          loading.value = false;
        });
    }

    return {
      email,
      name,
      password,
      c_password,
      message,
      errorRegister,
      loading,
      register,
    }
  },
}
</script>