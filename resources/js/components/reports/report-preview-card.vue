<template>
    <v-card width="400">
        <v-card-title class="pb-0" :style="`color:white;background:${report.category.color}`">
            {{ cap($t(report.category.title)) }}
        </v-card-title>
        <v-card-subtitle class="pb-2" :style="`color:white;background:${report.category.color};opacity:1`">
            {{ $t(report.category.description) }}
        </v-card-subtitle>
        <v-card-text class="pb-0">
            <div style="min-height:72px" class="d-flex flex-wrap ga-2" v-if="report.students.length">
                <v-chip 
                    v-for="student in report.students.slice(0,2)"
                    :text="formatStudentName(student)"
                />
                <v-tooltip position="top" v-if="report.students.length > 2">
                    <template v-slot:activator="{ props }">
                        <v-chip :text="`+${report.students.length-2} more`" v-bind="props"/>
                    </template>
                    <div>
                        <div v-for="student in report.students.slice(2)">
                            {{ formatStudentName(student) }}
                        </div>
                    </div>
                </v-tooltip>
            </div>
            <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-muted font-italic" v-else>
                {{ $t('No student') }}
            </div>
            <div class="d-flex ga-2 mt-2">
                <div style="flex:1">
                    <div class="text-caption text-muted">
                        {{ $t('Reported on') }}
                    </div>
                    <div>
                        {{ formatDateTime(report.on) }}
                    </div>
                </div>
                <div style="flex:1">
                    <div class="text-caption text-muted">
                        {{ $t('Reported by') }}
                    </div>
                    <div>
                        {{ report.by.name }}
                    </div>
                </div>
            </div>
        </v-card-text>
        <v-card-actions class="pt-0 pl-3">
            <report-status-icon :report="report"/>
            <v-spacer/>
            <v-btn icon="mdi-eye" color="primary" @click="emit('reportDetails', report)"/>
        </v-card-actions>
    </v-card>
</template>
<script setup>
    import { useReportStore } from '@/stores/useReportStore';
    import { useI18n } from 'vue-i18n';

    const { locale } = useI18n();

    const { categories } = useReportStore();
    const props = defineProps({ report: Object });
    const emit = defineEmits(['reportDetails']);

    const cap = string => string.charAt(0).toUpperCase() + string.slice(1);
    const formatStudentName = student => `${student.firstName} ${student.lastName} (${student.level}${student.section})`;

    const formatDateTime = dt => {
        const date = new Date(dt);
        const options = {
            month: 'short',
            day: 'numeric',
            weekday: 'short',
            hour: '2-digit',
            minute: '2-digit'
        };

        const formatter = new Intl.DateTimeFormat(locale.value, options);
        return  formatter.format(date);
    }
</script>