<template>
    <div class="flex justify-end">
        <ul class="pagination bg-white p-2 shadow-sm rounded">
            <li class="pagination-item">
                <span
                    class="rounded-l rounded-sm border border-gray-100 px-3 py-2 cursor-not-allowed no-underline text-gray-600 h-10"
                    v-if="isInFirstPage">&laquo;</span>
                <a v-else @click.prevent="onClickFirstPage"
                    class="rounded-l rounded-sm border-t border-b border-l border-gray-100 px-3 py-2 text-gray-600 hover:bg-gray-100 no-underline"
                    href="#" role="button" rel="prev">
                    &laquo;
                </a>
            </li>

            <li class="pagination-item">
                <button type="button" @click="onClickPreviousPage" :disabled="isInFirstPage"
                    aria-label="Go to previous page"
                    class="rounded-sm border border-gray-100 px-3 py-2 hover:bg-gray-100 text-gray-600 no-underline mx-2 text-sm"
                    :class="{ 'cursor-not-allowed': isInFirstPage }">Previous</button>
            </li>

            <li v-for="page in pages" class="pagination-item mb-5" :key="page.name">
                <span
                    class="rounded-sm border border-blue-100 px-3 py-2 bg-blue-100 no-underline text-blue-500 cursor-not-allowed mx-2"
                    v-if="isPageActive(page.name)">{{ page.name }}</span>
                <a class="rounded-sm border border-gray-100 px-3 py-2 hover:bg-gray-100 text-gray-600 no-underline mx-2"
                    href="#" v-else @click.prevent="onClickPage(page.name)" role="button">{{ page.name }}</a>
            </li>

            <li class="pagination-item">
                <button type="button" @click="onClickNextPage" :disabled="isInLastPage" aria-label="Go to next page"
                    class="rounded-sm border border-gray-100 px-3 py-2 hover:bg-gray-100 text-gray-600 no-underline mx-2 text-sm"
                    :class="{ 'cursor-not-allowed': isInLastPage }">Next</button>
            </li>

            <li class="pagination-item">
                <a class="rounded-r rounded-sm border border-gray-100 px-3 py-2 hover:bg-gray-100 text-gray-600 no-underline"
                    href="#" @click.prevent="onClickLastPage" rel="next" role="button" v-if="hasMorePages">&raquo;</a>
                <span
                    class="rounded-r rounded-sm border border-gray-100 px-3 py-2 hover:bg-gray-100 text-gray-600 no-underline cursor-not-allowed"
                    v-else>&raquo;</span>
            </li>
        </ul>
    </div>
</template>
  
<script>
import { computed } from "vue";

export default {
    props: {
        maxVisibleButtons: {
            type: Number,
            required: false,
            default: 3
        },

        totalPages: {
            type: Number,
            required: true
        },

        total: {
            type: Number,
            required: true
        },

        perPage: {
            type: Number,
            required: true
        },

        currentPage: {
            type: Number,
            required: true
        },

        hasMorePages: {
            type: Boolean,
            required: true
        }
    },
    setup(props, { emit }) {

        const startPage = computed(() => {
            if (props.currentPage === 1) {
                return 1;
            }

            if (props.currentPage === props.totalPages) {
                return props.totalPages - props.maxVisibleButtons + 1;
            }
            
            return props.currentPage - 1;
        });

        const endPage = computed(() => {
            return Math.min(
                startPage.value + props.maxVisibleButtons - 1,
                props.totalPages
            );
        });
        const pages = computed(() => {
            const range = [];
            let startingPage;
            if (startPage.value < 1) {
                startingPage = 1;
            } else {
                startingPage = startPage.value
            }
            for (let i = startingPage; i <= endPage.value; i += 1) {
                range.push({
                    name: i,
                    isDisabled: i === props.currentPage
                });
            }
            return range;
        });

        const isInFirstPage = computed(() => {
            return props.currentPage === 1;
        });

        const isInLastPage = computed(() => {
            return props.currentPage === props.totalPages;
        });

        function onClickFirstPage() {
            emit("pageChanged", 1);
        };
        function onClickPreviousPage() {
            emit("pageChanged", props.currentPage - 1);
        };
        function onClickPage(page) {
            emit("pageChanged", page);
        };
        function onClickNextPage() {
            emit("pageChanged", props.currentPage + 1);
        };
        function onClickLastPage() {
            emit("pageChanged", props.totalPages);
        };
        function isPageActive(page) {
            return props.currentPage === page;
        };

        return {
            startPage,
            endPage,
            pages,
            isInFirstPage,
            isInLastPage,
            props,
            onClickFirstPage,
            onClickPreviousPage,
            onClickPage,
            onClickNextPage,
            onClickLastPage,
            isPageActive,
        };
    },
};
</script>
<style scoped>
.pagination {
    list-style-type: none;
  }
  
  .pagination-item {
    display: inline-block;
  }
  
  
  
</style>