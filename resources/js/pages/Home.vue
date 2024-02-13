<template>
    <div>
        <div class="flex">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                @click="() => this.$router.push({ name: 'CreateTask' })">
                Create Button
            </button>
        </div>
        <div
            class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <div class="container my-12 mx-auto px-4 md:px-12">
                <div class="w-full py-4 border-t border-b">
                    <div class="flex flex-wrap -mx-3 mb-2 p-3">
                        <!-- KEYWORD FILTER -->
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 mt-2">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Keyword
                            </label>
                            <input @keyup="searchKeyword"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                v-model.lazy="filter.query" type="text" placeholder="Search title or description">
                        </div>
                        <!-- COMPLETED DATE FILTER -->
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 mt-2">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Completed Date
                            </label>
                            <vue-tailwind-datepicker v-model="dateValueCompleted" placeholder="Select completed date range"
                                :formatter="formatter" @update:model-value="(e) => selectMonthCompleted(e, 'completed')"
                                :shortcuts="false" :options="optionsDate" :auto-apply="false" />
                        </div>
                        <!-- DUE DATE FILTER -->
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 mt-2">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Due Date
                            </label>
                            <vue-tailwind-datepicker v-model="dateValueDueDate" placeholder="Select due date range"
                                :formatter="formatter" @update:model-value="(e) => selectMonthCompleted(e, 'due_date')"
                                :shortcuts="false" :options="optionsDate" :auto-apply="false" />
                        </div>
                        <!-- ARCHIVED DATE FILTER -->
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 mt-2">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 mb">
                                Archived Date
                            </label>
                            <vue-tailwind-datepicker v-model="dateValueArchivedDate"
                                placeholder="Select archived date range" :formatter="formatter"
                                @update:model-value="(e) => selectMonthCompleted(e, 'archived_date')" :shortcuts="false"
                                :options="optionsDate" :auto-apply="false" />
                        </div>
                        <!-- PRIORITY FILTER -->
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 mt-2">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 mb">
                                Priority
                            </label>
                            <select
                                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="filter.priority">
                                <option selected value="">All</option>
                                <option v-for="item in priorityFields()" :value="item.value" :key="item.value">{{ item.text }}
                                </option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!-- ORDER SECTION -->
                    <div class="flex flex-wrap -mx-3 mb-2 p-3">
                        <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Order By
                            </label>
                            <select
                                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="order.by">
                                <option selected value="">No Order</option>
                                <option v-for="orderField in orderFields" :value="orderField.field" :key="orderField.field">
                                    {{ orderField.name }}</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Order
                            </label>
                            <select
                                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="order.type" :disabled="order.by === ''">
                                <option selected value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="loading" class="text-center">
                Fetching....
            </div>
            <div v-else>
                <div v-if="tasks.length < 1" class="text-center">
                    You not have any task. Please create one task.
                </div>
                <div v-else>
                    <div v-for="task in tasks" :key="task.id">
                        <TaskCard :id="parseInt(task.id)" :title="task.title" :description="task.description"
                            :dueDate="task.due_date" :isTodo="task.is_todo" :isOverdue="task.is_overdue"
                            :priority="parseInt(task.priority)" :isCompleted="task.is_completed"
                            :isArchived="task.is_archived" :tags="task.tags" :createdAt="task.created_at"
                            @refreshTasks="fetchTasks" />
                    </div>
                    <Pagination :total-pages="pagination.totalPage" :total="pagination.total" :per-page="pagination.perPage"
                        :current-page="pagination.currentPage" :has-more-pages="true" :maxVisibleButtons="10"
                        @pageChanged="onPageChange">

                    </Pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, watchEffect, ref } from 'vue';
import useTasks from '../composables/task';

export default {
    setup() {
        const formatter = ref({
            date: 'YYYY-MM-DD',
            month: 'MMM',
        });
        const optionsDate = ref({
            footer: {
                apply: 'Apply',
                cancel: 'Cancel',
            }
        });
        const filter = reactive({
            completed_date_start: '',
            completed_date_end: '',
            due_date_start: '',
            due_date_end: '',
            archived_date_start: '',
            archived_date_end: '',
            priority: '',
            query: '',
        });
        let pagination = reactive({
            currentPage: 1,
            total: 0,
            perPage: 10,
            totalPage: 1,
        });
        const dateValueCompleted = ref({
            startDate: '',
            endDate: '',
        });
        const dateValueDueDate = ref({
            startDate: '',
            endDate: '',
        });
        const dateValueArchivedDate = ref({
            startDate: '',
            endDate: '',
        });
        const order = reactive({
            by: '',
            type: 'asc'
        });
        const orderFields = ref([
            {
                field: 'title',
                name: 'Title',
            },
            {
                field: 'description',
                name: 'Description',
            },
            {
                field: 'priority',
                name: 'Priority',
            },
            {
                field: 'due_date',
                name: 'Due Date',
            },
            {
                field: 'completed_date',
                name: 'Completed Date',
            },
            {
                field: 'created_at',
                name: 'Created At',
            },
        ]);
        const {
            tasks,
            loading,
            isSuccessUpdateStatus,
            messageErrorUpdateStatus,
            messageSuccessMarkTask,
            paginator,
            getTasks,
        } = useTasks();

        function searchKeyword(e) {
            filter.query = e.target.value
        };

        function selectMonthCompleted(date, type) {
            switch (type) {
                case 'completed':
                    filter.completed_date_end = date.endDate;
                    filter.completed_date_start = date.startDate;
                    break;
                case 'due_date':
                    filter.due_date_start = date.startDate;
                    filter.due_date_end = date.endDate;
                    break;
                case 'archived_date':
                    filter.archived_date_start = date.startDate;
                    filter.archived_date_end = date.endDate;
                    break;
                default:
                    null;
            }
        }

        function orderBy(by) {
            order[by] = order[by] === 'asc' ? 'desc' : 'asc';
        }

        async function fetchTasks() {
            await getTasks(order, filter);
        };

        function onPageChange(page) {
            pagination.currentPage = page;
        }

        function setPagination() {
            pagination.currentPage = paginator.value.current_page;
            pagination.total = paginator.value.total;
            pagination.perPage = paginator.value.per_page;
            pagination.totalPage = paginator.value.last_page;
        }

        onMounted(async () => {
            await fetchTasks(order, filter, pagination.currentPage);
            setPagination();
        });

        watchEffect(async () => {
            await getTasks(order, filter, pagination.currentPage);
            setPagination();
        });

        return {
            tasks,
            loading,
            isSuccessUpdateStatus,
            messageErrorUpdateStatus,
            messageSuccessMarkTask,
            filter,
            order,
            orderFields,
            dateValueCompleted,
            dateValueDueDate,
            dateValueArchivedDate,
            formatter,
            optionsDate,
            paginator,
            pagination,
            getTasks,
            fetchTasks,
            searchKeyword,
            selectMonthCompleted,
            orderBy,
            onPageChange,
        };
    }
}
</script>

<style>
.project.completed {
    border-left: 4px solid turquoise;
}

.project.ongoing {
    border-left: 4px solid orange;
}

.project.overdue {
    border-left: 4px solid tomato;
}

.v-chip.completed {
    background: turquoise !important;
}

.v-chip.ongoing {
    background: orange !important;
}

.v-chip.overdue {
    background: tomato !important;
}</style>