<template>
    <div class="d-flex select-region-province-district">
        <div class="region">
            <a-form-item
                :validate-status="errors && errors[inputKey.region_id] ? 'error' : ''"
                :help="errors.region_id ?? ''"
            >
                <span>{{ $t('order.region') }}<span class="text-danger">*</span></span>
                <a-select
                    :value="regionId"
                    :size="size"
                    :options="optionRegion"
                    @change="onRegionChange"
                    style="width: 100%"
                />
            </a-form-item>
        </div>
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
import { defineProps, ref, onMounted } from "vue";
import { useLoading } from "@/composables/loading.js";
import { createStyleElement, convertConstantToDataSelect } from "@/helpers/CommonHelper.js";
import CommonConstant from "@/constants/CommonConstant.js";

const { setLoading, setLoadingComplete } = useLoading();
const props = defineProps({
    size: {
        type: String,
        default: 'default'
    },
    fieldName: {
        type: Object,
        default: () => ({ label: 'name', value: 'id' })
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
const locations = ref(null);
const optionRegion = ref([]);
const optionProvince = ref([]);
const optionDistrict = ref([]);
const regionId = ref('');
const provinceId = ref('');
const districtId = ref('');

const getValueProvinceAndDistrict = () => {
    regionId.value = props.formData[props.inputKey.region_id] ?? 0;
    provinceId.value = props.formData[props.inputKey.province_id] ?? '';
    districtId.value = props.formData[props.inputKey.district_id] ?? '';
};

const fetchLocations = async () => {
    setLoading(true);
    try {
        locations.value = await new ProvinceService().getList();
    } catch (error) {
        console.error('Error fetching locations:', error);
    } finally {
        setLoadingComplete();
    }
};

const fetchRegionOptions = () => {
    optionRegion.value = [
        { value: 0, label: 'All' },
        ...convertConstantToDataSelect(CommonConstant.REGIONS)
    ];
    fetchProvinceOptions(regionId.value);
};

const fetchProvinceOptions = (regionId) => {
    if (locations.value) {
        setLoading(true);
        try {
            const data = locations.value;

            if (regionId != 0) {
                 optionProvince.value = data.filter(item => item.region === regionId);
            }else{
                optionProvince.value = data;
            }

            if (provinceId.value) {
                fetchDistrictOptions(provinceId.value);
            }
        } catch (error) {
            console.error('Error fetching provinces:', error);
        } finally {
            setLoadingComplete();
            createStyleElement(props.styleAdded, '.select-region-province-district');
        }
    }
};

const fetchDistrictOptions = async (provinceId) => {
    // Simulating an API call or data fetching logic
    setLoading(true);
    try {
        const selectedProvince = optionProvince.value.find(province => province.id === provinceId);
        optionDistrict.value = selectedProvince
            ? selectedProvince.district.map(({ prefix, ...rest }) => rest)
            : [];
    } catch (error) {
        console.error('Error fetching districts:', error);
    } finally {
        setLoadingComplete();
    }
};

const onRegionChange = (value) => {
    props.formData[props.inputKey.region_id] = regionId.value = value;
    props.formData[props.inputKey.province_id] = provinceId.value = null;
    props.formData[props.inputKey.district_id] = districtId.value = null;
    fetchProvinceOptions(value);
};

const onProvinceChange = (value) => {
    props.formData[props.inputKey.province_id] = provinceId.value = value;
    props.formData[props.inputKey.district_id] = districtId.value = null;
    fetchDistrictOptions(value);
};

const onDistrictChange = (value) => {
    props.formData[props.inputKey.district_id] = districtId.value = value;
};

onMounted(async () => {
    await fetchLocations();
    getValueProvinceAndDistrict();
    fetchRegionOptions();
});
</script>

<style scoped>
.province, .district, .region {
    width: 25%;
}

.province {
    width: 26%;
    margin-left: 8%;
}

.district {
    width: 26%;
    margin-left: 6%;
}
</style>
