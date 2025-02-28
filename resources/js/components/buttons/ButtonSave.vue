<template>
    <a-button type="primary" :size="size" class="ms-3 mt-3"
              style="background: var(--button-save-color); border-color: var(--button-save-color);padding: 0px 30px">
        {{ $t('common.buttons.save') }}
    </a-button>
</template>

<script>
import BaseButton from "@/components/buttons/BaseButton.vue";

export default {
    extends: BaseButton
}
</script>
