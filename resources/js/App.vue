<template>
    <div class="flex flex-col h-screen justify-between">
      <!-- Top Bar Nav -->
      <nav v-if="authenticated" class="w-full py-4 bg-blue-800 shadow p-4">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-between">
          <nav>
            <ul class="flex items-center justify-between font-bold text-sm text-white uppercase no-underline">
              <li><router-link to="/">Home</router-link></li>
            </ul>
          </nav>
  
          <div class="flex items-center text-lg no-underline text-white pr-6">
            <div>
              <div class="relative">
                <!-- Dropdown toggle button -->
                <button @click="isOpen" class="flex items-center p-2 text-indigo-100 bg-indigo-600 rounded-md">
                  <span class="mr-4">Menu</span>
                  <svg class="w-5 h-5 text-indigo-100 dark:text-white" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
  
                <!-- Dropdown menu -->
                <div v-show="show" class="absolute right-0 py-2 mt-2 bg-indigo-500 rounded-md shadow-xl w-44">
                  <h5
                    class="block px-4 py-2 text-sm text-indigo-100 ">
                    Name: {{user.name}}
                  </h5>
                  <div style="cursor: pointer;" @click="logout"
                    class="block px-4 py-2 text-sm text-indigo-100  hover:bg-indigo-400 hover:text-indigo-100">
                    Logout
                  </div>
                </div>
              </div>
            </div>
  
          </div>
        </div>
  
      </nav>
  
      <!-- Text Header -->
      <header class="w-full container mx-auto p-4">
        <div class="flex flex-col items-center py-12">
          <a class="font-bold text-gray-800 uppercase hover:text-gray-700 text-5xl" href="#">
            Todo Task List
          </a>
          <p class="text-lg text-gray-600">
            Fullstack Developer Test
          </p>
        </div>
      </header>
  
      <main class="container mx-auto p-4">
        <router-view></router-view>
      </main>
  
      <footer class="bg-neutral-200 text-center dark:bg-neutral-700 lg:text-left">
        <div class="p-4 text-center text-neutral-700 dark:text-neutral-200">
          Fullstack Test:
          <a class="text-neutral-800 dark:text-neutral-400" href="/#">Muhammad Rizky Firdaus</a>
        </div>
      </footer>
    </div>
  </template>
  <script>
  import { onMounted, ref } from 'vue';
  import method from './axios/axios-setup';
  import { useStore } from 'vuex';
  export default {
    setup() {
      let show = ref(false);
      const authenticated = ref(false);
      const user = ref(null);
      const store = useStore();
  
      async function logout() {
        await method.post('/api/logout')
          .then(() => {
            localStorage.clear();
            window.location.reload()
          });
      };
  
      const isOpen = () => (show.value = !show.value);
  
      onMounted(() => {
        authenticated.value = store.state.auth.Authenticated;
        user.value = store.state.auth.AuthUser
      });
  
      return {
        show,
        authenticated,
        user,
        logout,
        isOpen,
      };
    },
  }
  </script>