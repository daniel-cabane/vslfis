<template>
    <div>
        <div class="text-caption text-muted">
            {{ $t('Students') }}
        </div>
        <div class="d-flex flex-wrap ga-2" v-if="report.students.length">
            <v-chip 
                v-for="student in report.students"
                :text="formatStudentName(student)"
            />
        </div>
        <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-muted font-italic" v-else>
            {{ $t('No student') }}
        </div>
        <div class="text-caption text-muted mt-4">
            {{ $t('Location') }}
        </div>
        <div v-if="report.location">
            {{ report.location }}
        </div>
        <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-muted font-italic" v-else>
            {{ $t('Not specified') }}
        </div>
        <div class="text-caption text-muted mt-4">
            {{ $t('Comment') }}
        </div>
        <div v-if="report.comment">
            {{ report.comment }}
        </div>
        <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-muted font-italic" v-else>
            {{ $t('None') }}
        </div>
        <div class="d-flex ga-2 mt-4">
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
        <v-divider class="my-6"/>
        <div class="d-flex justify-center align-center text-h6" v-if="report.filedBy">
            <v-icon icon="mdi-check-circle" color="success" class="mr-2" size="large"/>
            {{ $t('Filed on Pronote by') }} {{ report.filedBy.name }}
        </div>
        <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-muted font-italic" v-else-if="report.finalized">
            <v-icon icon="mdi-close-circle" color="faded" class="mr-2" size="large"/>
            {{ $t('Not filed yet') }}
        </div>
        <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-error font-weight-bold" v-else>
            <v-icon icon="mdi-close-circle" color="error" class="mr-2" size="large"/>
            {{ $t('Not finalized') }}
        </div>
    </div>
</template>
<script setup>
    const props = defineProps({ report: Object });
    const emit = defineEmits(['close']);
    import { useI18n } from 'vue-i18n';

    const { locale } = useI18n();

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