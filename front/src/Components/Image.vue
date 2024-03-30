<template>
  <div
    class="fixed z-50 w-full h-full top-0 right-0 left-0 bottom-0 flex justify-center items-center bg-[#000000de]"
  >
    <div
      :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
      class="relative w-full h-full flex justify-center items-center"
    >
      <img :src="this.image" class="h-full" />
      <button
        class="absolute top-5 right-5 text-[#000] bg-[#fff] rounded-full"
        @click="this.showImage"
      >
        <i class="pi pi-times text-[14px] px-2 py-2"></i>
      </button>
    </div>
  </div>
</template>
<script>
export default {
  props: ["image", "showImage"],
};
</script>
