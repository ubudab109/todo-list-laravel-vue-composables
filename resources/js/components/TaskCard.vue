<template>
    <div class="w-full p-4">
        <!-- CONFIRMATION COMPLETED -->
        <ConfirmationModal title="Are You Sure Want Mark as Completed This Task?"
            :on-dismiss="() => showConfirmationMarkCompleted = false" intent="info" :show="showConfirmationMarkCompleted"
            :on-ok="() => markTask('completed', 'set')">
        </ConfirmationModal>
        <!-- CONFIRMATION INCOMPLETED -->
        <ConfirmationModal title="Are You Sure Want Mark as Incomplete This Task?"
            :on-dismiss="() => showConfirmationMarkIncomplete = false" intent="info" :show="showConfirmationMarkIncomplete"
            :on-ok="() => markTask('completed', 'remove')">
        </ConfirmationModal>
        <!-- CONFIRMATION ARCHIVE -->
        <ConfirmationModal title="Are You Sure Want To Archive This Task?"
            :on-dismiss="() => showConfirmationMarkArchived = false" intent="info" :show="showConfirmationMarkArchived"
            :on-ok="() => markTask('archived', 'set')">
        </ConfirmationModal>
        <!-- CONFIRMATION UNARCHIVE -->
        <ConfirmationModal title="Are You Sure Want To Unarchive This Task?"
            :on-dismiss="() => showConfirmationMarkUnarchived = false" intent="info" :show="showConfirmationMarkUnarchived"
            :on-ok="() => markTask('archived', 'remove')">
        </ConfirmationModal>
        <!-- CONFIRMATION DELETE -->
        <ConfirmationModal title="Are You Sure Want To Delete This Task?"
            :on-dismiss="() => showConfirmationDelete = false" intent="info" :show="showConfirmationDelete"
            :on-ok="() => removeTask()">
        </ConfirmationModal>

        <div class="bg-white shadow-md hover:shadow-xl rounded-lg overflow-hidden">
            <div class="p-4">
                <span :class="badgePriority(priority)">{{ textPriority(priority) }}</span>
                <h2 class="mt-2 mb-2 font-bold">{{ title }}</h2>
                <div class="text-sm" v-html="description"></div>
                <span class="mt-2 mr-2 inline-block px-2 py-1 leading-none bg-purple-200 text-purple-800 rounded-full font-semibold uppercase tracking-wide text-xs" v-for="(tag, index) in JSON.parse(tags)" :key="index">#{{tag}}</span>
                <h5 class="mt-2 mb-2">Created at: {{ formatTimestamp(createdAt) }}</h5>

            </div>
            <div class="p-4 border-t border-b text-xs text-gray-700">
                <div class="mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-100 hover:bg-gray-200 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">Deadline: <span
                                    class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-medium text-gray-500 bg-gray-200 rounded dark:bg-gray-700 dark:text-gray-400">{{
                                        dueDate }}</span></span>

                        </div>
                        <div
                            class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-100 hover:bg-gray-200 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">Status: <span
                                    :class="isTodo ? ' bg-blue-200 dark:bg-blue-700 dark:text-blue-400' : ' bg-green-200  dark:bg-green-700 dark:text-green-400'"
                                    class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-medium text-gray-500 rounded">{{
                                        isTodo ? 'To Do' : 'Completed' }}</span></span>
                        </div>
                        <div v-if="isOverdue"
                            class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-orange-200 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap">Overdue</span>

                        </div>
                        <div v-if="isArchived"
                            class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-blue-200 hover:bg-blue-300 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>

                            <span class="flex-1 ml-3 whitespace-nowrap">Archived</span>

                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-b text-xs text-gray-700">
                <div class="flex justify-end mb-1">
                    <div class="tooltip ml-3 mr-2 cursor-pointer"
                        @click="() => !isCompleted ? showConfirmationMarkCompleted = true : showConfirmationMarkIncomplete = true">
                        <div v-if="!isCompleted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div v-else>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <span class="tooltiptext">
                            {{ isCompleted ? 'Mark as Incomplete' : 'Mark as Completed' }}
                        </span>
                    </div>
                    <div class="tooltip mr-2 cursor-pointer"
                        @click="() => !isArchived ? showConfirmationMarkArchived = true : showConfirmationMarkUnarchived = true">
                        <div v-if="!isArchived">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div v-else>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <span class="tooltiptext">{{ isArchived ? 'Unarchive' : 'Archive' }}</span>
                    </div>
                    <div class="tooltip mr-2 cursor-pointer" @click="detailTask">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        <span class="tooltiptext">Edit</span>
                    </div>
                    <div class="tooltip cursor-pointer" @click="() => showConfirmationDelete = true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        <span class="tooltiptext">Delete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent, ref } from "vue";
import useTasks from '../composables/task';
import Swal from 'sweetalert2';
import { useRouter } from "vue-router";

export default defineComponent({
    props: {
        id: {
            type: Number,
            default: 0,
        },
        title: {
            type: String,
            default: '',
        },
        description: {
            type: String,
            default: '',
        },
        dueDate: {
            type: String,
            default: '',
        },
        isTodo: {
            type: Boolean,
            default: 0,
        },
        isOverdue: {
            type: Boolean,
            default: 0,
        },
        isCompleted: {
            type: Boolean,
            default: 0,
        },
        isArchived: {
            type: Boolean,
            default: 0,
        },
        priority: {
            type: Number,
            default: 0,
        },
        tags: {
            type: String,
            default: '',
        },
        createdAt: {
            type: String,
            default: '',
        }
    },
    setup(props, { emit }) {
        const showConfirmationMarkCompleted = ref(false);
        const showConfirmationMarkArchived = ref(false);
        const showConfirmationDelete = ref(false);
        const showConfirmationMarkIncomplete = ref(false);
        const showConfirmationMarkUnarchived = ref(false);
        const {
            markCompleteTask,
            markArchived,
            isSuccessUpdateStatus,
            loading,
            messageErrorUpdateStatus,
            messageSuccessMarkTask,
            messageSuccessArchive,
            messageDeleteTask,
            deleteTask,
        } = useTasks();

        const router = useRouter();
        
        async function markTask(markType, type) {
            switch (markType) {
                case 'completed':
                    await markCompleteTask(props.id, type).then(() => {
                        showConfirmationMarkCompleted.value = false;
                        showConfirmationMarkIncomplete.value = false;
                        Swal.fire({
                            icon: 'success',
                            title: messageSuccessMarkTask.value,
                        }).then(() => {
                            emit('refreshTasks');
                        })
                    }).catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: messageErrorUpdateStatus.value,
                        });
                    });
                    break;
                case 'archived':
                    await markArchived(props.id, type).then(() => {
                        showConfirmationMarkArchived.value = false;
                        showConfirmationMarkUnarchived.value = false;
                        Swal.fire({
                            icon: 'success',
                            title: messageSuccessArchive.value,
                        }).then(() => {
                            emit('refreshTasks');
                        })
                    }).catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: messageErrorUpdateStatus.value,
                        });
                    });
                default:
                    return null;
            };
        };

        async function removeTask() {
            await deleteTask(props.id)
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: messageDeleteTask.value
                }).then(() => {
                    emit('refreshTasks');
                });
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'There is something wrong when deleting data',
                });
            }).finally(() => {
                showConfirmationDelete.value = false;
            });
        };

        function detailTask() {
            router.push({ name: 'DetailTask', params: {id : props.id}});
        };

        return {
            showConfirmationMarkCompleted,
            showConfirmationMarkArchived,
            showConfirmationDelete,
            showConfirmationMarkIncomplete,
            showConfirmationMarkUnarchived,
            isSuccessUpdateStatus,
            loading,
            messageErrorUpdateStatus,
            messageSuccessMarkTask,
            markTask,
            removeTask,
            detailTask,
        }
    }
})
</script>