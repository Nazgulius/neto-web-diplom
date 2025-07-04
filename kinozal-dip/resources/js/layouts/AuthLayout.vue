<script setup lang="ts">
// import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
// import AuthLayout from '@/layouts/auth/AuthCardLayout.vue';
// import AuthLayout from '@/layouts/auth/AuthSplitLayout.vue';
import AuthLayout from '@/layouts/auth/AuthAdminLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
</template>
