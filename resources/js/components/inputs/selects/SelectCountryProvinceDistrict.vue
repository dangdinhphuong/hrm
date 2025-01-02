<template>
    <div class="d-flex select-Country-province-district">
        <div class="flex-item Country ">
            <a-form-item
                :validate-status="errors?.[inputKey.country_id] ? 'error' : ''"
                :help="errors?.[inputKey.country_id] ?? ''"
            >
                <span>{{ $t('country.title') }}<span class="text-danger">*</span></span>
                <a-select
                    :value="countryId"
                    show-search
                    :size="size"
                    :disabled="disabled"
                    :field-names="fieldName"
                    :options="optionCountry"
                    @change="onCountryChange"
                    style="width: 100%"
                    :filter-option="(input, option) => option[fieldName.label].toLowerCase().includes(input.toLowerCase())"
                />
            </a-form-item>
        </div>
        <div class="flex-item province ">
            <a-form-item
                :validate-status="errors?.[inputKey.province_id] ? 'error' : ''"
                :help="errors?.[inputKey.province_id] ?? ''"
            >
                <span>{{ $t('delivery_note.columns.province') }}<span class="text-danger">*</span></span>
                <a-select
                    :value="provinceId"
                    :size="size"
                    show-search
                    :disabled="disabled"
                    :options="optionProvince"
                    :field-names="fieldName"
                    @change="onProvinceChange"
                    :filter-option="(input, option) => option[fieldName.label].toLowerCase().includes(input.toLowerCase())"
                    style="width: 100%"
                />
            </a-form-item>
        </div>
        <div class="flex-item district ">
            <a-form-item
                :validate-status="errors?.[inputKey.district_id] ? 'error' : ''"
                :help="errors?.[inputKey.district_id] ?? ''"
            >
                <span>{{ $t('delivery_note.columns.district') }}<span class="text-danger">*</span></span>
                <a-select
                    :value="districtId"
                    :size="size"
                    show-search
                    :disabled="disabled"
                    :options="optionDistrict"
                    :field-names="fieldName"
                    :filter-option="(input, option) => option[fieldName.label].toLowerCase().includes(input.toLowerCase())"
                    @change="onDistrictChange"
                    style="width: 100%"
                />
            </a-form-item>
        </div>
    </div>
</template>

<script setup>
import {defineProps, ref, onMounted} from "vue";
import {useLoading} from "@/composables/loading.js";
import {createStyleElement, convertConstantToDataSelect} from "@/helpers/CommonHelper.js";
import CountriesService from "@/services/Address/CountriesService.js";

const {setLoading, setLoadingComplete} = useLoading();
const props = defineProps({
    size: {type: String, default: 'default'},
    fieldName: {type: Object, default: () => ({label: 'name', value: 'id'})},
    errors: {type: [Array, Object], default: () => ({})},
    formData: {type: Object, default: () => ({})},
    inputKey: {type: [Array, Object], default: () => ({})},
    styleAdded: {type: String, default: ''},
    disabled: {default: false},
});

const locations = ref(null);
const optionCountry = ref([]);
const optionProvince = ref([]);
const optionDistrict = ref([]);
const countryId = ref('');
const provinceId = ref('');
const districtId = ref('');

const countryDefault = "";

const fetchLocations = async () => {
    setLoading(true);
    try {
        locations.value = await new CountriesService().getList();
    } catch (error) {
    } finally {
        setLoadingComplete();
    }
    if (countryId.value) {
        fetchProvinceOptions(countryId.value)
    }
};

const fetchCountryOptions = () => {
    optionCountry.value = locations.value;
};

const fetchProvinceOptions = (countryId) => {
    setLoading(true);
    try {
        const selectedCountry = locations.value?.find(country => country.id === countryId);
        optionProvince.value = selectedCountry?.provinces || [];

        if (provinceId.value) {
            fetchDistrictOptions(provinceId.value);
        }
    } catch (error) {
        console.error('Error fetching provinces:', error);
    } finally {
        setLoadingComplete();
        createStyleElement(props.styleAdded, '.select-Country-province-district');
    }
};

const fetchDistrictOptions = async (provinceId) => {
    setLoading(true);
    try {
        const selectedProvince = optionProvince.value?.find(province => province.id === provinceId);
        optionDistrict.value = selectedProvince?.districts || [];
    } catch (error) {
        console.error('Error fetching districts:', error);
    } finally {
        setLoadingComplete();
    }
};

const onCountryChange = (value) => {
    countryId.value = value;
    provinceId.value = null;
    districtId.value = null;
    optionDistrict.value = [];
    props.formData[props.inputKey.country_id] = value;
    props.formData[props.inputKey.province_id] = null;
    props.formData[props.inputKey.district_id] = null;
    fetchProvinceOptions(value);
};

const onProvinceChange = (value) => {
    provinceId.value = value;
    districtId.value = null;
    props.formData[props.inputKey.province_id] = value;
    props.formData[props.inputKey.district_id] = null;
    fetchDistrictOptions(value);
};

const onDistrictChange = (value) => {
    districtId.value = value;
    props.formData[props.inputKey.district_id] = value;
};

const getValueProvinceAndDistrict = async () => {

    countryId.value = props.formData[props.inputKey.country_id] ?? countryDefault;
    provinceId.value = props.formData[props.inputKey.province_id] ?? '';
    districtId.value = props.formData[props.inputKey.district_id] ?? '';
};

onMounted(async () => {
    await getValueProvinceAndDistrict();
    await fetchLocations();
    fetchCountryOptions();
});
</script>

<style scoped>
.select-Country-province-district {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.select-Country-province-district .flex-item {
    flex: 1;
    padding: 0 10px; /* Thêm margin để tạo khoảng cách đều */
    min-width: 200px; /* Đảm bảo các khối có kích thước tối thiểu */
}

.Country {
    padding-left: 0px !important; /* Đảm bảo khối đầu tiên không có khoảng trống bên trái */
}

.district {
    padding-right: 0px!important;  /* Đảm bảo khối cuối cùng không có khoảng trống bên phải */
}
:deep(.ant-form-item){
    margin-bottom: 0px !important;
}

@media (max-width: 768px) {
    .select-Country-province-district {
        flex-direction: column;
    }
    .select-Country-province-district .flex-item {
        width: 100%; /* Chiếm hết chiều rộng màn hình trên thiết bị nhỏ */
        margin: 10px 0; /* Thêm khoảng cách dọc giữa các khối */
    }
}

</style>
