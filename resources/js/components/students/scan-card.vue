<template>
    <v-dialog v-model="dialog" width="450" max-width="90%">
        <template v-slot:activator>
            <v-btn 
                size="large" 
                icon="mdi-credit-card-wireless-outline" 
                :disabled="disable" 
                @click="scan"
            />
        </template>
        <v-card style="position:relative;">
            <v-progress-linear indeterminate :reverse="scanning" v-if="scanning || isLoading"/>
            <img :src="`/images/scan card ${locale}.png`"/>
            <v-btn 
                variant="tonal" 
                size="small" 
                icon="mdi-close" 
                color="error" 
                style="position:absolute;bottom:5px;left:5px;"
                @click="cancelScan"
            />
        </v-card>
    </v-dialog>
</template>
<script setup>
    import { ref } from "vue";
    import { useStudentStore } from '@/stores/useStudentStore';
    import { storeToRefs } from 'pinia';
    import useNotifications from '@/composables/useNotifications';
    import { useI18n } from 'vue-i18n';

    const { locale } = useI18n();

    const { addNotification } = useNotifications();

    const studentStore = useStudentStore();
    const { findByTag } = studentStore;
    const { isLoading } = storeToRefs(studentStore);

    const emit = defineEmits(['scanSuccessful']);

    const disable = ('NDEFReader' in window); // ! this in prod

    const dialog = ref(false);
    const scanning = ref(false);

    const scan = async () => {
        dialog.value = true;
        scanning.value = true;
        setTimeout(async () => { // replace this with ndef logic
            scanning.value = false;
            const student = await findByTag(405204025);
            if(student){
                dialog.value = false;
                emit('scanSuccessful', student);
            }
        }, 2000);
    }
    const cancelScan = () => {
        dialog.value = false;
        scanning.value = false;
    }
</script>