<template>
    <div>
        hy
{{ csId }}
    </div>
</template>

<script setup>

import { onMounted,ref } from 'vue';
import { useRoute } from 'vue-router';


const route = useRoute();
const csId = ref(0);
onMounted(()=>{
csId.value = atob(route.params.csId) ;
})
</script>
