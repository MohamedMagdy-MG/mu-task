<template>
  <div
    :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
    class="flex justify-between items-center gap-2 w-full"
  >
    <div
      :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
      class="flex items-center gap-2"
    >
      <div
        class="rounded-full text-[#000] w-[44px] h-[44px] relative flex justify-center items-center bg-[#fff] border border-[#9ED5CB] px-1 py-1"
      >
        <img
          :src="
            this.comment?.User?.image
              ? this.comment?.User?.image
              : require('@/assets/logo.png')
          "
          class="h-[30px] rounded-full object-cover"
        />
      </div>

      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="flex flex-col"
      >
        <div
          class="flex flex-col bg-[#F3F4F6] px-5 py-4 text-[#000] rounded items-start justify-flex-start gap-1 w-[400px]"
        >
          <div
            class="flex justify-between items-center w-full"
            v-if="this.post.User?.mine === true"
          >
            <p class="font-bold text-[12px]">
              {{ this.comment?.User.name }}
            </p>
            <input
              type="checkbox"
              @change="this.selectComment()"
              :checked="this.selected"
            />
          </div>
          <div class="flex justify-between items-center w-full" v-else>
            <p class="font-bold text-[12px]">
              {{ this.comment?.User.name }}
            </p>
          </div>

          <div
            :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
            class="flex justify-flex-start items-start text-[12px]"
          >
            <p>
              {{ this.comment?.comment }}
            </p>
          </div>
        </div>
        <p class="text-[12px] px-2">
          {{ this.comment?.time }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["comment", "selectedComments", "post"],
  data() {
    return {
      selected: false,
    };
  },
  methods: {
    selectComment() {
      this.selected = !this.selected;
      if (this.selected === true) {
        this.selectedComments.push(this.comment.id);
      }else{
        this.selectedComments.pop(this.comment.id);

      }
    },
  },
};
</script>

