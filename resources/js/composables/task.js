import {
    ref
} from 'vue';
import {
    useRouter
} from 'vue-router';
import method from '../axios/axios-setup';
import helpers from '../helpers';

export default function useTasks() {
    const tasks = ref([]);
    const task = ref({});
    const paginator = ref({});
    const loading = ref(false);
    const loadingDetail = ref(false);
    const loadingUpdate = ref(false);
    const loadingCreate = ref(false);
    const isErrorFetchingTasks = ref(false);
    const isErrorUpdateStatus = ref(false);
    const isErrorCreateTask = ref(false);
    const isErrorUpdateTask = ref(false);
    const isErrorDeleteTask = ref(false);
    const isSuccessUpdate = ref(false);
    const isSuccessUpdateStatus = ref(false);
    const isSuccessCreateTask = ref(false);
    const isSuccessDeleteTask = ref(false);
    const isSuccessArchivedTask = ref(false);
    const messageErrorUpdateStatus = ref('');
    const messageErrorCreate = ref([]);
    const messageErrorUpdate = ref([]);
    const messageErrorFetchTasks = ref([]);
    const messageSuccessMarkTask = ref('');
    const messageSuccessArchive = ref('');
    const messageSuccessCreateTask = ref('');
    const messageSuccessUpdateTask = ref('');
    const messageDeleteTask = ref('');
    const router = useRouter();

    /**
     * The `getTasks` function is an asynchronous function that retrieves tasks from an API based on
     * given orders and parameters, and updates the tasks, pagination, and loading state accordingly.
     * @param {Object} orders - The `orders` parameter is an object that contains the sorting orders for the
     * tasks. Each key in the object represents a field to sort by, and the corresponding value
     * represents the sorting order (e.g., ascending or descending).
     * @param {Object} params - An object containing various parameters for filtering the tasks. Each key in the
     * object represents a parameter name, and the corresponding value represents the parameter value.
     * @param {Number} page - current page
     */
    const getTasks = async (orders, params, page) => {
        loading.value = true;
        try {
            let queryParam = '';
            Object.keys(params).forEach((param, key) => {
                if (key === 0) {
                    queryParam += `param[${param}]=${params[param]}`
                } else {
                    queryParam += `&param[${param}]=${params[param]}`
                }
            });
            if (orders.by !== '') {
                queryParam += `&order[by]=${orders.by}&order[type]=${orders.type}`
            }
            const response = await method.get(`/api/tasks?${queryParam}&page=${page}`);
            const data = response.data.response;
            tasks.value = data.data;
            paginator.value = data.pagination;
        } catch (err) {
            isErrorFetchingTasks.value = true;
            const errStatus = err.response.status;
            if (errStatus === 401) {
                router.push({
                    name: 'Login'
                });
            }
            const messageError = err.response.data.message;
            messageErrorFetchTasks.value = helpers.defaultResponseError(errStatus, messageError);
        } finally {
            loading.value = false;
        }
    };

    /**
     * The function `getTask` is an asynchronous function that retrieves a task with additional data
     * from an API and handles any errors that occur during the process.
     * @param {Number} taskId - The `taskId` parameter is the ID of the task that you want to retrieve.
     */
    const getTask = async (taskId) => {
        loadingDetail.value = true;
        try {
            const response = await method.get(`/api/tasks/${taskId}?with[]=user&with[]=files.file`);
            const data = response.data.response;
            task.value = data;
        } catch (err) {
            isErrorFetchingTasks.value = true;
            const errStatus = err.response.status;
            if (errStatus === 401) {
                router.push({
                    name: 'Login'
                });
            }
            const messageError = err.response.data.message;
            messageErrorFetchTasks.value = helpers.defaultResponseError(errStatus, messageError);
        } finally {
            loadingDetail.value = false;
        }
    };

    /**
     * The function `markCompleteTask` is an asynchronous function that updates the status of a task by
     * making a PUT request to an API endpoint.
     * @param {Number} id - The `id` parameter is the unique identifier of the task that needs to be marked as
     * complete. It is used to identify the specific task in the API endpoint
     * `api/task-completed/`.
     * @param {String} type - The `type` parameter is used to specify the type of task completion.
     * completion.
     */
    const markCompleteTask = async (id, type) => {
        loading.value = true;
        try {
            const response = await method.put(`/api/task-completed/${id}`, {
                type: type
            });
            const data = response.data;
            messageSuccessMarkTask.value = data.message;
            isSuccessUpdateStatus.value = true;
        } catch (err) {
            isSuccessUpdateStatus.value = false;
            isErrorUpdateStatus.value = false;
            messageErrorUpdateStatus.value = err.response.data.message;
        } finally {
            loading.value = false;
        }
    };

    /**
     * The `markArchived` function is an asynchronous function that updates the status of a task to
     * archived and handles success and error messages.
     * @param {Number} id - The `id` parameter is the unique identifier of the task that needs to be archived.
     * @param {String} type - The `type` parameter is used to specify the type of archive action to be
     * performed. It could be a string value indicating the type of archive, such as "completed",
     * "deleted", or any other relevant type.
     */
    const markArchived = async (id, type) => {
        loading.value = true;
        try {
            const response = await method.put(`/api/task-archived/${id}`, {
                type: type
            });
            const data = response.data;
            messageSuccessArchive.value = data.message;
            isSuccessArchivedTask.value = true;
        } catch (err) {
            isSuccessArchivedTask.value = false;
            isErrorUpdateStatus.value = false;
            messageErrorUpdateStatus.value = err.response.data.message;
        } finally {
            loading.value = false;
        }
    };

    /**
     * The `deleteTask` function is an asynchronous function that deletes a task by making a DELETE
     * request to an API endpoint and handles loading, success, and error states.
     * @param {Number} id - The `id` parameter is the unique identifier of the task that needs to be deleted.
     */
    const deleteTask = async (id) => {
        loading.value = true;
        try {
            await method.delete(`/api/tasks/${id}`);
            messageDeleteTask.value = 'Data Deleted Successfully';
        } catch (err) {
            isErrorDeleteTask.value = true;
        } finally {
            loading.value = false;
        }
    };

    /**
     * The `createTask` function is an asynchronous function that sends a POST request to an API
     * endpoint to create a new task, and handles success and error responses.
     * @param {Object} form - The `form` parameter is an object that contains the data needed to create a task.
     * It is passed as an argument to the `createTask` function.
     */
    const createTask = async (form) => {
        loadingCreate.value = true;
        try {
            const response = await method.post('/api/tasks', form);
            const data = response.data;
            messageSuccessCreateTask.value = data.message;
            isSuccessCreateTask.value = true;
        } catch (err) {
            const errStatus = err.response.status;
            const messageError = err.response.data.message;
            isErrorCreateTask.value = true;
            messageErrorCreate.value = helpers.defaultResponseError(errStatus, messageError);
        } finally {
            loadingCreate.value = false;
        }
    };

    /**
     * The `createTask` function is an asynchronous function that sends a POST request to an API
     * endpoint to create a new task, and handles success and error responses.
     * @param {Object} form - The `form` parameter is an object that contains the data needed to create a task.
     * It is passed as an argument to the `createTask` function.
     */
     const updateTask = async (id, form) => {
        loadingUpdate.value = true;
        try {
            const response = await method.put(`/api/tasks/${id}`, form);
            const data = response.data;
            messageSuccessUpdateTask.value = data.message;
            isSuccessUpdate.value = true;
        } catch (err) {
            const errStatus = err.response.status;
            const messageError = err.response.data.message;
            isErrorUpdateTask.value = true;
            messageErrorUpdate.value = helpers.defaultResponseError(errStatus, messageError);
        } finally {
            loadingUpdate.value = false;
        }
    };

    return {
        tasks,
        task,
        paginator,
        loading,
        loadingDetail,
        loadingUpdate,
        loadingCreate,
        isErrorUpdateStatus,
        isErrorCreateTask,
        isErrorUpdateTask,
        isErrorDeleteTask,
        isSuccessUpdateStatus,
        isSuccessCreateTask,
        isSuccessUpdate,
        isSuccessDeleteTask,
        isSuccessArchivedTask,
        messageErrorCreate,
        messageErrorUpdate,
        messageErrorUpdateStatus,
        messageSuccessMarkTask,
        messageSuccessCreateTask,
        messageSuccessUpdateTask,
        messageSuccessArchive,
        messageDeleteTask,
        getTasks,
        markCompleteTask,
        markArchived,
        deleteTask,
        createTask,
        getTask,
        updateTask,
    };
}
