<template>
    <v-text-field 
        class="pt-2"
        variant="outlined"
        v-model="name"
        :label="$t(label)"
        append-inner-icon="mdi-magnify"
        :loading="isLoading"
        clearable  
        autocomplete="off" 
        :hide-details="!showDetails"
        @click:clear="emit('empty')" 
        @keyup="typing"
    />
</template>
<script setup>
    import { ref } from "vue";

    const emit = defineEmits(['empty', 'search']);
    const props = defineProps({ label: String, isLoading: Boolean, showDetails: {type: Boolean, default: false} })

    const name = ref('');
    let typingTimer;
    const typing = () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            if(name.value.length == 0){
                emit('empty');
            } else if(name.value.length >= 2) {
                emit('search', name.value);
            }
        }, 1000);
    }
</script>