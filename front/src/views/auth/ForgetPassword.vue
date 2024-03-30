<template>
  <div class="h-[100vh] flex flex-col justify-center items-center bg-gray-100">
    <div class="flex items-center justify-center text-muted">
      <button
        class="text-[14px] font-bold rounded-lg flex justify-center items-center"
        @click="changeLanguage()"
      >
        <div
          v-if="getLanguageText() === 'ar'"
          class="flex justify-center items-center gap-2"
        >
          <img
            :src="getImageSource()"
            alt="Flag"
            class="rounded-full w-10 h-10"
          />
          {{ $t(getLanguageText()) }}
        </div>
        <div v-else class="flex justify-center items-center gap-2">
          {{ $t(getLanguageText()) }}
          <img
            :src="getImageSource()"
            alt="Flag"
            class="rounded-full w-10 h-10"
          />
        </div>
      </button>
    </div>
    <div
      class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden lg:rounded-lg m-0"
    >
      <div class="flex justify-between">
        <div class="flex items-center justify-center">
          <RouterLink
            to="/"
            class="py-3 text-[#445045] text-[14px] font-bold flex items-center justify-center gap-2 text-[#445045] text-[14px]"
          >
            <i class="pi pi-arrow-circle-left text-[#445045] text-[14px]"></i>
            {{ $t("Login") }}
          </RouterLink>
        </div>
      </div>
      <RouterLink to="/login" class="flex justify-center">
        <img width="200" src="@/assets/logo.png" />
      </RouterLink>
      <slot />
      <div
        class="mb-4 text-sm text-gray-600 text-[14px] leading-6 flex justify-start items-center"
        :style="{
          'text-align':
            this.$store.state.language === 'ar'
              ? 'right'
              : 'left' + ' !important',
        }"
      >
        {{ $t("Please enter your email address or national id") }}
      </div>
      <form @submit.prevent>
        <div>
          <input
            id="email_nationl_id"
            type="text"
            class="px-5 py-3 block w-full text-[14px] border-2 border-[#9ED5CB] rounded-lg hover:border-[#9ED5CB]"
            v-model="user.data"
            required
            autofocus
            autocomplete="email_nationl_id"
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.Email Address Or National ID')"
          />

          <span
            class="mt-2 text-red-700 w-full flex items-center text-[13px]"
            :class="{ 'justify-end': this.$store.state.language == 'ar' }"
            :style="{
              'text-align':
                this.$store.state.language === 'ar'
                  ? 'right'
                  : 'left' + ' !important',
            }"
            v-for="error in errors?.data"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>

        <div class="flex items-center justify-center pt-4">
          <button
            class="w-full text-[14px] bg-[#445045] hover:bg-[#445045] px-5 py-3 font-bold rounded-lg text-[#fff]"
            v-if="this.loading === true"
          >
            <i class="pi pi-spin pi-spinner text-[#fff] text-[20px]"></i>
          </button>
          <button
            @click="Login()"
            class="w-full text-[14px] bg-[#445045] hover:bg-[#445045] px-5 py-3 font-bold rounded-lg text-[#fff]"
            v-else
          >
            {{ $t("Check") }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
  
<script>
import axios from "axios";
import CryptoJS from "crypto-js";
export default {
  name: "forget-password-view",
  mounted() {
    if (this.$store.state.isLoggedIn === "true") {
      this.$router.push({ name: "home" });
    }
    document.title =
      localStorage.getItem("language") == "ar"
        ? "نسيت كلمة المرور | نظام المدونة"
        : "Forget Password | Blog System";
  },
  data() {
    return {
      user: {
        data: "",
      },
      errors: {
        data: [],
        message: "",
      },
      loading: false,
    };
  },
  methods: {
    getImageSource() {
      return this.$store.state.language !== "en"
        ? require("@/assets/flags/en.png")
        : require("@/assets/flags/ar.png");
    },
    getLanguageText() {
      return this.$store.state.language === "en" ? "ar" : "en";
    },
    changeLanguage() {
      const newLanguage = this.$store.state.language === "en" ? "ar" : "en";
      this.$store.commit("SET_LANGUAGE", newLanguage);
      this.$i18n.locale = newLanguage;
      this.errors.data = [];
      document.title =
        localStorage.getItem("language") == "ar"
          ? "نسيت كلمة المرور | نظام المدونة"
          : "Forget Password | Blog System";
    },

    Login() {
      this.loading = true;
      axios
        .post(
          "http://localhost:8000/api/auth/forget-password",

          this.user,
          {
            headers: {
              language: this.$store.state.language,
            },
          }
        )
        .then((res) => {
          this.loading = false;
          this.$router.push({
            name: "password-recovery",
            query: {
              e: CryptoJS.AES.encrypt(this.user.data, "AES").toString(),
            },
          });
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err?.response?.data?.errors;
          this.$toast.open({
            message: err?.response?.data?.message,
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
  
  