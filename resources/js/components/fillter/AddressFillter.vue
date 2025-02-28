<template>
    <div class="mb-2 col-12 col-sm-3" style="margin: 4%;">
        <div>{{ $t('order.region') }}</div>
        <a-select style="width: 100%" :size="size" @change="regionSelectChange" v-model:value="regionValue">
            <a-select-option v-for="item in regionData" :value="item.id">{{ item.name }}</a-select-option>
        </a-select>
    </div>
    <div class="mb-2 col-12 col-sm-3" style="margin: 4%;">
        <div>{{ $t('order.province') }}</div>
        <a-select style="width: 100%" :size="size" @change="provinceSelectChange" v-model:value="provinceValue">
            <a-select-option v-for="item in provinceData" :value="item.id">{{ item.name }}</a-select-option>
        </a-select>
    </div>
    <div class="mb-2 col-12 col-sm-3" style="margin: 4%;">
        <div>{{ $t('order.district') }}</div>
        <a-select style="width: 100%" :size="size" @change="districtSelectChange" v-model:value="districtValue">
            <a-select-option v-for="item in districtData" :value="item.id">{{ item.name }}</a-select-option>
        </a-select>
    </div>
</template>

<script setup>
import {defineProps, defineEmits, ref} from "vue";
import ProvinceService from "@/services/Address/ProvinceService.js";
import OrderStatusConstant from "@/constants/OrderStatusConstant.js";
import {translate} from "@/helpers/CommonHelper.js";

const addressData = ref({});
const regionData = ref([]);
const provinceData = ref([]);
const districtData = ref([]);
const provinceValue = ref(null);
const districtValue = ref(null);
const regionValue = ref(null);

const props = defineProps({
    size: {
        type: String,
        default: 'large'
    },
    params: Object,
    submitAddressFilter: {
        type: Function,
        required: true
    }
});
const provinceService = new ProvinceService();

// data
const fetchProvince = (params) => {
    return provinceService.getList(params);
}

const setDataRegion = () => {

    regionData.value = [
        {
            id: '',
            name: 'All',
        },
        ...Object.entries(OrderStatusConstant.ORDER_STATUS.REGIONS).map(([key, value]) => ({
            id: key,
            name: value,
        })),
    ];
};

const setDataProvince = (region = '') => {
    let array = addressData.value;
    provinceData.value = [];
    districtData.value = [];

    array.map(item => {
        if (region) {
            if (region && region == item.region) {
                provinceData.value.push({
                    id: item.id,
                    name: item.name
                });
            }
        } else {
            provinceData.value.push({
                id: item.id,
                name: item.name
            });
        }
    });
    getPramProvinceAndDistrict();
};

const setAddress = async () => {
    addressData.value = await fetchProvince();
    setDataProvince();
};

const setDataDistrict = (provinceId) => {
    districtData.value = [];
    let array = addressData.value;
    array.map(item => {
        if (item.id == provinceId) {
            item.district.map(value => {
                districtData.value.push({
                    id: value.id,
                    name: value.name
                });
            });
        }
    });
};

const getPramProvinceAndDistrict = () => {
    provinceValue.value = null;
    districtValue.value = null;
    if (props.params.province_id) {
        let provinceId = provinceValue.value = parseInt(props.params.province_id);
        setDataDistrict(provinceId)
    }
    if (props.params.district_id) {
        districtValue.value = parseInt(props.params.district_id)
    }
};

// submit data
const districtSelectChange = (value) => {
    props.params.district_id = value;
    props.submitAddressFilter(props.params);
};

const provinceSelectChange = (value) => {
    setDataDistrict(value)
    districtValue.value = props.params.district_id = null;
    props.params.province_id = value;
    props.submitAddressFilter(props.params);
};

const regionSelectChange = (value) => {
    districtValue.value = props.params.district_id = null;
    provinceValue.value = props.params.province_id = null;
    props.params.region = value;
    props.submitAddressFilter(props.params);
    setDataProvince(value);
};

// call function
setAddress();
setDataRegion();


</script>
