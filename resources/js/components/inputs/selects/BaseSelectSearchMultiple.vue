<template>
    <a-select class="ant-select-lg"
              v-model:value="state.value"
              mode="multiple"
              label-in-value
              placeholder="search..."
              style="width: 100%"
              :filter-option="false"
              :max-tag-count="maxTagCount"
              :not-found-content="state.fetching ? undefined : null"
              :options="state.data"
              :field-names="fieldName"
              :disabled="disabled"
              @search="afterFetchData"
              @change="$emit('update:value', handleSelectChange())"
    >
        <template v-if="state.fetching" #notFoundContent>
            <loading :is-full-page="true"/>
        </template>
    </a-select>
</template>

<script setup>
import {defineProps, reactive, ref} from "vue";
import {debounce} from 'lodash-es';
import {useLoading} from "@/composables/loading.js";
import Loading from 'vue-loading-overlay';

const props = defineProps({
    size: String,
    value: [String, Number, Array, Object],
    multiple: Boolean,
    fetchOptions: {type: Function, required: true},
    fieldName: {
        type: Object,
        default: {
            label: 'name',
            value: 'id'
        }
    },
    maxTagCount: Number,
    disabled: { default: false },
});

let lastFetchId = 0;
const options = ref([]);
const state = reactive({
    data: [],
    value: [],
    fetching: false,
});
const params = ref({
    limit: 10,
    page: 1,
    keyword: '',
    paginate: false,
    id: props.value ?? []
});

const {setLoading, setLoadingComplete} = useLoading();

// Hàm fetch data được cập nhật để giữ lại các option đã chọn
const fetchData = async (value) => {
    setLoading(true);
    params.value.keyword = value;
    state.fetching = true;

    // Nếu có từ khóa hoặc giá trị đã chọn thì tiếp tục fetch
    if (params.value.keyword || params.value.id.length) {
        try {
            const data = await props.fetchOptions(params.value);

            // Tạo một bản sao các giá trị đã chọn
            const selectedOptions = state.value.map(selectedItem => {
                return state.data.find(item => item.id === selectedItem.value);
            }).filter(Boolean); // Lọc bỏ các giá trị null (nếu không tìm thấy)

            // Gộp dữ liệu mới (không trùng lặp) với các option đã chọn
            state.data = removeDuplicates([...selectedOptions, ...data]);
        } finally {
            state.fetching = false;
            setLoadingComplete();
        }
    }
};

const afterFetchData = debounce(fetchData, 1000);

const getValue = async () => {
    if (params.value.id.length) {
        await fetchData('');
        state.value = params.value.id.map(id => ({
            value: id,
            label: state.data.find(item => item.id === id)?.[props.fieldName.label] || id,
        }));
    }
}

const removeDuplicates = (array) => {
    const seenIds = new Set();
    return array.filter(item => {
        if (seenIds.has(item.id)) {
            return false;
        } else {
            seenIds.add(item.id);
            return true;
        }
    });
};

const handleSelectChange = () => state.value.map(item => item.value);

getValue();
</script>
