<template>
  <card class="flex flex-col justify-start pl-4 pt-4">
    <h4 class="font-bold text-primary text-2xl">Menu de la journée</h4>
    <div v-if="card.menu" class="px-3 py-3">
      <h1 class="text-left text-lg text-80 font-light">
        Plat Principal :
        <span class="font-semibold">{{ card.menu.main_dish }}</span>
      </h1>
      <h1 class="text-left text-lg text-80 font-light">
        Plat Secondaire :
        <span class="font-semibold">{{ card.menu.secondary_dish }}</span>
      </h1>
      <h1 class="text-left text-lg text-80 font-light">
        Dessert : <span class="font-semibold">{{ card.menu.dessert }}</span>
      </h1>
    </div>
    <div v-else>
      <h1 class="text-centre text-lg text-80 font-semibold">
        Pas D'information pour le moment
      </h1>
    </div>
  </card>
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
