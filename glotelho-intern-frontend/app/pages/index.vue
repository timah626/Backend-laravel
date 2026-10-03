   <template>
    <p>{{ $settings.appName }}</p>
     <UButton>Nuxt UI works</UButton>
   </template>

   <script setup lang="ts">
    const { $settings } = useNuxtApp();
  </script>