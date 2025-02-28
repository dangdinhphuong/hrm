import {$t} from "@assets/js/lang/index.js";
import moment from "moment";

export function isEmptyObject(object) {
    return !object || Object.keys(object).length === 0;
}

export function isObject(value) {
    return (
        typeof value === 'object' &&
        value !== null &&
        !Array.isArray(value)
    );
}

export function isArray(value) {
    return Array.isArray(value);
}

export function buildApiUrl(path) {
    return `/api/${path}`;
}

export function buildApiPathWithParams(path, params) {
    for (const [key, value] of Object.entries(params)) {
        let param = `{${key}}`;
        path = path.replaceAll(param, value);
    }
    return path;
}

export function translate(key) {
    return $t(key);
}

export function isset(value) {
    return typeof value !== 'undefined';
}

export function getEnv(key) {
    return import.meta.env[`VITE_${key}`] ?? null;
}

export function cloneObject(object, type = 'object') {
    let result = {};
    if (type === 'array') {
        result = [];
    }
    return Object.assign(result, object);
}

export function convertConstantToDataSelect(data) {
    let result = [];
    result.push({label: '', value: ''})
    data.forEach((value, key) => {
        result.push({
            label: value,
            value: key
        })
    });
    return result;
}

export function convertConstantObjectToDataSelect(data, valueType = "Number") {
    let result = [];

    Object.entries(data).forEach(([key, value]) => {
        let convertedKey;
        switch (valueType) {
            case "Number":
                convertedKey = Number(key);
                break;
            case "String":
                convertedKey = String(key);
                break;
            case "Boolean":
                convertedKey = Boolean(key);
                break;
            // Add more types as needed
            default:
                convertedKey = key; // Default to original key if type is not recognized
        }

        result.push({
            label: value,
            value: convertedKey
        });
    });

    return result;
}


export function createStyleElement(styleAdded, classQuery) {
    // Thêm style tùy chỉnh
    if (styleAdded) {
        const selectProvinceDistrictElement = document.querySelector(classQuery);
        if (selectProvinceDistrictElement) {
            const style = document.createElement('style');
            style.type = 'text/css';
            style.innerHTML = styleAdded;
            selectProvinceDistrictElement.appendChild(style);
        }
    }
}

export function formatToVietnameseCurrency(money) {
    const number = parseFloat(money);

    // Check if the conversion was successful
    if (isNaN(number)) {
        throw new Error('Invalid number format');
    }
    const formattedNumber = number.toLocaleString('vi-VN');

    return formattedNumber + ' đ';
}

export function useDaysInMonth(month = moment().month() + 1, year = moment().year()) {
    const daysInMonth = moment(`${year}-${month}`, "YYYY-MM").daysInMonth();

    const weekdays = [
        $t("weekdays.Sunday"),
        $t("weekdays.Monday"),
        $t("weekdays.Tuesday"),
        $t("weekdays.Wednesday"),
        $t("weekdays.Thursday"),
        $t("weekdays.Friday"),
        $t("weekdays.Saturday")
    ];

    const result = [];
    for (let day = 1; day <= daysInMonth; day++) {
        const date = moment(`${year}-${month}-${day}`, "YYYY-MM-DD");
        result.push({
            dayOfWeek: weekdays[date.day()],
            date: date.format("DD/MM") // Định dạng lại ngày thành 2 chữ số
        });
    }

    return result;
}

