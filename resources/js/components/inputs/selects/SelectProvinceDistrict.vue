<template>
    <div class="d-flex select-province-district">
        <div class="province">
            <a-form-item
                :validate-status="errors && errors[inputKey.province_id] ? 'error' : ''"
                :help="errors.province_id ?? ''"
            >
                <span>{{ $t('delivery_note.columns.province') }}<span class="text-danger">*</span></span>
                <a-select
                    :value="provinceId"
                    :size="size"
                    :options="optionProvince"
                    :field-names="fieldName"
                    @change="onProvinceChange"
                    style="width: 100%"
                />
            </a-form-item>
        </div>
        <div class="district">
            <a-form-item
                :validate-status="errors && errors[inputKey.district_id] ? 'error' : ''"
                :help="errors.district_id ?? ''"
            >
                <span>{{ $t('delivery_note.columns.district') }}<span class="text-danger">*</span></span>
                <a-select
                    :value="districtId"
                    :size="size"
                    :options="optionDistrict"
                    :field-names="fieldName"
                    @change="onDistrictChange"
                    style="width: 100%"
                />
            </a-form-item>
        </div>
    </div>
</template>

<script setup>
import ProvinceService from "@/services/Address/ProvinceService.js";
import {defineProps, ref, watch, onMounted} from "vue";
import {useLoading} from "@/composables/loading.js";
import {defineEmits} from "vue";
import {translate, createStyleElement } from "@/helpers/CommonHelper.js";
import {number} from "yup";

const {setLoading, setLoadingComplete} = useLoading();
const props = defineProps({
    size: {
        type: String,
        default: 'default'
    },
    fieldName: {
        type: Object,
        default: () => ({label: 'name', value: 'id'})
    },
    errors: {
        type: [Array, Object],
        default: () => ({})
    },
    formData: {
        type: Object,
        default: {}
    },
    inputKey: {
        type: [Array, Object],
        default: {}
    },
    styleAdded: {
        type: String,
        default: ''
    }
});
const optionProvince = ref([]);
const optionDistrict = ref([]);
const provinceId = ref('');
const districtId = ref('');

const getValueProvinceAndDistrict = () => {
    provinceId.value = props.formData[props.inputKey.province_id] ?? '';
    districtId.value = props.formData[props.inputKey.district_id] ??'';
};

const fetchProvinceOptions = async () => {
    setLoading(true);
    try {
        const data = await new ProvinceService().getList();
        optionProvince.value = data;
        setLoadingComplete();
        if (provinceId.value) {
            fetchDistrictOptions(provinceId.value);
        }
    } catch (error) {
        setLoadingComplete();
    }
    createStyleElement ( props.styleAdded, '.select-province-district');
};

const fetchDistrictOptions = async (provinceId) => {
    setLoading(true);
    try {
        const selectedProvince = optionProvince.value.find(province => province.id === provinceId);
        optionDistrict.value = selectedProvince
            ? selectedProvince.district.map(({prefix, ...rest}) => rest)
            : [];
        setLoadingComplete();
    } catch (error) {
        setLoadingComplete();
    }
};

const onProvinceChange = (value) => {
    props.formData[props.inputKey.province_id] = provinceId.value = value;
    props.formData[props.inputKey.district_id] = districtId.value = null;
    fetchDistrictOptions(value);
};

const onDistrictChange = (value) => {
    props.formData[props.inputKey.district_id] = districtId.value = value;
};

getValueProvinceAndDistrict();
fetchProvinceOptions();
</script>

<style scoped>
.province, .district {
    width: 50%;
}

.district {
    padding-left: .5rem;
}

.province {
    padding-right: .5rem;
}
</style>
