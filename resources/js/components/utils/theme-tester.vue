<template>
    <div class="my-8">
        <v-divider>
            <span class="text-caption text-muted">
                Theme tester
            </span>
        </v-divider>
        <div class="mt-8 d-flex justify-space-between align-center">
            <span class="text-h5 text-muted">
                Buttons
            </span>
            <v-switch label="Disable" v-model="buttonsDisabled" hide-details/>
        </div>
        <div class="d-flex flex-wrap ga-2 mb-2" v-for="color in colors">
            <v-btn 
                v-for="variant in variants" 
                text="Button" 
                :variant="variant" 
                :color="color"
                :disabled="buttonsDisabled"
            />
        </div>
        <div class="mt-8 text-h5 text-muted">
            Chips
        </div>
        <div class="d-flex flex-wrap ga-2 mb-2" v-for="color in colors">
            <v-chip 
                v-for="variant in variants" 
                text="Chip" 
                :variant="variant" 
                :color="color"
            />
        </div>
    </div>
</template>
<script setup>
    import { ref } from "vue";

    const buttonsDisabled = ref(false);

    const variants = [
        'text', 'flat', 'elevated', 'tonal', 'outlined', 'plain'
    ];
    const colors = [
        "primary",
        "primary-darken-1",
        "secondary",
        "secondary-darken-1",
        "error",
        "info",
        "success",
        "warning",
        "muted",
        "faded"
    ];
</script>