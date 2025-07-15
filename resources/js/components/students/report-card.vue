<template>
    <v-card>
        <v-toolbar :color="type.color">
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn icon="mdi-chevron-down" v-bind="props"/>
                </template>
                <v-list>
                    <v-list-subheader :title="$t('Change category')"/>
                    <v-list-item 
                        v-for="type in otherTypes"
                        :title="cap($t(type.title))"
                        @click="emit('changeType', type)"
                    />
                </v-list>
            </v-menu>
            <v-toolbar-title>
                <div>{{ cap($t(type.title)) }}</div>
                <div class="text-caption">{{ $t(type.description) }}</div>
            </v-toolbar-title>
            <template v-slot:append>
                <v-btn icon="mdi-close" @click="closeDialog"/>
            </template>
        </v-toolbar>
        <v-card-text>
            <v-tabs v-model="tab">
                <v-tab value="students">{{ $t('Students') }}</v-tab>
                <v-tab value="more">{{ $t('More info') }}</v-tab>
            </v-tabs>
            <v-tabs-window v-model="tab">
                <v-tabs-window-item value="students">
                    <div>
                        <div class="text-h6 text-muted text-center py-4" v-if="involvedStudents.length == 0">
                            {{ $t('No student') }}
                        </div>
                        <div>
                            <involved-student-card class="my-2" v-for="student in involvedStudents" :student="student" @photo="showPhoto" @remove="removeInvolvedStudent"/>
                        </div>
                        <v-divider class="my-4"/>
                        <div class="d-flex align-center ga-4 my-2">
                            <search-input label="Name or email" :isLoading="isLoading" @search="searchStudents"/>
                            <scan-card @scanSuccessful="getStudentFromScan" class="mt-2 mr-2"/>
                        </div>
                        <div class="pa-2" v-if="students.length">
                            <v-list class="py-0">
                                <v-divider/>
                                <student-preview 
                                    v-for="student in students" 
                                    :student="student" 
                                    @picked="involveStudent(student)"
                                    @photo="showPhoto"
                                />
                            </v-list>
                            <div class="d-flex justify-end mt-2">
                                <v-btn variant="outlined" color="error" density="compact" :text="$t('Clear list')" @click="purgeStudents"/>
                            </div>
                        </div>
                    </div>
                </v-tabs-window-item>
                <v-tabs-window-item value="more">
                    <std-text-input class="mt-4" v-model="location" label="Location"/>
                    <v-textarea class="mt-4" variant="outlined" v-model="comment" :label="$t('Comment')"/>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card-text>
        <v-card-actions>
            <v-btn variant="tonal" color="error" :text="$t('Cancel')" @click="closeDialog"/>
            <v-spacer/>
            <v-btn variant="flat" color="primary" :text="$t('Confirm')" @click="confirmReport"/>
        </v-card-actions>
        <v-dialog v-model="photoDialog">
            <img style="max-width:90vw;max-height:90vh;object-fit:contain;position:relative;" :src="photoUrl"/>
            <v-btn icon="mdi-close" color="error" style="position:fixed;top:10px;right:10px;" @click="photoDialog=false"/>
        </v-dialog>
    </v-card>
</template>
<script setup>
    import { ref, computed } from "vue";
    import { useReportStore } from '@/stores/useReportStore';
    import { storeToRefs } from 'pinia';

    const reportStore = useReportStore();
    const { searchStudents, purgeStudents, removeStudent, addStudent, saveReport, types } = reportStore;
    const { students, isLoading } = storeToRefs(reportStore);

    const props = defineProps({ type: Object });
    const emit = defineEmits(['cancel', 'changeType']);

    const cap = string => string.charAt(0).toUpperCase() + string.slice(1);
    const otherTypes = computed(() => {
        return types.filter(t => t.title != props.type.title);
    });

    const tab = ref('students');

    const involvedStudents = ref([]);
    const photoDialog = ref(false);
    const photoUrl = ref(null);
    const location = ref('');
    const comment = ref('');
    const showPhoto = url => {
        if(url){
            photoUrl.value = url;
            photoDialog.value = true;
        }
    }
    const getStudentFromScan = nb => {
        console.log(nb);
    }
    const involveStudent = student => {
        involvedStudents.value.push(student);
        removeStudent(student);
    }
    const removeInvolvedStudent = student => {
        involvedStudents.value = involvedStudents.value.filter(s => s.id != student.id);
        addStudent(student);
    }

    const closeDialog = () => {
        purgeStudents();
        emit('cancel');
    }
    const confirmReport = async () => {
        const res = saveReport({
            category: props.type.title,
            students: involvedStudents.value.map(s => s.id),
            location: location.value,
            comment: comment.value
        });
        if(res){
            closeDialog();
        }
    }
</script>