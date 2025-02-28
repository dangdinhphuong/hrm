<template>
    <a-button type="primary" :size="size" class="ms-3 mt-3" danger ghost style="padding: 0px 30px">
        {{ $t('common.buttons.cancel') }}
    </a-button>
</template>

<script>
import BaseButton from "@/components/buttons/BaseButton.vue";

export default {
    extends: BaseButton
}
</script>
