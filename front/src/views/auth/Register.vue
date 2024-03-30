<template>
  <div class="flex flex-col justify-center items-center bg-gray-100 my-5">
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
      class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden lg:rounded-lg m-0 text-[#445045] text-[14px]"
    >
      <div class="flex justify-between">
        <div class="flex items-center justify-center">
          <RouterLink
            to="/login"
            class="py-3 text-[#445045] font-bold flex items-center gap-2 text-[14px]"
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

      <form @submit.prevent>
        <div>
          <input
            id="name_en"
            type="text"
            class="px-5 py-3 block w-full text-[14px] border-2 border-[#9ED5CB] rounded-lg hover:border-[#9ED5CB]"
            v-model="user.name_en"
            required
            autofocus
            autocomplete="name_en"
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.Name (English)')"
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
            v-for="error in errors?.name_en"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>
        <div class="mt-4">
          <input
            id="name_ar"
            type="text"
            class="px-5 py-3 block w-full text-[14px] border-2 border-[#9ED5CB] rounded-lg hover:border-[#9ED5CB]"
            v-model="user.name_ar"
            required
            autofocus
            autocomplete="name_ar"
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.Name (Arabic)')"
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
            v-for="error in errors?.name_ar"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>

        <div class="mt-4">
          <input
            id="national_id"
            type="text"
            class="px-5 py-3 block w-full text-[14px] border-2 border-[#9ED5CB] rounded-lg hover:border-[#9ED5CB]"
            v-model="user.national_id"
            required
            autofocus
            autocomplete="national_id"
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.National ID')"
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
            v-for="error in errors?.national_id"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>
        <div class="mt-4">
          <input
            id="email"
            type="email"
            class="px-5 py-3 block w-full text-[14px] border-2 border-[#9ED5CB] rounded-lg hover:border-[#9ED5CB]"
            v-model="user.email"
            required
            autofocus
            autocomplete="email"
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.Email Address')"
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
            v-for="error in errors?.email"
            v-bind:key="error"
          >
            {{ error }}
          </span>
        </div>

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
            <i class="pi pi-spin pi-spinner text-[#fff]"></i>
          </button>
          <button
            @click="Register()"
            class="w-full text-[14px] bg-[#445045] hover:bg-[#445045] px-5 py-3 font-bold rounded-lg text-[#fff]"
            v-else
          >
            {{ $t("Register") }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
  
<script>
import axios from "axios";

export default {
  name: "login-view",
  mounted() {
    if (this.$store.state.isLoggedIn === "true") {
      this.$router.push({ name: "home" });
    }
    document.title =
      localStorage.getItem("language") == "ar"
        ? "التسجيل | نظام المدونة"
        : "Register | Blog System";
  },
  data() {
    return {
      user: {
        name_en: "",
        name_ar: "",
        national_id: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: {
        name_en: [],
        name_ar: [],
        national_id: [],
        email: [],
        password: [],
        password_confirmation: [],
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
      this.errors.name_en = [];
      this.errors.name_ar = [];
      this.errors.national_id = [];
      this.errors.email = [];
      this.errors.password = [];
      this.errors.password_confirmation = [];
      document.title =
        localStorage.getItem("language") == "ar"
          ? "التسجيل | نظام المدونة"
          : "Register | Blog System";
    },

    Register() {
      this.loading = true;
      axios
        .post(
          "http://localhost:8000/api/auth/register",

          this.user,
          {
            headers: {
              language: this.$store.state.language,
            },
          }
        )
        .then((res) => {
          this.loading = false;
          localStorage.setItem("isLoggedIn", true);
          localStorage.setItem("user", JSON.stringify(res?.data?.data));
          this.$store.dispatch("loginUser");
          this.$router.push({ name: "activate" });
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
  
  