<template>
  <div
    class="fixed z-50 w-full h-full top-0 right-0 left-0 bottom-0 flex justify-center items-center bg-[#000000de]"
  >
    <div
      class="bg-[#fff] flex justify-flex-start px-6 py-6 rounded flex-col max-h-[600px] gap-3 w-[30%]"
    >
      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="flex justify-between items-center gap-2"
      >
        <RouterLink to="/" class="flex items-center gap-2">
          <div
            class="w-[44px] h-[44px] rounded-full text-[#000] relative flex justify-center items-center bg-[#fff] border border-[#9ED5CB] px-1 py-1"
          >
            <img
              :src="
                this.post.User?.image
                  ? this.post.User?.image
                  : require('@/assets/logo.png')
              "
              class="h-full rounded-full object-cover"
            />
          </div>

          <div
            :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
            class="flex flex-col text-[#445045]"
          >
            <p class="font-bold text-[16px]">
              {{ this.post.User?.name }}
            </p>
            <div
              :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
              class="flex justify-flex-start gap-1 font-bold text-[12px]"
            >
              <p>{{ this.post.Content?.time }}</p>
              <i class="pi pi-lock-open text-[12px] rounded-full"></i>
            </div>
          </div>
        </RouterLink>
        <div
          class="flex items-center gap-2"
          v-show="this.post.User?.mine === true"
        >
          <button
            class="rounded-full"
            :class="this.isHoverClose === true ? 'bg-[#F3F4F6] ' : ''"
            @mouseover="this.isHoverClose = true"
            @mouseleave="this.isHoverClose = false"
            @click="this.ShowEdit"
          >
            <i class="pi pi-times text-[14px] px-2 py-2"></i>
          </button>
        </div>
      </div>
      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="flex flex-col justify-between items-center my-3"
      >
        <div
          :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
          class="w-full flex flex-col my-2"
        >
          <input
            type="text"
            :placeholder="$t('Title')"
            contenteditable="true"
            v-model="this.postData.title"
            class="bg-[#F3F4F6] rounded py-2 focus:border-teal-500 focus:outline-none w-full text-[#445045] text-wrap text-[12px]"
            :class="
              this.$store.state.language === 'ar'
                ? 'pl-[85px] pr-2'
                : ' pl-4 pr-[85px]'
            "
          />
          <span
            class="mt-2 text-red-700 w-full flex items-center text-[13px]"
            :style="{
              'text-align':
                this.$store.state.language === 'ar'
                  ? 'right'
                  : 'left' + ' !important',
            }"
            v-for="error in errors?.title"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>
        <div
          :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
          class="w-full flex flex-col my-2"
        >
          <textarea
            type="text"
            :placeholder="$t('Body')"
            contenteditable="true"
            v-model="this.postData.body"
            rows="8"
            class="bg-[#F3F4F6] rounded py-2 focus:border-teal-500 focus:outline-none w-full text-[#445045] text-wrap text-[12px]"
            :class="
              this.$store.state.language === 'ar'
                ? 'pl-[85px] pr-2'
                : ' pl-4 pr-[85px]'
            "
          ></textarea>
          <span
            class="mt-2 text-red-700 w-full flex items-center text-[13px]"
            :style="{
              'text-align':
                this.$store.state.language === 'ar'
                  ? 'right'
                  : 'left' + ' !important',
            }"
            v-for="error in errors?.body"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>
        <div
          :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
          class="w-full flex flex-col my-2"
        >
          <div
            class="flex justify-between items-start w-full"
            v-if="previewImage !== null"
          >
            <input
              :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
              type="file"
              @change="handleFileChange"
              accept=".png, .jpg"
            />
            <div
              :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
              class="flex relative"
            >
              <img
                v-if="previewImage"
                :src="previewImage"
                alt="Preview Image"
                class="w-[80px] h-[80px] rounded object-cover"
              />
              <button
                class="rounded-full absolute top-1 bg-[#F3F4F6]"
                :class="
                  this.$store.state.language === 'ar' ? 'left-1' : 'right-1'
                "
                @click="this.deleteImage"
              >
                <i class="pi pi-trash text-[14px] px-2 py-2"></i>
              </button>
            </div>
          </div>
          <div
            :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
            class="flex justify-flex-start items-start w-full"
            v-else
          >
            <input
              :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
              type="file"
              @change="handleFileChange"
              accept=".png, .jpg"
            />
          </div>
          <span
            class="mt-2 text-red-700 w-full flex items-center text-[13px]"
            :style="{
              'text-align':
                this.$store.state.language === 'ar'
                  ? 'right'
                  : 'left' + ' !important',
            }"
            v-for="error in errors?.image"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>

        <div
          :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
          class="flex justify-center items-center w-full"
        >
          <button
            class="rounded-full mt-3 px-10 py-2 text-[#fff] bg-[#9ED5CB] text-[14px] font-bold w-full"
            @click="this.EditPost"
          >
            {{ $t("Edit") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  props: ["id", "getPosts", "post", "ShowEdit"],
  data() {
    return {
      postData: {
        id: this.post.id,
        title: this.post.Content.title,
        body: this.post.Content.body,
        selectedFile: this.post.Content.image,
      },
      errors: {
        title: null,
        body: null,
        selectedFile: null,
      },
      loading: false,
      isHoverClose: false,
      previewImage: this.post.Content.image,
    };
  },
  methods: {
    handleFileChange(event) {
      const file = event.target.files[0];
      this.postData.selectedFile = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          this.previewImage = reader.result;
        };
        reader.readAsDataURL(file);
      }
    },
    deleteImage() {
      this.previewImage = null;
    },

    EditPost() {
      this.loading = true;
      let formData = new FormData();
      formData.append("id", this.postData.id);
      formData.append("title", this.postData.title);
      formData.append("body", this.postData.body);
      if (this.previewImage != null) {
        formData.append("image", this.postData.selectedFile);
      }

      axios
        .post(`http://localhost:8000/api/site/post/update`, formData, {
          headers: {
            language: this.$store.state.language,
            authorization: `bearer ${this.$store.state.user.token}`,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          this.getPosts();
          this.loading = false;
          this.ShowEdit();
          this.$toast.open({
            message: res.data.message,
            type: "success",
            position: "top",
            duration: 3000,
            pauseOnHover: true,
          });
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
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
