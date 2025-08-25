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

    const disable = !('NDEFReader' in window);
    const ndef = ref(null);

    const dialog = ref(false);
    const scanning = ref(false);

    const scan = async () => {
        dialog.value = true;
        scanning.value = true;
        try {
            ndef.value = new NDEFReader();
            await ndef.value.scan();

            ndef.value.addEventListener("readingerror", () => {
                addNotification("Unreadable tag");
            });

            ndef.value.addEventListener("reading", fetchStudentFromScan);
        } catch (err) {
            addNotification({text: 'Reader is not available', type: 'error'});
            console.log(err);
            ndef.value = null;
            dialog.value = false;
            scanning.value = false;
        }
        ndef.value = null;
    }
    const fetchStudentFromScan = async data => {
        const tagId = parseInt(data.serialNumber.split(":").reverse().join(""), 16);
        const student = await findByTag(tagId);
        if(student){
            emit('scanSuccessful', student);
        }
        dialog.value = false;
        ndef.value.removeEventListener("reading", fetchStudentFromScan);
        ndef.value = null;
    }
    const cancelScan = () => {
        ndef.value.removeEventListener("reading", fetchStudentFromScan);
        ndef.value = null;
        dialog.value = false;
        scanning.value = false;
    }
</script>