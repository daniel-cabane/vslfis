<template>
<v-app-bar color="secondary-darken-1">
    <template v-slot:prepend>
        <v-img width='45px' src="/images/lfi logo.png" contain />
    </template>
    <v-spacer />
    <v-tabs centered stacked v-model="navigationTab" exact :mandatory="false" v-if="!isWindowXs">
        <v-tab value="tab-1" to="/">
            <v-icon>mdi-eye</v-icon>
            {{ $t('report') }}
        </v-tab>
        <v-tab value="tab-2" to="/manage">
            <v-icon>mdi-table-check</v-icon>
            {{ $t('manage') }}
        </v-tab>
    </v-tabs>
    <v-spacer />
    <template v-slot:append>
        <profile-menu :user="user"/>
    </template>
</v-app-bar>
</template>
<script setup>
    import { ref, computed, watch } from "vue";
    import { useDisplay } from 'vuetify';
    import { useRoute } from 'vue-router';

    let navigationTab = ref('tab-1');
    const props = defineProps({ user: Object });
    console.log(props.user);

    const { name } = useDisplay();
    const isWindowXs = computed(() => name.value == 'xs');

    const route = useRoute();
    watch(() => route.name, (newVal, oldVal) => {
        switch(newVal){
        case 'Home':
            navigationTab.value = 'tab-1';
        break;
        case 'Manage':
            navigationTab.value = 'tab-2';
        break;
        default:
            navigationTab.value = null;
        break;
        }
    });
</script>