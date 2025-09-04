<template>
    <v-dialog v-model="dialog" max-width="90%" width="500">
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
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </v-card-text>

            <dialog-footer @yes="submitReport" @no="dialog=false"/>
            </v-card>
        </template>
    </v-dialog>
</template>
<script setup>
    import { ref } from 'vue';

    const dialog = ref(false);

    const submitReport = () => {
        console.log('submit');
    }
</script>