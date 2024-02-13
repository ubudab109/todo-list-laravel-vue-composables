<template>
    <div class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <Alert intent="danger" :show="isErrorCreateTask" :on-dismiss="() => isErrorCreateTask = false">
            <li v-for="(error, key) in messageErrorCreate" :key="key" style="color: red; font-weight: bolder">{{ error }}
            </li>
        </Alert>
        <form class="w-full" @submit="submitTask">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Title
                    </label>
                    <input v-model="title"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        type="text" placeholder="Create Your Title Task here">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Description
                    </label>
                    <textarea v-model="description"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        placeholder="Create Your Description Task here"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Due Date
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        type="date" v-model="dueDate">
                    <p class="text-gray-600 text-xs italic">Optional</p>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Task Priority
                    </label>
                    <div class="relative">
                        <select
                            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            v-model="priorityForm">
                            <option selected value="" disabled>Select Priority</option>
                            <option v-for="item in priorityFields()" :value="item.value" :key="item.value">{{ item.text }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">
                        Tag
                    </label>
                    <TagInput placeholder="enter some tags" :tags="tags"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        @on-tags-changed="handleChangeTag"></TagInput>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">
                        Files
                    </label>
                    <div v-if="filesData.length > 0" class="files">
                        <div class="file-item" v-for="(file, index) in filesData" :key="index">
                            <span>{{ file.filename }}</span>
                            <span class="delete-file"
                                @click="handleClickDeleteFile(file.id, file.filename, index)">Delete</span>
                        </div>
                    </div>
                    <div class="dropzone" v-bind="getRootProps()">
                        <div class="border-dropzone" :class="{
                            isDragActive,
                        }">
                            <input v-bind="getInputProps()" />
                            <p v-if="isDragActive">Drop the files here ...</p>
                            <p v-else>Drag and drop files here, or Click to select files</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2"
                        @click="() => this.$router.push({ name: 'Home' })">
                        Back
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import { ref } from 'vue';
import Swal from 'sweetalert2';
import { useDropzone } from "vue3-dropzone";
import useFiles from '../composables/file';
import useTasks from '../composables/task';
import { useRouter } from 'vue-router';

export default {
    name: 'CreateTask',
    setup() {
        const { uploadFiles, deleteFiles } = useFiles();
        const { getRootProps, getInputProps, open, isDragActive } = useDropzone({ onDrop });
        const { createTask, loadingCreate, isErrorCreateTask, messageErrorCreate, messageSuccessCreateTask } = useTasks();
        const tags = ref([]);
        let filesData = ref([]);
        const title = ref('');
        const description = ref('');
        const dueDate = ref('');
        const priorityForm = ref('');
        const router = useRouter();

        async function onUploadFiles(files) {
            await uploadFiles(files).then((data) => {
                if (filesData.value.length < 1) {
                    filesData.value = data;
                } else {
                    data.forEach(item => {
                        filesData.value.push(item);
                    });
                }
            }).catch((err) => {
                Swal.fire({
                    icon: 'error',
                    title: err,
                });
            });
        }

        async function onDrop(acceptFiles, _) {
            await onUploadFiles(acceptFiles); // saveFiles as callback
        }

        async function handleClickDeleteFile(id, filename, index) {
            await deleteFiles(id, filename)
                .then(() => {
                    filesData.value.splice(index, 1);
                }).catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: err,
                    });
                })
        }

        function handleChangeTag(tag) {
            const data = [];
            data.push(tag);
            tags.value = data[0];
        };

        async function submitTask(e) {
            e.preventDefault();
            let formBody = new FormData();
            formBody.append('title', title.value);
            formBody.append('description', description.value);
            if (dueDate.value !== '') {
                formBody.append('due_date', dueDate.value);
            }
            formBody.append('priority', priorityForm.value);
            tags.value.forEach((tag, key) => {
                formBody.append(`tags[${key}]`, tag);
            });
            filesData.value.forEach((file, index) => {
                formBody.append(`files[${index}][id]`, file.id);
            });
            isErrorCreateTask.value = false;
            await createTask(formBody);
            if (!isErrorCreateTask.value) {
                Swal.fire({
                    icon: 'success',
                    title: messageSuccessCreateTask.value
                }).then(() => {
                    router.push({ name: 'Home' });
                });
            } else {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
        }

        return {
            tags,
            isDragActive,
            filesData,
            loadingCreate,
            isErrorCreateTask,
            messageErrorCreate,
            messageSuccessCreateTask,
            title,
            description,
            dueDate,
            priorityForm,
            open,
            getRootProps,
            getInputProps,
            handleChangeTag,
            handleClickDeleteFile,
            createTask,
            submitTask,
        };
    },
}
</script>