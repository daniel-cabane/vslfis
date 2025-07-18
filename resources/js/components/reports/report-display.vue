<template>
    <v-card-text>
        <div class="text-caption text-muted mt-2">
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
        <div class="text-caption text-muted mt-2">
            {{ $t('Location') }}
        </div>
        <div v-if="report.location">
            {{ report.location }}
        </div>
        <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-muted font-italic" v-else>
            {{ $t('Not specified') }}
        </div>
        <div class="text-caption text-muted mt-2">
            {{ $t('Comment') }}
        </div>
        <div v-if="report.comment">
            {{ report.comment }}
        </div>
        <div style="min-height:72px" class="d-flex justify-center align-center text-h6 text-muted font-italic" v-else>
            {{ $t('None') }}
        </div>
        <v-divider class="my-4"/>
        <div class="d-flex justify-center align-center text-h6" v-if="report.filedBy">
            <v-icon icon="mdi-check-circle" color="primary" size="large"/>
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
    </v-card-text>
</template>
<script setup>
    const props = defineProps({ report: Object });
    const emit = defineEmits(['close']);

    const cap = string => string.charAt(0).toUpperCase() + string.slice(1);
    const formatStudentName = student => `${student.firstName} ${student.lastName} (${student.level}${student.section})`;
</script>