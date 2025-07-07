<template>
    <v-card width="400" class="pa-2">
        <v-card-title class="d-flex">
            <v-spacer/>
            <span class="text-muted">
                {{ user.name }}
            </span>
        </v-card-title>
        <v-card-text class="pb-0">
            <v-text-field variant="outlined" :label="$t('Name')" v-model="name" :disabled="isLoading"/>
            <v-text-field variant="outlined" :label="$t('Email')" v-model="email" :disabled="isLoading"/>
            <v-checkbox label="CPE" v-model="isCPE" hide-details density="compact"/>
        </v-card-text>
        <v-card-actions>
            <v-btn icon="mdi-delete" color="error" @click="emit('delete', user.id)"/>
            <v-spacer/>
            <v-btn 
                variant="flat"
                color="primary"
                density="compact" 
                :text="$t('Save')"
                @click="emit('update', {id: user.id, name, email, isCPE})"
            />
        </v-card-actions>
    </v-card>
</template>
<script setup>
    import { ref } from "vue";

    const props = defineProps({ user: Object, isLoading: Boolean });

    const emit = defineEmits(['update', 'delete']);

    const name = ref(props.user.name);
    const email = ref(props.user.email);
    const isCPE = ref(props.user.is.cpe);
</script>