<template>
    <v-dialog v-model="addStudentDialog" scrollable max-width="850">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn size="large" icon="mdi-account-multiple-plus-outline" v-bind="activatorProps"/>
        </template>
        <template v-slot:default="{ isActive }">
            <v-card style="position:relative" :title="$t('Add students')">
                <v-card-text>
                    <div class="d-flex ga-2 pt-2">
                        <v-select variant="outlined" :label="$t('Level')" v-model="newStudent.level" :items="levels"/>
                        <v-select variant="outlined" :label="$t('Section')" v-model="newStudent.section" :items="['A', 'B', 'C', 'D', 'E']"/>
                        <v-select variant="outlined" :label="$t('Status')" v-model="newStudent.status" :items="statusOptions"/>
                    </div>
                    <v-window v-model="AddStudentWindow">
                        <v-window-item value="default">
                            <div class="d-flex ga-2 align-center">
                                <v-btn style="flex:1" variant="outlined" color="primary" density="compact" append-icon="mdi-account-plus" :disabled="nullStatus" :text="$t('Add one')" @click="AddStudentWindow = 'addOne'"/>
                                <v-btn style="flex:1" variant="outlined" color="primary" density="compact" append-icon="mdi-clipboard" :disabled="nullStatus" :text="$t('Paste multiple')" @click="pasteStudents"/>
                                <v-icon size="x-large" icon="mdi-help-circle-outline" @click="showHelp=!showHelp"/>
                            </div>
                        </v-window-item>
                        <v-window-item value="addOne">
                            <div class="d-flex ga-2 py-3">
                                <std-text-input v-model="newStudent.lastName" label="Last Name"/>
                                <std-text-input v-model="newStudent.firstName" label="First Name"/>
                            </div>
                            <div class="d-flex ga-2">
                                <std-text-input v-model="newStudent.email" label="Email"/>
                                <v-number-input hide-details control-variant="hidden" variant="outlined" :label="$t('Tag number')" v-model="newStudent.tagNb"/>
                            </div>
                            <div class="d-flex justify-end mt-4">
                                <v-btn variant="tonal" color="error" density="compact" class="mr-2" :text="$t('Cancel')" @click="AddStudentWindow = 'default'"/>
                                <v-btn variant="outlined" color="primary" density="compact" :text="$t('Add')" @click="addSingleStudent"/>
                            </div>
                        </v-window-item>
                    </v-window>
                    <v-divider class="my-4"/>
                    <v-data-table hover hide-default-footer items-per-page="-1" :headers="headers" :items="newStudents">
                        <template v-slot:item="{ item }">
                            <tr>
                                <td>{{ item.firstName }} {{ item.lastName }}</td>
                                <td>{{ item.email }}</td>
                                <td class="text-center">{{ item.tagNb }}</td>
                                <td class="text-center">{{ item.level }}{{ item.section }}</td>
                                <td class="text-center">{{ item.status }}</td>
                                <td class="text-center"><v-icon icon="mdi-close-thick" color="error" @click="removeStudent(item.id)"/></td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-card-text>
                <dialog-footer :isLoading="isLoading" @yes="proceed" @no="cancelAddStudents"/>
                <transition name="slide-fade">
                    <v-card class="help pa-2" elevation="12" width="500" v-if="showHelp" @click="showHelp=false">
                        <div>
                            <div class="text-muted">
                                {{ $t('Hint') }}
                            </div>
                            <div>
                                {{ $t("Copy data from a spreadsheet and click the 'Paste Multiple' button above to add multiple students from this class at the same time.") }}
                            </div>
                            <div class="text-muted mt-4">
                                {{ $t('Spreadsheet format') }}
                            </div>
                            <div>
                                <table style="border-collapse:collapse; min-width:400px; width:100%; font-family:Arial, sans-serif;">
                                    <thead>
                                        <tr style="background:#acacac;">
                                            <th></th>
                                            <th style="border:1px solid #d3d3d3; padding:8px; font-weight:bold; width: 20%;">A</th>
                                            <th style="border:1px solid #d3d3d3; padding:8px; font-weight:bold; width: 20%;">B</th>
                                            <th style="border:1px solid #d3d3d3; padding:8px; font-weight:bold; width: 20%;">C</th>
                                            <th style="border:1px solid #d3d3d3; padding:8px; font-weight:bold; width: 20%;">D</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border:1px solid #d3d3d3; padding:8px;text-align: center;">1</td>
                                            <td style="border:1px solid #d3d3d3; padding:8px;text-align: center;">{{ $t('Last Name') }}</td>
                                            <td style="border:1px solid #d3d3d3; padding:8px;text-align: center;">{{ $t('First Name') }}</td>
                                            <td style="border:1px solid #d3d3d3; padding:8px;text-align: center;">{{ $t('Email') }}</td>
                                            <td style="border:1px solid #d3d3d3; padding:8px;text-align: center;">{{ $t('Tag nb') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px dotted #bdbdbd; padding:8px; text-align: center;">2</td>
                                            <td style="border:1px dotted #bdbdbd; padding:8px; color:#bdbdbd;text-align: center;">...</td>
                                            <td style="border:1px dotted #bdbdbd; padding:8px; color:#bdbdbd;text-align: center;">...</td>
                                            <td style="border:1px dotted #bdbdbd; padding:8px; color:#bdbdbd;text-align: center;">...</td>
                                            <td style="border:1px dotted #bdbdbd; padding:8px; color:#bdbdbd;text-align: center;">...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </v-card>
                </transition>
            </v-card>
        </template>
    </v-dialog>
</template>
<script setup>
    import { ref, computed } from "vue";
    import useNotifications from '@/composables/useNotifications';
    import { useI18n } from 'vue-i18n';
    import { useStudentStore } from '@/stores/useStudentStore';

    const { saveStudents } = useStudentStore();

    const { t } = useI18n();

    const { addNotification } = useNotifications();

    const emit = defineEmits(['saveStudents']);
    const props = defineProps({ isLoading: Boolean });

    const AddStudentWindow = ref('default');
    const addStudentDialog = ref(false);
    const newStudent = ref({});
    const newStudents = ref([]);
    const showHelp = ref(false);
    const levels = ['6e', '5e', '4e', '3e', '2nde', '1re', 'Term', 'Y7', 'Y8', 'Y9', 'Y10', 'Y11', 'Y12'];
    const statusOptions = [
        { title: t('Cyan'), value: 'cyan' }, { title: t('Blue'), value: 'blue' }, { title: t('Red'), value: 'red' }, {title: t('Black'), value: 'black'}
    ];
    const sortRaw = (a,b) => {
        const levelA = levels.indexOf(a.level);
        const levelB = levels.indexOf(b.level);

        if (levelA !== levelB) {
            return levelA - levelB;
        }

        if (a.section && b.section) {
            return a.section.localeCompare(b.section);
        }
        if (!a.section) return 1;
        if (!b.section) return -1;
        return 0;
    }

    const headers = [
        { title: t('Name'), key: 'lastName' },
        { title: t('Email'), key: 'email' },
        { title: t('Tag nb'), key: 'tagNb', align: 'center' },
        { title: t('Class'), key: 'level', sortRaw, align: 'center' },
        { title: t('Status'), key: 'status', align: 'center' },
        { title: t('Remove'), sortable: false, align: 'center' }
    ];
    const cancelAddStudents = () => {
        AddStudentWindow.value = 'default';
        newStudent.value = {};
        newStudents.value = [];
        showHelp.value = false;
        addStudentDialog.value = false;
    }

    let id = 0;
    const nullStatus = computed(() => {
        return !newStudent.value.level || !newStudent.value.section || !newStudent.value.status;
    });
    const formatFirstName = str => str.replace(/\b\w/g, c => c.toUpperCase()).replace(/\s+/g, '-');
    const addSingleStudent = () => {
        if(!newStudent.value.level || !newStudent.value.section || !newStudent.value.status){
            addNotification({ text: 'Level, section and status are required', type: 'error'});
            return;
        }
        if(!newStudent.value.lastName || !newStudent.value.firstName){
            addNotification({ text: 'First and Last name are required', type: 'error'});
            return;
        }
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!pattern.test(newStudent.value.email)){
            addNotification({ text: 'Valid email required', type: 'error'});
            return;
        }
        newStudent.value.firstName = formatFirstName(newStudent.value.firstName);
        newStudent.value.lastName = newStudent.value.lastName.toUpperCase();
        newStudent.id = id++;
        newStudents.value.push(newStudent.value);
        newStudent.value = { level: newStudent.value.level, section: newStudent.value.section, status: newStudent.value.status};
    }

    const pasteStudents = async () => {
        let clipboardText = '';

        clipboardText = await navigator.clipboard.readText();
        const rows = clipboardText.split('\n').map(row => row.split('\t'));

        rows.forEach(row => {
            if (row.length >= 3 && row[0]) {
                newStudents.value.push({
                    id: id++,
                    level: newStudent.value.level,
                    section: newStudent.value.section,
                    status: newStudent.value.status,
                    lastName: row[0].toUpperCase(),
                    firstName: formatFirstName(row[1]),
                    email: row[2],
                    tagNb: row[3]
                });
            }
        });
    }

    const removeStudent = id => {
        newStudents.value = newStudents.value.filter(s => s.id != id);
    }

    const proceed = async () => {
        const res = await saveStudents(newStudents.value);
        if(res){
            cancelAddStudents();
        }
    }
</script>
<style scoped>
    .help {
        position: absolute;
        top: 30px;
        right: 60px;
    }
    .slide-fade-enter-active {
        transition: all .2s ease;
    }

    .slide-fade-leave-active {
        transition: all .2s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .slide-fade-leave-to {
        transform: translateX(-40px);
        opacity: 0;
    }

    .slide-fade-enter-from {
        transform: translateX(-40px);
        opacity: 0;
    }
    .slide-fade-move {
        transition: all .5s ease-out;
    }
</style>