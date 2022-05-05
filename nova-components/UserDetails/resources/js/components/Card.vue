<template>
  <div class="flex flex-col items-center justify-center shadow-lg rounded-lg">
    <div class="px-3 py-3">
      <h1 class="text-center text-3xl text-80 font-light">User Details</h1>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    "card",

    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  ],

  mounted() {
    //
  },
};
</script>
