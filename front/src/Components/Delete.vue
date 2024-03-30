<template>
  <div
    class="fixed z-50 w-full h-full top-0 right-0 left-0 bottom-0 flex justify-center items-center bg-[#000000de]"
  >
    <div
      class="bg-[#fff] flex justify-center items-center px-6 py-6 rounded flex-col"
    >
      <p class="py-2 mb-3">
        {{ $t("Are you sure you want to delete this post?") }}
      </p>
      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="h-[1px] w-full bg-[#F3F4F6]"
      ></div>
      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="flex justify-center items-center gap-3 w-full"
      >
        <button
          class="w-full flex items-center justify-center gap-3 py-2 text-[14px] font-bold rounded"
          @mouseleave="this.yes = false"
          @mouseover="this.yes = true"
          :class="this.yes == true ? 'bg-[#F3F4F6]' : ''"
          @click="this.DeletePost()"
        >
          <i class="pi pi-spin pi-spinner" v-if="this.loading == true"></i>
          <p v-else>{{ $t("Yes") }}</p>
        </button>
        <button
          class="w-full flex items-center justify-center gap-3 py-2 text-[14px] font-bold rounded"
          @mouseleave="this.no = false"
          @mouseover="this.no = true"
          :class="this.no == true ? 'bg-[#F3F4F6]' : ''"
          @click="this.ShowDelete"
        >
          <p>{{ $t("No") }}</p>
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  props: ["id", "ShowDelete", "getPosts"],
  data() {
    return {
      yes: false,
      no: false,
      loading: false,
    };
  },
  methods: {
    DeletePost() {
      this.loading = true;
      axios
        .delete(`http://localhost:8000/api/site/post`, {
          data: {
            id: this.id,
          },
          headers: {
            language: this.$store.state.language,
            authorization: `bearer ${this.$store.state.user.token}`,
          },
        })
        .then((res) => {
          this.getPosts();
          this.loading = false;
          this.ShowDelete();
          this.$toast.open({
            message: res.data.message,
            type: "success",
            position: "top",
            duration: 3000,
            pauseOnHover: true,
          });
        })
        .catch((err) => {
          this.$toast.open({
            message: err.response.data.message,
            type: "error",
            position: "top",
            duration: 3000,
            pauseOnHover: true,
          });
        });
    },
  },
};
</script>
