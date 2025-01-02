<template>
    <div class="clearfix">

        <a-upload
            v-model:file-list="fileList"
            :max-count="maxCount"
            :list-type="listTypeUpload"
            @preview="downloadFile"
            @change="handleFileChange"
            :disabled="disabled"
            :before-upload="beforeUpload"
        >
            <div>
                <div>
                    <div v-if="fileList.length < 8 && listTypeUpload == 'picture-card' && !disabled">
                        <plus-outlined />
                        <div style="margin-top: 8px">Upload</div>
                    </div>
                    <a-button v-else-if="listTypeUpload != 'picture-card' && !disabled">
                        <upload-outlined></upload-outlined>
                        Upload
                    </a-button>
                </div>
                <div class="text-danger">
                    ( Định dạng pdf, doc, docx, xls, xlsx, txt, img và tổng kích thước các file không quá
                    {{formatSize(maxSize)}} )
                </div>
            </div>
        </a-upload>
        <div v-if="maxCount > 1" v-for="(item, indexItem) in errors" :key="indexItem">
            <p v-if="indexItem.includes('file')">
                <close-circle-outlined :style="{ color: 'red' }" />
                {{ item[0] }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { defineEmits, defineProps, onMounted, ref } from "vue";
import { PlusOutlined, UploadOutlined, CloseCircleOutlined, DownloadOutlined} from '@ant-design/icons-vue';
import {messageError} from "@/helpers/MessageHelper.js";
import {translate} from "@/helpers/CommonHelper.js";

const emit = defineEmits(['update:value']);
const props = defineProps({
    errors: { type: [Array, Object], default: () => ({}) },
    maxCount: {
        type: Number,
        default: 1
    },
    maxTotalSize : {
    type: Number,
    default: 7 // 7MB
   },
    listTypeUpload: {
        type: [String],
        default: "picture-card"
    },
    formData: { type: Object, default: () => ({}) },
    disabled: { default: false },
});
const previewVisible = ref(false);
const previewImage = ref('');
const previewTitle = ref('');
const previewUrl = ref('')
const fileList = ref([]);
const maxSize = props.maxTotalSize * 1024 * 1024;

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

const getBase64 = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
};

const handleCancelPreview = () => {
    previewVisible.value = false;
    previewTitle.value = '';
};
const downloadFile = async (file) => {
    try {
        const response = await fetch(file.url);
        if (!response.ok) {
            throw new Error(translate('message.error.file_unavailable'));
        }
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

const formatSize = (size) => {
    return (size / (1024 * 1024)).toFixed(0) + 'MB'; // Chuyển đổi kích thước sang MB và làm tròn 2 chữ số thập phân
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
    return false; // Ngăn việc tự động upload
};

const handleFileChange = info => {
    const newFiles = info.fileList.map(file => file.originFileObj || file);
    // Tách các phần tử là file ra một mảng riêng
    const fileArray = newFiles.filter(file => file instanceof File);
    // Lấy file mới nhất được thêm
    const latestFile = fileArray[fileArray.length - 1];


    // Kiểm tra nếu file vượt quá 2MB
    const maxFileSize = 2 * 1024 * 1024; // 2MB
    if (latestFile && latestFile.size > maxFileSize) {
        messageError(`Kích thước mỗi file không được vượt quá 2MB. File "${latestFile.name}" vượt quá giới hạn.`);
        // Loại bỏ file vượt quá kích thước cho phép
        info.fileList.pop();
        fileList.value = info.fileList.map(file => file.originFileObj || file);
        return;
    }

    // Kiểm tra tổng kích thước của các file trong fileArray trước khi thêm vào fileList
    const totalSize = fileArray.reduce((acc, file) => acc + file.size, 0);
    const maxSize = 10 * 1024 * 1024; // Giới hạn tổng dung lượng (ví dụ: 10MB)

    if (totalSize > maxSize) {
        messageError(`Tổng kích thước các file tải lên không được vượt quá ${formatSize(maxSize)}.`);
        // Loại bỏ file cuối cùng (file vượt quá dung lượng cho phép)
        info.fileList.pop();
        fileList.value = info.fileList.map(file => file.originFileObj || file);
    } else {
        // Nếu kích thước hợp lệ, cập nhật danh sách file
        fileList.value = newFiles;
    }
    emit('update:value', fileList.value);
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

