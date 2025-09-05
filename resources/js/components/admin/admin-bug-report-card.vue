<template>
    <v-card :title="`${$t('Report from')} ${report.author.name}`">
        <v-card-text class="d-flex">
            <div style="flex:1">
                <div class="text-caption text-muted">
                    {{ $t('Comment') }}
                </div>
                <div>
                    {{ report.comment }}
                </div>
            </div>
            <div style="width:350px;">
                <div class="text-caption text-muted">
                    {{ $t('Student') }}
                </div>
                <v-card class="pa-3 d-flex justify-space-between align-center" v-if="report.student">
                    <div class="text-h6">
                        {{ report.student.firstName }} {{ report.student.lastName }} ({{ report.student.level }}{{ report.student.section }})
                    </div>
                    <v-btn icon="mdi-magnify" color="primary" variant="tonal" @click="emit('search', `${report.student.firstName} ${report.student.lastName}`)"/>
                </v-card>
                <div class="text-h6 text-muted d-flex justify-center" v-else>
                    {{ $t('None') }}
                </div>
            </div>
        </v-card-text>
        <v-card-actions>
            <!-- <v-btn variant="flat" color="error" :text="$t('Delete')" @click="emit('delete', report.id)"/> -->
            <v-spacer/>
            <v-btn variant="flat" color="secondary" :text="$t('Close')" @click="emit('close', report.id)"/>
        </v-card-actions>
    </v-card>
</template>
<script setup>
    const props = defineProps({ report: Object });

    const emit = defineEmits(['search', 'close', 'delete']);
</script>