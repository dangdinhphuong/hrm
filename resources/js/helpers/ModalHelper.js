import {Modal} from 'ant-design-vue';

export function modalConfirm(title, content, onOk) {
    Modal.confirm({
        title: title,
        content: content,
        onOk() {
            return onOk();
        }
    });
}
