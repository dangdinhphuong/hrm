<template>
    <div class="clearfix">
        <a-upload
            v-model:file-list="fileList"
            :max-count="maxCount"
            :list-type="listTypeUpload"
            @preview="downloadFile"
            @change="handleFileChange"
            :before-upload="beforeUpload"
        >
            <div v-if="fileList.length < 8 && listTypeUpload == 'picture-card'">
                <plus-outlined/>
                <div style="margin-top: 8px">Upload</div>
            </div>
            <a-button v-else>
                <upload-outlined></upload-outlined>
                upload
            </a-button>
            <div class="text-danger">
                ( Tổng kích thước các file không quá {{ formatSize(maxSize) }} )
            </div>
        </a-upload>
        <a-modal :open="previewVisible" :title="previewTitle" :footer="null" @cancel="handleCancelPreview">
            <img alt="example" style="width: 100%" :src="previewImage"/>
        </a-modal>
    </div>
</template>

<script setup>
import {defineEmits, defineProps, onMounted, ref} from "vue";
import {PlusOutlined} from '@ant-design/icons-vue';

const emit = defineEmits(['update:value']);
import {UploadOutlined} from '@ant-design/icons-vue';
import {translate} from "@/helpers/CommonHelper.js";
import {messageError} from "@/helpers/MessageHelper.js";

const props = defineProps({
    errors: {type: [Array, Object], default: () => ({})},
    maxCount: {
        type: Number,
        default: 1
    },
    listTypeUpload: {
        type: [String],
        default: "picture-card"
    },
    maxTotalSize: {
        type: Number,
        default: 7 // 7MB
    },
    formData: {type: Object, default: () => ({})},
    disabled: {default: false}
})


const previewVisible = ref(false);
const previewImage = ref('');
const previewTitle = ref('');
const fileList = ref([]);
const maxSize = props.maxTotalSize * 1024 * 1024;

const handleCancelPreview = () => {
    previewVisible.value = false;
    previewTitle.value = '';
};

const getBase64 = (file) => {
    console.log('flie', file);
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}

const downloadFile = async (file) => {
    try {
        const response = await fetch(file.url);
        if (!response.ok) {
            throw new Error(translate('message.error.file_unavailable'));
        }

        previewImage.value = file.url || file.preview;
        previewVisible.value = true;
        previewTitle.value = file.name || file.url.substring(file.url.lastIndexOf('/') + 1);

        const blob = await response.blob();
        const downloadUrl = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.setAttribute('download', file.name || 'file'); // Đặt tên file khi lưu
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(downloadUrl); // Giải phóng URL
    } catch (error) {
        console.error(error);
        messageError(translate('message.error.file_download_failed')); // Hiển thị thông báo lỗi
    }
};

const checkTotalSize = (existingFiles, newFile) => {
    const totalSize = existingFiles.reduce((acc, file) => {
        // Kiểm tra nếu file hợp lệ và có thuộc tính size
        return acc + (file && file.size ? file.size : 0);
    }, 0) + (newFile && newFile.size ? newFile.size : 0); // Kiểm tra newFile có hợp lệ hay không

    if (totalSize > maxSize) {
        return false;
    }
    return true;
};

const beforeUpload = file => {
    return false; // Prevent auto-upload
};

const handleFileChange = info => {
    const files = info.fileList.map(file => file.originFileObj || file);
    console.log('File List:', files);
    emit('update:value', files)
};

const formatSize = (size) => {
    return (size / (1024 * 1024)).toFixed(0) + 'MB'; // Chuyển đổi kích thước sang MB và làm tròn 2 chữ số thập phân
};
const getFiles = async () => {
    const data = props.formData.file;

    if (data) {
        const result = data.map(item => ({
            uid: item.id,
            name: item.descriptions,
            status: 'done',
            url: item.file_path,
            thumbUrl: item.file_path
        }));

        fileList.value = result ?? [];
        emit('update:value', fileList.value);
    }
};

onMounted(async () => {
    await getFiles();
});

</script>

<style scoped>
/* tile uploaded pictures */
.upload-list-inline :deep(.ant-upload-list-item) {
    float: left;
    width: 200px;
    margin-right: 8px;
}

.upload-list-inline [class*='-upload-list-rtl'] :deep(.ant-upload-list-item) {
    float: right;
}
</style>
