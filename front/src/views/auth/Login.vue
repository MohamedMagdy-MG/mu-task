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
      <RouterLink to="/login" class="flex justify-center">
        <img width="200" src="@/assets/logo.png" />
      </RouterLink>
      <slot />

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

        <div class="mt-4">
          <input
            id="password"
            type="password"
            class="px-5 py-3 mt-1 block w-full text-[14px] border-2 border-[#9ED5CB] rounded-lg hover:border-[#9ED5CB] rounded-lg"
            v-model="user.password"
            required
            autocomplete="current-password"
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
            {{ $t("Login") }}
          </button>
        </div>

        <div class="border-b border-b-gray-200 my-3"></div>

        <div
          class="flex justify-between my-3"
          v-if="getLanguageText() === 'ar'"
        >
          <div class="flex items-center justify-center">
            <RouterLink
              to="/register"
              class="px-5 py-3 text-[#445045] bg-[#9ED5CB] hover:bg-[#9ED5CB] text-[14px] font-bold rounded-lg"
            >
              {{ $t("Create new account") }}
            </RouterLink>
          </div>
          <div class="flex items-center justify-center">
            <RouterLink
              to="/forget-password"
              class="px-5 py-3 text-[#445045] text-[14px] font-bold"
            >
              {{ $t("Forgotten password?") }}
            </RouterLink>
          </div>
        </div>
        <div class="flex justify-between my-3" v-else>
          <div class="flex items-center justify-center">
            <RouterLink
              to="/forget-password"
              class="px-5 py-3 text-[#445045] text-[14px] font-bold"
            >
              {{ $t("Forgotten password?") }}
            </RouterLink>
          </div>
          <div class="flex items-center justify-center">
            <RouterLink
              to="/register"
              class="px-5 py-3 text-[#445045] bg-[#9ED5CB] hover:bg-[#9ED5CB] text-[14px] font-bold rounded-lg"
            >
              {{ $t("Create new account") }}
            </RouterLink>
          </div>
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
        ? "تسجيل الدخول | نظام المدونة"
        : "Login | Blog System";
  },
  created() {},
  data() {
    return {
      user: {
        data: "",
        password: "",
      },
      errors: {
        data: [],
        password: [],
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
      this.errors.password = [];
      document.title =
        localStorage.getItem("language") == "ar"
          ? "تسجيل الدخول | نظام المدونة"
          : "Login | Blog System";
    },

    Login() {
      this.loading = true;
      axios
        .post(
          "http://localhost:8000/api/auth/login",

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
          if (res?.data?.data?.otp_date != null) {
            this.$router.push({ name: "activate" });
          } else {
            this.$router.push("/");
          }
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err?.response?.data?.errors;
          this.user.password = "";
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
  
  