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
      <RouterLink to="/reset-password" class="flex justify-center">
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
        {{ $t("This is a secure area of the application") }}
      </div>
      <form @submit.prevent>
        <div class="mt-4">
          <input
            id="password"
            type="password"
            class="px-5 py-3 mt-1 block w-full text-[14px] border-2 border-[#9ED5CB] hover:border-[#9ED5CB] rounded-lg"
            v-model="user.password"
            required
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.password')"
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
            v-for="error in errors?.password"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>
        <div class="mt-4">
          <input
            id="password_confirmation"
            type="password"
            class="px-5 py-3 mt-1 block w-full text-[14px] border-2 border-[#9ED5CB] hover:border-[#9ED5CB] rounded-lg"
            v-model="user.password_confirmation"
            required
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.Confirm Password')"
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
            v-for="error in errors?.password_confirmation"
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
            <i class="pi pi-spin pi-spinner text-[#fff] text-[14px]"></i>
          </button>
          <button
            @click="Verify()"
            class="w-full text-[14px] bg-[#445045] hover:bg-[#445045] px-5 py-3 font-bold rounded-lg text-[#fff]"
            v-else
          >
            {{ $t("Reset Password") }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
  
<script>
import axios from "axios";
export default {
  name: "reset-password-view",
  mounted() {
    if (this.$store.state.isLoggedIn !== "true") {
      this.$router.push({ name: "login" });
    }
    document.title =
      localStorage.getItem("language") == "ar"
        ? "انشاء كلمة مرور جديدة | نظام المدونة"
        : "Create a new password | Password Recovery";
  },
  data() {
    return {
      user: {
        password: "",
        password_confirmation: "",
      },
      errors: {
        password: [],
        password_confirmation: [],
        message: "",
      },
      loading: false,
      resendLoading: false,
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
      this.errors.password = [];
      this.errors.password_confirmation = [];
      document.title =
        localStorage.getItem("language") == "ar"
          ? "انشاء كلمة مرور جديدة | نظام المدونة"
          : "Create a new password | Password Recovery";
    },

    Verify() {
      this.loading = true;
      axios
        .post("http://localhost:8000/api/site/profile/password", this.user, {
          headers: {
            language: this.$store.state.language,
            Authorization: `Bearer ${
              JSON.parse(localStorage.getItem("user")).token
            }`,
          },
        })
        .then((res) => {
          this.loading = false;
          this.$router.push("/");
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err?.response?.data?.errors;
          this.user.password = "";
          this.user.password_confirmation = "";
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
  
  