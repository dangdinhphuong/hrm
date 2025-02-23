<template>
    <div class="d-flex justify-content-between" style="border-bottom: 2px solid #CFCDCD; margin-bottom: 2%;">
        <h2><b>{{ pageTitle }}</b></h2>
    </div>
    <div class="page-search">
        <div class="d-sm-flex justify-content-end row row-cols-sm-auto m-0 mb-sm-3">

            <a-space direction="vertical" style="padding:0px; margin-right: 16px;" v-if="canSearch">
                <a-input-search
                    v-model:value="searchData.keyword"
                    enter-button
                    @search="onSearch"
                    :size='sizeButton'
                    :allowClear="true"
                    :placeholder="$t('common.placeholder.quick_search')"
                />
            </a-space>

            <a-dropdown :trigger="['click']" v-if="advancedSearchInput.length > 0"
                        v-model:open="visibleAdvancedSearch" @openChange="handleVisibleChangeAdvancedSearch">
                <template #overlay @click.prevent>
                    <div class="wrapper-advanced-search"
                         style="padding:20px;width:100vh;background-color:#fff; box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25); border-radius: 2px;">
                        <div class="advanced-search-input d-flex flex-wrap">
                            <template v-for="(input, index) in advancedSearchInput" :key="index">
                                <div class="mb-2 col-12 col-sm-3" :class="[input.classAdvancedFormItem ?? '']"
                                     style="margin: 4%;">
                                    <div>{{ input.name }}</div>
                                    <app-input
                                        v-model="searchData[input.key]"
                                        :input-key="input.key"
                                        :type="input.type"
                                        :options="input.options ?? []"
                                        :entity="input.entity ?? null"
                                        :multiple="input.multiple ?? false"
                                        :style-added="input.styleAdded ?? ''"
                                        :form-data="searchData ?? {}"
                                    >
                                    </app-input>
                                </div>
                            </template>
                        </div>
                        <div class="advanced-search-button" style="margin-top:100px">
                            <div class="d-flex justify-content-center">
                                <button-search @click="submitAdvancedSearch" class="m-1"></button-search>
                                <button-reset-search @click="resetAdvancedSearch" class="m-1"></button-reset-search>
                            </div>
                        </div>
                    </div>
                </template>
                <a-button type="primary" :size='sizeButton'
                          style="background: var(--button-submit-color);border-color: var(--button-submit-color)">
                    {{ $t('common.buttons.advanced') }}
                </a-button>
            </a-dropdown>
        </div>
    </div>
    <div class="page-button d-flex flex-wrap" v-if="canAction">
        <div class="d-sm-flex justify-content-end row row-cols-sm-auto m-0 mb-sm-3 col-12 col-sm-12">
            <button-dropdown
                v-if="getButtonDropdownData('operationAction')"
                :data="getButtonDropdownData('operationAction')"
                :size="sizeButton">
            </button-dropdown>
            <button-dropdown
                v-if="getButtonDropdownData('uploadAction')"
                :data="getButtonDropdownData('uploadAction')"
                :size="sizeButton">
            </button-dropdown>
            <button-upload @click="actionUpload" v-if="actionUpload" :size="sizeButton"></button-upload>
            <button-download @click="actionDownload" v-if="actionDownload" :size="sizeButton"></button-download>
            <button-add @click="actionAdd" v-if="actionAdd" :size="sizeButton"></button-add>
            <button-refresh @click="actionRefresh"></button-refresh>
        </div>
    </div>
    <div class="page-content">
        <a-card class="mt-3">
            <a-table :columns="columns" :data-source="data.data ?? []"
                     :row-selection="tableRowSelectionActions ? rowSelectionConfig : null"
                     :pagination="false" :scroll="scrollTable ? scrollTable : null"
                     :expand-column-width="5">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'action'">
                        <template v-if="actionEdit">
                            <a-button shape="circle" class="me-1"
                                      @click="clickActionEdit(record)">
                                <template #icon>
                                    <img src="@assets/images/icon/edit.svg" alt="" style="width:18px">
                                </template>
                            </a-button>
                        </template>
                        <template v-if="actionDetail">
                            <a-button shape="circle" class="me-1"
                                      @click="clickActionDetail(record)">
                                <template #icon>
                                    <img src="@assets/images/icon/eye.svg" alt="" style="width:18px">
                                </template>
                            </a-button>
                        </template>
                        <template v-if="getActionOther">
                            <a-dropdown :trigger="['click']">
                                <template #overlay @click.prevent>
                                    <a-menu>
                                        <a-menu-item v-for="(action,index) in getActionOther(record)" :key="index"
                                                     @click="tableClickActionOther(record, action.event)">
                                            {{ action.title }}
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-button shape="circle">
                                    <img src="@assets/images/icon/otheraction.svg" alt="">
                                </a-button>
                            </a-dropdown>
                        </template>
                    </template>
                </template>
                <template v-if="innerColumns" #expandedRowRender>
                    <a-table :columns="innerColumns" :data-source="[]" :pagination="false">
                    </a-table>
                </template>
            </a-table>
            <div class="mt-4 d-flex flex-row-reverse me-2 mb-2">
                <a-pagination
                    v-model:current="data.current_page"
                    v-model:page-size="data.per_page"
                    :total="data.total"
                    :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} items`"
                    @change="onChangePagination"
                />
            </div>
        </a-card>
    </div>
</template>


<script setup>
import {ref, watch, defineProps, computed, unref} from "vue";
import ButtonAdd from "@/components/buttons/ButtonAdd.vue";
import ButtonUpload from "@/components/buttons/ButtonUpload.vue";
import ButtonDownload from "@/components/buttons/ButtonDownload.vue";
import ButtonSearch from "@/components/buttons/ButtonSearch.vue";
import ButtonResetSearch from "@/components/buttons/ButtonResetSearch.vue";
import ButtonDropdown from "@/components/buttons/ButtonDropdown.vue";
import ButtonRefresh from "@/components/buttons/ButtonRefresh.vue";
import AppInput from "@/components/inputs/AppInput.vue";
import {isEmptyObject, translate, cloneObject} from "@/helpers/CommonHelper.js";
import router from "@/router/index.js";

const props = defineProps({
    sizeButton: {
        type: String,
        default: 'large'
    },
    scrollTable: {
        type: Object,
        default: {}
    },
    pageTitle: {
        type: String,
        required: true
    },
    columns: {
        type: Array,
        default: []
    },
    innerColumns: {
        type: Array,
        default: null
    },
    innerData: {
        type: Function,
        default: null
    },
    advancedSearchInput: {
        type: Array,
        default: []
    },
    fetchData: {
        type: Function,
        required: true
    },
    triggerFetchData: {
        type: null,
        default: null
    },
    actionAdd: {
        type: Function,
        default: null
    },
    actionEdit: {
        type: Function,
        default: null
    },
    actionDetail: {
        type: Function,
        default: null
    },
    actionDownload: {
        type: Function,
        default: null
    },
    actionUpload: {
        type: Function,
        default: null
    },
    getActionOther: {
        type: Function,
        default: null
    },
    clickActionOther: {
        type: Function,
        default: null
    },
    buttonDropdownData: {
        type: Array,
        default: []
    },
    canSearch: {
        type: Boolean,
        default: true
    },
    canAction: {
        type: Boolean,
        default: true
    },
    tableRowSelectionActions: {
        type: Function,
        default: null
    },
    tableRowSelected: {
        type: Array,
        default: []
    },
});

const columns = cloneObject(props.columns, 'array');

const state = ref([]);

const hasSelected = computed(() => state.value.length > 0);

const selectedRowKeys = ref([]); // Check here to configure the default column

const onSelectChange = changableRowKeys => {
    props.tableRowSelected.value = selectedRowKeys.value = changableRowKeys;
};

const rowSelectionConfig = computed(() => {
    return {
        selectedRowKeys: unref(selectedRowKeys),
        onChange: onSelectChange,
        hideDefaultSelections: false,
        selections: props.tableRowSelectionActions(selectedRowKeys),
    };
});

columns.unshift({
    title: '#',
    width: 5,
    key: 'index',
    fixed: 'left',
    customRender: ({index}) => {
        return ++index;
    },
});

if (props.actionEdit || props.getActionOther || props.actionDetail) {
    columns.push({
        title: translate('common.columns.action'),
        key: 'action',
        fixed: 'right',
        width: 8,
    });
}

const defaultSearchData = {
    page: 1,
    limit: 10
};
const searchData = ref(cloneObject(defaultSearchData));
const visibleAdvancedSearch = ref(false);
const data = ref({});

//Fetch data
const setValueTypeBeforeFetchData = () => {
    props.advancedSearchInput.forEach(field => {

        // Xóa trường nếu bị vô hiệu hóa và là trường nhóm
        // if (typeof field.key === 'object') {
        //     delete searchData.value[field.key];
        // }
        // Chuyển đổi thành mảng nếu là trường đa giá trị và không phải là mảng
        if (field.multiple && !Array.isArray(searchData.value[field.key]) && searchData.value[field.key]) {
            searchData.value[field.key] = [searchData.value[field.key]];
        }

        // Chuyển đổi giá trị thành số nếu có kiểu dữ liệu là 'number'
        if (field.valueType === 'number') {
            if (!Array.isArray(searchData.value[field.key])) {
                if (typeof field.key === 'object') {
                    Object.keys(field.key).forEach((key) => {
                        if (Number(searchData.value[field.key[key]])) {
                            searchData.value[field.key[key]] = Number(searchData.value[field.key[key]]);
                        }
                    });
                } else if (searchData.value[field.key]) {
                    searchData.value[field.key] = Number(searchData.value[field.key]);
                }

            } else {
                // Chuyển đổi từng phần tử trong mảng thành số nếu có thể
                searchData.value[field.key] = searchData.value[field.key].map(element =>
                    !isNaN(Number(element)) ? Number(element) : element
                );
            }
        }

    });
};

const fetchData = async () => {
    router.push({query: searchData.value});
    const dataSource = await props.fetchData(searchData.value);

    if (!dataSource) {
        const defaultSearch = cloneObject(defaultSearchData);
        data.value.current_page = defaultSearch.page;
        data.value.per_page = defaultSearch.limit;
        data.value.total = 0;
    } else {
        data.value = customDataSource(dataSource);
    }
    if(dataSource){
        // props.innerData(data);
        // innerData(dataSource);
    }

    visibleAdvancedSearch.value = false;
}
const innerData = (data) =>{

console.log(props.innerData(data));
    return props.innerData(data)
};

const customDataSource = (dataSource) => {
    dataSource.data = dataSource.data.map(item => ({...item, key: item.id}));
    return dataSource;
}

const firstFetchData = () => {
    const routeQuery = router.currentRoute.value.query;
    if (!isEmptyObject(routeQuery)) {
        searchData.value = routeQuery;
    }
    setValueTypeBeforeFetchData();
    fetchData();
}
firstFetchData();

//Pagination
const onChangePagination = (currentPage, pageSize) => {
    searchData.value.page = currentPage;
    searchData.value.limit = pageSize;
    fetchData();
};

const getButtonDropdownData = (key) => {
    const desiredObject = props.buttonDropdownData.find((item) => item.key === key);
    return desiredObject ?? false;
}

//Search
const submitAddressFilter = (value) => {
    searchData.value = value;
}
const onSearch = () => {
    removeSearchDataAdvanced();
    fetchData();
}

const submitAdvancedSearch = () => {
    delete searchData.value.keyword;
    searchData.value = {...searchData.value, ...cloneObject(defaultSearchData)};
    fetchData();
}
const resetAdvancedSearch = () => {
    removeSearchDataAdvanced();
    fetchData();
}
const removeSearchDataAdvanced = () => {
    const keyword = searchData.value.keyword;
    setDefaultSearchData();
    if (keyword) {
        searchData.value.keyword = keyword;
    }
//    TODO xem lại phần này, reset xong nhưng option vẫn giữ giá trị đã chọn
}
const setDefaultSearchData = () => {
    searchData.value = cloneObject(defaultSearchData);
}
const handleVisibleChangeAdvancedSearch = (visible) => {
    if (visible === false) {
        // TODO set lại advanced search bằng giá trị từ url, tránh trường hợp nhập xong không submit thì ẩn đi vẫn giữ giá trị cũ
    }
}

//action
const tableClickActionOther = (record, event) => {
    props.clickActionOther({
        id: record.id,
        record: record,
        event: event,
        pageParams: searchData.value
    });
}
const clickActionEdit = (record) => {
    props.actionEdit({id: record.id, record: record, pageParams: searchData.value});
}
const clickActionDetail = (record) => {
    props.actionDetail({id: record.id, record: record, pageParams: searchData.value});
}
const actionRefresh = () => {
    fetchData();
}

//watch
watch(
    () => props.triggerFetchData,
    () => {
        fetchData();
    }
)
</script>

<style lang="scss" scoped>
:deep(.ant-pagination li) {
    margin-top: 5px;
}

:deep(.ant-input-search-button) {
    background: var(--button-search-color);
}

:deep(.ant-table-body) {
    min-height: 50vh;
}

:deep(.ant-space-align-center) {
    padding-left: 0px !important;
    padding-right: 0px !important;
}

@media (max-width: 575px) {
    .fillter-left {
        justify-content: flex-end !important;
    }
    .carousel {
        width: 100% !important;
    }
}
</style>
