<template>
  <div class="flex flex-col items-start justify-center bg-white rounded-lg">
    <div class="px-5 py-3">
      <h1 class="text-start text-3xl text-80 font-medium">
        Welcome Back
        <span class="font-semibold"
          >{{ card.user.firstname }} {{ card.user.lastname }}</span
        >
      </h1>
    </div>
    <div class="px-7 py-2 mb-2">
      <h1 class="text-start text-lg text-80 font-medium">
        <span class="font-semibold">{{ card.role }}</span> at
        <span class="font-semibold">{{ card.establishment }}</span>
      </h1>
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
