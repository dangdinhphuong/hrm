import {ref} from 'vue'

export function useLoading() {
    const loading = ref(true);

    function isLoading() {
        return !loading.value;
    }

    function isLoadingComplete() {
        return !loading.value;
    }

    function setLoading() {
        loading.value = true;
    }

    function setLoadingComplete() {
        loading.value = false;
    }

    return {
        isLoading,
        isLoadingComplete,
        setLoading,
        setLoadingComplete
    }
}
