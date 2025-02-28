import {ref} from 'vue'

export function useComponentKey(componentName) {
    const keyComponent = ref({});
    keyComponent.value[componentName] = 0;

    function getComponentKey() {
        return keyComponent.value[componentName];
    }

    function incrementComponentKey() {
        keyComponent.value[componentName] += 1;
    }

    return {
        getComponentKey,
        incrementComponentKey
    }
}
