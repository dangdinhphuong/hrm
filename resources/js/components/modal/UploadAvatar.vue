<template>
    <a-modal :open="open" :title="$t('hrm.employees.avatar.update')" :inert="!open" @ok="handleOk"
             @cancel="handleCancel">
        <div>
            <input
                ref="input"
                type="file"
                name="image"
                accept="image/*"
                @change="setImage"
            />
            <div class="content container">
                <section class="cropper-area">
                    <div class="img-cropper">
                        <vue-cropper
                            v-if="imgSrc"
                            ref="cropper"
                            :aspect-ratio="1"
                            :src="imgSrc"
                            preview=".preview"
                        />
                        <a-empty
                            v-else
                            @click="showFileChooser"
                            @dragover.prevent
                            @drop="handleDrop"
                            :image="empty"
                            :image-style="{ height: '60px' }"
                        >
                            <template #description>
                                <span>Kéo và thả ảnh vào đây hoặc nhấp để chọn</span>
                            </template>
                        </a-empty>
                    </div>
                    <div class="actions" v-if="imgSrc">
                        <ZoomInOutlined @click="zoom(0.2)"/>
                        <ZoomOutOutlined @click="zoom(-0.2)"/>
                        <LeftOutlined @click="move(-10, 0)"/>
                        <RightOutlined @click="move(10, 0)"/>
                        <UpOutlined @click="move(0, -10)"/>
                        <DownOutlined @click="move(0, 10)"/>
                        <RotateRightOutlined @click="rotate(90)"/>
                        <RotateLeftOutlined @click="rotate(-90)"/>
                        <ReloadOutlined @click="reset"/>
                        <UploadOutlined @click="showFileChooser"/>
                    </div>
                </section>
            </div>
        </div>
        <template #footer>
            <a-button key="back" @click="handleCancel">Quay lại</a-button>
            <a-button key="submit" type="primary" :loading="loading" @click="handleOk">Gửi</a-button>
        </template>
    </a-modal>
</template>

<script setup>
import {ref, watch, defineProps, defineEmits} from 'vue';
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import UserService from '@/services/system/UserService.js';
import {
    ZoomInOutlined, ZoomOutOutlined, LeftOutlined, RightOutlined,
    UpOutlined, DownOutlined, RotateRightOutlined, RotateLeftOutlined,
    ReloadOutlined, UploadOutlined
} from '@ant-design/icons-vue';
import {isSuccessRequest} from '@/helpers/AxiosHelper.js';
import {messageError, messageSuccess} from '@/helpers/MessageHelper.js';
import empty from '@assets/images/original.png';
import {translate} from "@/helpers/CommonHelper.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import {authStore} from "@/stores/AuthStore.js";
import { Potrace } from 'potrace';

const imgSrc = ref(''); // URL ảnh để hiển thị
const input = ref(null); // Tham chiếu đến input file
const cropper = ref(null); // Tham chiếu đến cropper
const props = defineProps({showModal: {type: Boolean, default: false}});
const loading = ref(false); // Trạng thái tải
const emit = defineEmits(['update:showModal', 'update:avatar', 'crop']); // Sự kiện
const userService = new UserService(); // Dịch vụ người dùng
const open = ref(props.showModal); // Trạng thái mở modal
const maxFileSize = ref(20 * 1024 * 1024); // Kích thước file tối đa (20 MB)

const userStore = authStore();
const userEmployeeId = userStore.getUser?.employeeId || 0;
const employeeId = getCurrentRouteParams('employeeId') || userEmployeeId;
const employeeService = new EmployeeService();


// Theo dõi thay đổi của showModal
watch(() => props.showModal, newVal => open.value = newVal);

// Xử lý khi nhấn nút "Gửi"
const handleOk = async () => {
    if (cropper.value) {
        let avatar = cropper.value.getCroppedCanvas().toDataURL();
        loading.value = true;
        try {
            avatar = await compressImage(avatar, 1); // Nén ảnh nếu lớn hơn 1 MB
            const response = await employeeService.uploadAvatar(employeeId, {avatar});
            if (isSuccessRequest(response)) {
                messageSuccess(response.message);
                // Cập nhật state user trong Pinia
                if(userEmployeeId == employeeId){
                    userStore.setAvatar(response.data.file);
                }
                emit('update:avatar', response.data.file);

                handleCancel();
            } else {
                messageError(response.message);
            }
        } catch (error) {
            console.error('Lỗi tải ảnh:', error);
        } finally {
            loading.value = false;
        }
    } else {
        messageError('Cropper chưa được khởi tạo');
    }
};

// Xử lý khi nhấn nút "Quay lại"
const handleCancel = () => {
    // Reset ảnh và cropper về trạng thái ban đầu
    resetImage();

    // Reset input file
    if (input.value) {
        input.value.value = null; // Đặt giá trị null để xóa input file
    }

    // Đóng modal
    open.value = false;
    emit('update:showModal', open.value);

    // Reset thêm các trạng thái khác nếu cần (ví dụ trạng thái loading)
    loading.value = false;
};
const resetImage = () => {
    imgSrc.value = '';
    cropper.value?.clear();  // Xóa dữ liệu từ cropper
};

// Điều chỉnh cropper với các hành động như zoom, move, rotate
const adjustCropper = (action, ...args) => {
    cropper.value?.[action](...args);
};

// Chọn ảnh từ input file
const setImage = e => {
    const file = e.target.files[0];
    if (!file) return messageError('Chưa chọn file');
    if (!file.type.startsWith('image/')) return messageError('Vui lòng chọn file ảnh');
    if (file.size > maxFileSize.value) return messageError('Kích thước file không vượt quá 1 MB');

    const reader = new FileReader();
    reader.onload = event => {
        imgSrc.value = event.target.result;
        cropper.value?.replace(event.target.result);
    };
    reader.readAsDataURL(file);
};

// Xử lý khi kéo và thả ảnh vào khu vực
const handleDrop = e => {
    e.preventDefault(); // Ngăn ngừa hành vi mặc định của trình duyệt
    const file = e.dataTransfer.files[0];
    if (file) setImage({target: {files: [file]}});
};

// Hiển thị hộp thoại chọn file
const showFileChooser = () => input.value.click();

// Các hàm điều chỉnh cropper
const zoom = percent => adjustCropper('relativeZoom', percent);
const move = (offsetX, offsetY) => adjustCropper('move', offsetX, offsetY);
const reset = () => adjustCropper('reset');
const rotate = deg => adjustCropper('rotate', deg);

// Nén ảnh nếu kích thước lớn hơn maxSizeMB
const compressImage = async (base64Image, maxSizeMB) => {
    const img = new Image();
    img.src = base64Image;

    return new Promise((resolve) => {
        img.onload = () => {
            let canvas = document.createElement('canvas');
            let ctx = canvas.getContext('2d');

            const MAX_WIDTH = 800;
            const MAX_HEIGHT = 800;

            let width = img.width;
            let height = img.height;

            if (width > MAX_WIDTH || height > MAX_HEIGHT) {
                if (width > height) {
                    height = Math.round(height * MAX_WIDTH / width);
                    width = MAX_WIDTH;
                } else {
                    width = Math.round(width * MAX_HEIGHT / height);
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;

            ctx.drawImage(img, 0, 0, width, height);

            let newBase64 = canvas.toDataURL('image/jpeg', 0.7);

            while (newBase64.length > maxSizeMB * maxFileSize.value) {
                canvas.width *= 0.9;
                canvas.height *= 0.9;
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                newBase64 = canvas.toDataURL('image/jpeg', 0.7);
            }

            resolve(newBase64);
        };
    });
};

</script>

<style lang="scss" scoped>
.ant-upload-select-picture-card i {
    font-size: 32px;
    color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
    margin-top: 8px;
    color: #666;
}

input[type="file"] {
    display: none;
}

:deep(.actions span) {
    font-size: 25px;
    line-height: 44px;
    height: 40px;
    margin: 2%;
}
</style>
