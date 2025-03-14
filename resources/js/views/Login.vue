<template>
    <div class="container-fluid">
        <div class="row" style="min-height: 100vh;">
            <div class="d-none d-sm-flex col-sm-8 wrapper-col-left" :style="{ backgroundColor }">
                <div>
                    <img :src="configs?.setting_logo ?? logoCrm" alt="img" style="width:65%">
                </div>
            </div>
            <div class="col-12 col-sm-4 d-flex flex-column justify-content-between">
                <div class="login-header mt-3" style="width: 80%;margin:0 auto;">
                    <img :src="configs?.setting_small_logo ?? logoEdupia" alt="img" class="">
                </div>

                <div class="login-content" style="width: 80%;margin:0 auto">
                    <div class="login-intro">
                        <h2><b>{{ $t('login.title') }}</b></h2>
                        <p v-html="$t('login.intro', { company: configs?.setting_company_name ?? 'Educa' })"></p>
                    </div>
                    <div class="login-form mt-3">
                        <a-form
                            :model="formData"
                            @finish="login"
                        >
                            <div class="row">
                                <div class="col-12">
                                    <b>{{ $t('login.input_text.account') }}</b>
                                </div>
                                <div class="col-12">
                                    <a-form-item
                                        name="username"
                                        :rules="[{ required: true, message: $t('login.messages.account_required') }]"
                                    >
                                        <a-input v-model:value="formData.username"/>
                                    </a-form-item>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <b>{{ $t('login.input_text.password') }}</b>
                                </div>
                                <div class="col-12">
                                    <a-form-item
                                        name="password"
                                        :rules="[{ required: true, message: $t('login.messages.password_required') }]"
                                    >
                                        <a-input-password v-model:value="formData.password"/>
                                    </a-form-item>
                                </div>
                            </div>
                            <div class="row" v-if="isLoginFail">
                                <div class="col-12">
                                    <a-alert
                                        :message="$t('login.messages.login_fail')"
                                        type="error"
                                        closable
                                    />
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <a-button type="primary" html-type="submit" block>
                                        {{ $t('login.buttons.login') }}
                                    </a-button>
                                </div>
                            </div>
                        </a-form>
                    </div>
                </div>

                <div class="login-footer text-center mt-3 mb-3">
                    {{ $t('footer.intro', { company: configs?.setting_company_name ?? 'Educa' }) }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, reactive, ref, computed  } from 'vue';
import logoCrm from '@assets/images/logo/crm.png';
import logoEdupia from '@assets/images/logo/edupia.png';
import AuthService from "@/services/system/AuthService.js";
import {getFromLocalStorage, saveToLocalStorage} from "@/helpers/CommonHelper.js";
const backgroundColor = computed(() => configs.value?.setting_subsidebar_color || '#6993FF');
// State
const configs = ref([]);
const isLoginFail = ref(false);
const images = reactive({ logoCrm, logoEdupia });
const formData = reactive({ username: '', password: '' });
// Methods
const login = async () => {
    try {
        await new AuthService().login(formData.username, formData.password);
    } catch (error) {
        isLoginFail.value = true;
    }
};
// Lifecycle Hooks

async function loadConfigs() {
    const configData = await getFromLocalStorage('configs');

    if (configData.length > 0) {
        configs.value = configData[0] || 'Unknown';
    }

    console.log('Configs:', configData);
}

onBeforeMount(loadConfigs);
</script>

<style lang="scss" scoped>
.wrapper-col-left {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #6993FF;
    text-align: center;
}
</style>
