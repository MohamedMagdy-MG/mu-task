<template>
  <div
    v-show="this.AddForm === false"
    class="flex justify-center items-center max-h-[600px] px-2 py-2 w-full"
  >
    <div
      :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
      class="flex justify-center items-center w-full"
    >
      <button
        class="rounded-full mt-3 px-10 py-2 text-[#fff] bg-[#9ED5CB] text-[14px] font-bold w-full"
        @click="this.AddPostForm"
      >
        {{ $t("Add") }}
      </button>
    </div>
  </div>
  <div
    v-show="this.AddForm === true"
    class="flex justify-center items-center bg-[#fff] max-h-[600px] px-2 py-2 w-full"
  >
    <div
      :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
      class="flex justify-flex-start rounded flex-col gap-3 w-full px-3"
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
                this.$store.state.user?.image
                  ? this.$store.state.user?.image
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
              {{
                this.$store.state.language === "ar"
                  ? this.$store.state.user?.name_ar
                  : this.$store.state.user?.name_en
              }}
            </p>
          </div>
        </RouterLink>
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
            class="bg-[#F3F4F6] rounded py-2 focus:border-teal-500 focus:outline-none w-full text-[#445045] text-wrap text-[12px]"
            :class="
              this.$store.state.language === 'ar'
                ? 'pl-[85px] pr-2'
                : ' pl-4 pr-[85px]'
            "
            contenteditable="true"
            v-model="this.postData.title"
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
            class="bg-[#F3F4F6] rounded py-2 focus:border-teal-500 focus:outline-none w-full text-[#445045] text-wrap text-[12px]"
            contenteditable="true"
            v-model="this.postData.body"
            rows="8"
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
          class="flex justify-center items-center w-full gap-3"
        >
          <div
            :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
            class="w-full"
          >
            <button
              class="rounded-full mt-3 px-10 py-2 text-[#fff] bg-[#9ED5CB] text-[14px] font-bold w-full"
              @click="this.AddPost"
              v-if="this.loading === false"
            >
              {{ $t("Add") }}
            </button>
            <button
              v-else
              class="rounded-full mt-3 px-10 py-2 text-[#fff] bg-[#9ED5CB] text-[14px] font-bold w-full"
            >
              <i class="pi pi-spin pi-spinner text-[#fff] text-[20px]"></i>
            </button>
          </div>
          <button
            class="rounded-full mt-3 px-10 py-2 text-[#000] bg-[#F3F4F6] text-[14px] font-bold w-full"
            @click="this.AddPostForm"
          >
            {{ $t("Close") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  props: ["getPosts"],
  data() {
    return {
      postData: {
        title: "",
        body: "",
        selectedFile: null,
      },
      errors: {
        title: null,
        body: null,
        selectedFile: null,
      },
      loading: false,
      isHoverClose: false,
      previewImage: null,
      AddForm: false,
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
    AddPostForm() {
      this.AddForm = !this.AddForm;
      this.errors = {
        title: null,
        body: null,
        selectedFile: null,
      };
    },
    AddPost() {
      this.loading = true;
      let formData = new FormData();
      formData.append("title", this.postData.title);
      formData.append("body", this.postData.body);
      if (this.previewImage != null) {
        formData.append("image", this.postData.selectedFile);
      }

      axios
        .post(`http://localhost:8000/api/site/post`, formData, {
          headers: {
            language: this.$store.state.language,
            authorization: `bearer ${this.$store.state.user.token}`,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          this.AddPostForm();
          this.postData = {
            title: "",
            body: "",
            selectedFile: null,
          };
          this.previewImage = null;
          this.getPosts();
          this.loading = false;
          this.$toast.open({
            message: res.data.message,
            type: "success",
            position: "top",
            duration: 3000,
            pauseOnHover: true,
          });
        })
        .catch((err) => {
          this.loading = false;
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
