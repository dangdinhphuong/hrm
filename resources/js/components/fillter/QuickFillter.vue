<template>
    <!-- Dropdown with Menu -->
    <a-space :size="size" class="ms-3 mt-3 pr-0 pl-0">
        <a-dropdown>
            <template #overlay>
                <a-menu>
                    <a-menu-item v-for="(item, index) in statusData" :key="item.key"
                                 @click="handleSelectClick( item.key )">
                        {{ item.name }}
                    </a-menu-item>
                </a-menu>
            </template>
            <a-button :size="size" type="primary" class="col-12">
                {{ translate('order.button.all') }}
                <DownOutlined/>
            </a-button>
        </a-dropdown>
    </a-space>

    <!-- Buttons -->
    <a-button
        v-for="(item, index) in statusDataButtom"
        :key="item.key"
        :size="size"
        :class="{ 'active': index == activeButton }"
        @click="handleButtonClick(index)"
        class="ms-3 mt-3 first-button"
    >
        {{ item.name }}
    </a-button>
</template>

<script setup>
import {DownOutlined} from '@ant-design/icons-vue';
import {ref} from 'vue';
import {defineProps, defineEmits} from "vue";

const emit = defineEmits(['update:modelValue']);
import OrderStatusConstant from "@/constants/OrderStatusConstant.js";
import {translate} from "@/helpers/CommonHelper.js";

const props = defineProps({
    data: Object,
    params: Object,
    size: {
        type: String,
        default: 'large',
    },
    modelValue: {
        type: [String, Number, Array, Object],
        default: null
    },
    submitAdvancedSearch: {
        type: Function,
        required: true
    }
});
const statusData = ref([]);
const statusDataButtom = ref([]);
const activeButton = ref(0);

const formatStatusOrder = async () => {
    let array = props.data;
    Object.entries(array).map(([item, value]) => {
        const statusKey = OrderStatusConstant.ORDER_STATUS.STATUS[value.id] ?? '';
        const updatedStatusItem = {
            key: value.id,
            name: statusKey + '(' + (value.total) + ')' ?? '',
        }
        if (item < 3) {
            statusDataButtom.value.push(updatedStatusItem);
        }
        statusData.value.push(updatedStatusItem);
    });
    statusData.value.unshift({
        key: '',
        name: 'All',
    });
};

const getActiveButton = () => {
    if (props.params.order_status) {
        activeButton.value = props.params.order_status;
    } else {
        activeButton.value = 0;
    }
};

const handleButtonClick = (index) => {
    emit('update:modelValue', index.toString());
    props.submitAdvancedSearch()
};

const handleSelectClick = (index) => {
    if (index < 1) {
        index = '';
    } else {
        index -= 1;
    }
    emit('update:modelValue', index.toString());
    props.submitAdvancedSearch();
};

formatStatusOrder();
getActiveButton();
</script>

<style lang="scss" scoped>
:deep(.ant-space-item) {
    width: 100%;
}

@media (max-width: 575px) {
    :deep(.first-button) {
        display: none !important;
    }
}

:deep(.first-button) {
    background: var(--color-light);
    border-color: var(--color-light);
    color: var(--color-medium);
}

.active {
    background-color: var(--button-add-color) !important;
    border-color: var(--button-add-color) !important;
    color: var(--color-light) !important;
}
</style>
