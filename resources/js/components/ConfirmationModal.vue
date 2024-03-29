<script setup>
import {
    InformationCircleIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    XCircleIcon,
} from "@heroicons/vue/20/solid/index.js";
import { computed } from "vue";
import { cva } from "class-variance-authority";

const props = defineProps({
    intent: {
        type: String,
        validator(value) {
            return ["info", "success", "danger", "warning"].includes(value);
        },
        default: "info",
    },
    title: String,
    show: {
        type: Boolean,
        default: true,
    },
    onDismiss: Function,
    onOk: Function,
});

const containerClass = computed(() => {
    return cva("relative z-10", {
        variants: {
            intent: {
                info: "bg-blue-100",
                success: "bg-green-100",
                warning: "bg-orange-100",
                danger: "bg-red-100",
            },
        },
    })({
        intent: props.intent,
    });
});

const iconClass = computed(() => {
    return cva("w-6 h-6", {
        variants: {
            intent: {
                info: "text-blue-700",
                success: "text-green-600",
                warning: "text-orange-400",
                danger: "text-red-500",
            },
        },
    })({
        intent: props.intent,
    });
});

const iconComponent = computed(() => {
    const icons = {
        success: CheckCircleIcon,
        warning: ExclamationTriangleIcon,
        danger: XCircleIcon,
        info: InformationCircleIcon,
    };

    return icons[props.intent];
});

function dismiss() {
    if (props.onDismiss) {
        props.onDismiss();
    }
}
</script>


<template>
    <div v-if="props.show" :class="containerClass" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!--
          Background backdrop, show/hide based on modal state.
      
          Entering: "ease-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!--
              Modal panel, show/hide based on modal state.
      
              Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <component :is="iconComponent" :class="iconClass" />
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    {{ props.title }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        This actions can not be revert
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="onOk"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Yes</button>
                        <button type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                            @click="dismiss()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>