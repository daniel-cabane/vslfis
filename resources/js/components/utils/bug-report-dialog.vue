<template>
    <v-dialog v-model="dialog" width="500">
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
                <v-select variant="outlined" :items="studentsItems" :label="$t('Student')" v-model="student"/>
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
    import { useBugreportStore } from '@/stores/useBugreportStore';
    import { storeToRefs } from 'pinia';
    import { useI18n } from 'vue-i18n';
    import useNotifications from '@/composables/useNotifications';

    const { addNotification } = useNotifications();

    const { t } = useI18n();

    const studentStore = useStudentStore();
    const { history } = storeToRefs(studentStore);

    const { postReport } = useBugreportStore();

    const dialog = ref(false);
    const student = ref(null);
    const comment = ref('');

    const studentsItems = computed(() => ([{title: t('None'), value: 0}, ...history.value.map(s => ({ title: `${s.firstName} ${s.lastName}`, value: s.id}))]));

    const submitReport = async () => {
        if(comment.value.length <= 3){
            addNotification({text: 'The comment field is required', type: 'error'});
            return false;
        }
        const res = await postReport({student: student.value, comment: comment.value});
        if(res){
            comment.value = '';
            student.value = null;
            dialog.value = false;
        }
    }
</script>