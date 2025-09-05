<template>
    <v-dialog v-model="dialog" max-width="90%" width="500" v-if="false">
        <template v-slot:activator="{ props: activatorProps }">
            <v-list-item v-bind="activatorProps">
                <template v-slot:prepend>
                    <v-icon icon="mdi-bug"></v-icon>
                </template>
                <v-list-item-title>{{ $t('Bug report') }}</v-list-item-title>
            </v-list-item>
        </template>
        <template v-slot:default="{ isActive }">
            <v-card :title="$t('Bug report')">
            <v-card-text>
                <v-select variant="outlined" :items="studentsItems" :label="$t('Student')"/>
                <v-textarea variant="outlined" v-model="comment" :label="$t('Comment')"/>
            </v-card-text>

            <dialog-footer @yes="submitReport" @no="dialog=false"/>
            </v-card>
        </template>
    </v-dialog>
</template>
<script setup>
    import { ref, computed } from 'vue';
    import { useStudentStore } from '@/stores/useStudentStore';
    import { storeToRefs } from 'pinia';

    const studentStore = useStudentStore();
    const { history } = storeToRefs(studentStore);

    const dialog = ref(false);
    const student = ref(null);
    const comment = ref('');

    const studentsItems = computed(() => history.value.map(s => ({ title: `${s.firstName} ${s.lastName}`, value: s.id})));

    const submitReport = () => {
        console.log('submit');
    }
</script>