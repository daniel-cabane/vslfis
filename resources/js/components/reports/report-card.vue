<template>
    <v-card>
        <v-toolbar :color="category.color">
            <v-menu v-if="mode=='edit'">
                <template v-slot:activator="{ props }">
                    <v-btn icon="mdi-chevron-down" v-bind="props"/>
                </template>
                <v-list>
                    <v-list-subheader :title="$t('Change category')"/>
                    <v-list-item 
                        v-for="cat in otherCategories"
                        :title="cap($t(cat.title))"
                        @click="category = cat"
                    />
                </v-list>
            </v-menu>
            <div v-else class="d-flex align-center" style="height:100%">
                <v-icon :icon="category.icon" class="ml-3"/>
            </div>
            <v-toolbar-title>
                <div>{{ cap($t(category.title)) }}</div>
                <div class="text-caption">{{ $t(category.description) }}</div>
            </v-toolbar-title>
            <template v-slot:append>
                <v-btn icon="mdi-close" @click="closeDialog"/>
            </template>
        </v-toolbar>
        <v-card-text>
            <v-window v-model="mode">
                <v-window-item value="view">
                    <report-display :report="report"/>
                </v-window-item>
                <v-window-item value="edit">
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
                </v-window-item>
            </v-window>
        </v-card-text>
        <v-card-actions>
            <v-btn variant="tonal" color="error" :text="$t('Cancel')" @click="mode='view'" v-if="mode=='edit' && report.id"/>
            <v-btn variant="tonal" color="error" :text="$t('Close')" @click="closeDialog" v-else/>
            <v-spacer/>
            <div class="d-flex align-center" v-if="mode=='edit'">
                <v-switch color="primary" class="mr-2" inset v-model="finalized" hide-details/>
                <span class="mr-4 text-h6 font-weight-bold" :class="finalized ? 'text-primary' : 'text-faded'">
                    {{ $t('Finalized') }}
                </span>
                <v-btn variant="flat" color="primary" :text="$t('Confirm')" @click="confirmReport"/>
            </div>
            <v-fab class="justify-end" :color="open ? '' : 'primary'" icon v-else>
                <v-icon>{{ open ? 'mdi-close' : 'mdi-menu' }}</v-icon>
                <v-speed-dial v-model="open" location="top" transition="slide-y-reverse-transition" activator="parent">
                    <v-btn key="1" color="faded" icon="mdi-close-circle" @click="unfileDialog=true" v-if="report.filedBy"/>
                    <v-btn key="2" :color="report.finalized ? 'success' : 'grey'" icon="mdi-check" @click="showFileDialog" v-else/>
                    <v-btn key="3" :color="report.editable ? 'info' : 'grey'" icon="mdi-pencil" @click="editReportMode"/>
                    <v-btn key="4" :color="report.deletable ? 'error' : 'grey'" icon="mdi-delete" @click="showDeleteDialog"/>
                </v-speed-dial>
            </v-fab>
        </v-card-actions>
        <v-dialog v-model="deleteDialog" width="450">
            <v-card :title="$t('Are you sure ?')">
                <v-card-text>
                    <v-checkbox v-model="confirmDelete" :label="$t('Yes, delete this report')" hide-details/>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn variant="tonal" color="primary" :text="$t('Cancel')" @click="deleteDialog=false"/>
                    <v-btn variant="flat" color="error" :text="$t('Delete')" :disabled="!confirmDelete" @click="proceedDelete"/>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="fileDialog" width="450">
            <v-card :title="$t('File on Pronote')">
                <v-card-text>
                    {{ $t('Did you file this report on Pronote ?') }}
                </v-card-text>
                <dialog-footer cancel-text="No" confirm-text="Yes" @yes="proceedFileReport(report.id)" @no="fileDialog=false" v-if="finalized"/>
                <v-card-actions v-else>
                    <v-spacer/>
                    <v-btn variant="tonal" color="primary" :text="$t('Close')" @click="fileDialog=false"/>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="unfileDialog" width="450">
            <v-card :title="$t('Cancel filing')">
                <v-card-text>
                    {{ $t('Do you want to cancel the filed status ?') }}
                </v-card-text>
                <dialog-footer cancel-text="No" confirm-text="Yes" @yes="proceedUnfileReport(report.id)" @no="unfileDialog=false"/>
            </v-card>
        </v-dialog>
        <v-dialog v-model="photoDialog">
            <img style="max-width:90vw;max-height:90vh;object-fit:contain;position:relative;" :src="photoUrl"/>
            <v-btn icon="mdi-close" color="error" style="position:fixed;top:10px;right:10px;" @click="photoDialog=false"/>
        </v-dialog>
    </v-card>
</template>
<script setup>
    import { ref, computed } from "vue";
    import { useReportStore } from '@/stores/useReportStore';
    import { useAuthStore } from '@/stores/useAuthStore';
    import { storeToRefs } from 'pinia';
    import useNotifications from '@/composables/useNotifications';

    const { addNotification } = useNotifications();

    const reportStore = useReportStore();
    const { searchStudents, purgeStudents, removeStudent, addStudent, saveReport, updateReport, deleteReport, fileReport, unfileReport, categories } = reportStore;
    const { students, isLoading } = storeToRefs(reportStore);

    const { removeFromUnfinalized } = useAuthStore();

    const props = defineProps({ report: Object });
    const emit = defineEmits(['close']);

    const mode = ref(props.report.id ? 'view' : 'edit');
    const open = ref(false);

    const cap = string => string.charAt(0).toUpperCase() + string.slice(1);
    const otherCategories = computed(() => {
        return categories.filter(t => t.title != category.value);
    });

    const tab = ref('students');

    const involvedStudents = ref(props.report.students);
    const location = ref(props.report.location);
    const comment = ref(props.report.comment);
    const category = ref(props.report.category);
    const finalized = ref(props.report.finalized);
    const photoDialog = ref(false);
    const photoUrl = ref(null);
    const showPhoto = url => {
        if(url){
            photoUrl.value = url;
            photoDialog.value = true;
        }
    }
    const getStudentFromScan = student => {
        involvedStudents.value.push(student);
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
        emit('close');
    }
    const confirmReport = async () => {
        if(props.report.id){
            const res = updateReport({
                id: props.report.id,
                category: category.value.title,
                students: involvedStudents.value.map(s => s.id),
                location: location.value,
                comment: comment.value,
                finalized: finalized.value,
            });
            if(res){
                if(finalized.value){
                    removeFromUnfinalized(props.report.id);
                }
                closeDialog();
            }
        } else {
            const res = saveReport({
                category: category.value.title,
                students: involvedStudents.value.map(s => s.id),
                location: location.value,
                comment: comment.value,
                finalized: finalized.value,
            });
            if(res){
                closeDialog();
            }
        }
    }

    const editReportMode = () => {
        if(props.report.editable){
            mode.value = 'edit'
        } else {
            addNotification({ text: 'You cannot edit this report', type: 'error'})
        }
        
    }

    const deleteDialog = ref(false);
    const confirmDelete = ref(false);
    const showDeleteDialog = () => {
        if(props.report.deletable){
            deleteDialog.value = true;
        } else {
            addNotification({ text: 'You cannot delete this report', type: 'error'})
        }
    }
    const proceedDelete = async () => {
        const res = await deleteReport(props.report.id);
        if(res){
            closeDialog();
        }
    }

    const fileDialog = ref(false);
    const showFileDialog = () => {
        if(props.report.finalized){
            fileDialog.value = true;
        } else {
            addNotification({ text: 'Report has to be finalized to be filed on Pronote', type: 'error'})
        }
    }
    const proceedFileReport = async id => {
        const res = await fileReport(id);
        if(res){
            closeDialog();
        }
    }
    const unfileDialog = ref(false);
    const proceedUnfileReport = async id => {
        const res = await unfileReport(id);
        if(res){
            closeDialog();
        }
    }
</script>