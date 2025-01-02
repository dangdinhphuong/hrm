import '@/bootstrap';
import App from '@/App.vue';
import {createApp} from "vue";
import {createPinia} from 'pinia';
import {languages} from "@assets/js/lang/index.js";
import router from "@/router/index.js";


import {LoadingPlugin} from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

import Antd,{
    Drawer,
    Button,
    List,
    Menu,
    message,
    Card,
    Table,
    Select,
    Input,
    Layout,
    Form,
    Alert,
    Dropdown,
    Pagination,
    Space,
    Tag,
    Tree,
    Modal,
    RangePicker,
    DatePicker
} from 'ant-design-vue';

import 'ant-design-vue/dist/reset.css';
import 'bootstrap/dist/css/bootstrap-grid.min.css';
import 'bootstrap/dist/css/bootstrap-utilities.min.css';

import axios from "axios";

window.axios = axios;

const pinia = createPinia();

const app = createApp(App);

//Antd
app.use(Antd);
// app.use(Button);
// app.use(Drawer);
// app.use(List);
// app.use(Menu);
// app.use(Card);
// app.use(Table);
// app.use(Select);
// app.use(Input);
// app.use(Layout);
// app.use(Form);
// app.use(Alert);
// app.use(Dropdown);
// app.use(Pagination);
// app.use(Space);
// app.use(Tag);
// app.use(Tree);
// app.use(Modal);
// app.use(DatePicker);
// app.use(RangePicker);

app.use(router);
app.use(pinia);
app.use(languages);
app.use(LoadingPlugin);

app.mount("#app");

app.config.globalProperties.$message = message;
