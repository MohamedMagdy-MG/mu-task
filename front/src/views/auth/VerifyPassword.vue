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
      <RouterLink to="/password-recovery" class="flex justify-center">
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
        {{ $t("Thank you for waiting") }}
      </div>
      <form @submit.prevent>
        <div>
          <input
            id="otp"
            type="text"
            class="px-5 py-3 block w-full text-[14px] border-2 border-[#9ED5CB] rounded-lg hover:border-[#9ED5CB]"
            v-model="user.otp"
            required
            autofocus
            autocomplete="otp"
            :class="{
              'text-right': this.$store.state.language === 'ar',
              'text-left': this.$store.state.language !== 'en',
            }"
            :placeholder="$t('Forms.OTP Code')"
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
            v-for="error in errors?.otp"
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
            {{ $t("Verify") }}
          </button>
        </div>

        <div class="border-b border-b-gray-200 my-3"></div>

        <div
          v-if="this.resendActive == false"
          class="flex py-3 text-[#445045] text-[14px] font-bold"
          :class="{
            'justify-start items-start': this.$store.state.language == 'ar',
            'justify-end items-end': this.$store.state.language == 'en',
          }"
        >
          <slot v-if="this.countDownMin == 1">0{{ this.countDownMin }}</slot>
          <slot v-else>00</slot>
          <slot>:</slot>
          <slot v-if="this.countDownMin == 1">00</slot>
          <slot v-else-if="this.countDown < 10">0{{ this.countDown }}</slot>
          <slot v-else>{{ this.countDown }}</slot>
        </div>
        <div v-if="this.resendActive == true">
          <div
            class="flex my-3"
            :class="{
              'justify-start items-start': this.$store.state.language == 'ar',
              'justify-end items-end': this.$store.state.language == 'en',
            }"
          >
            <div class="flex items-center justify-center">
              <button
                class="w-full text-[#445045] text-[14px] py-3 font-bold rounded-lg"
                v-if="this.resendLoading === true"
              >
                <i class="pi pi-spin pi-spinner text-[#fff] text-[14px]"></i>
              </button>
              <button
                @click="Resend()"
                class="py-3 text-[#445045] text-[14px] font-bold"
                v-else
              >
                {{ $t("Resend Code?") }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
  
<script>
import axios from "axios";
import CryptoJS from "crypto-js";

export default {
  name: "verify-password-view",
  mounted() {
    if (this.$store.state.isLoggedIn === "true") {
      this.$router.push({ name: "home" });
    } else {
      if (
        CryptoJS.AES.decrypt(this.$route.query.e, "AES").toString(
          CryptoJS.enc.Utf8
        ) == null
      ) {
        this.$router.push({ name: "home" });
      }
    }
    document.title =
      localStorage.getItem("language") == "ar"
        ? "استرجاع كلمة المرور | نظام المدونة"
        : "Activeate Account | Password Recovery";
  },
  data() {
    return {
      user: {
        otp: "",
      },
      errors: {
        otp: [],
        message: "",
      },
      loading: false,
      resendLoading: false,
      countDown: 60,
      countDownMin: 1,
      resendActive: false,
    };
  },
  created() {
    this.countDownTimer();
  },
  methods: {
    countDownTimer() {
      if (this.countDown > 0) {
        if (this.countDown == 59) {
          this.countDownMin -= 1;
        }

        setTimeout(() => {
          this.countDown -= 1;
          this.countDownTimer();
        }, 1000);
      } else {
        this.resendActive = true;
      }
    },
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
      this.errors.otp = [];
      document.title =
        localStorage.getItem("language") == "ar"
          ? "استرجاع كلمة المرور | نظام المدونة"
          : "Activeate Account | Password Recovery";
    },

    Verify() {
      this.loading = true;
      axios
        .post(
          "http://localhost:8000/api/auth/forget-password-otp",

          {
            data: CryptoJS.AES.decrypt(this.$route.query.e, "AES").toString(
              CryptoJS.enc.Utf8
            ),
            otp: this.user.otp,
          },
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
          this.$router.push("/reset-password");
        })
        .catch((err) => {
          this.loading = false;
          this.errors = err?.response?.data?.errors;
          this.user.otp = "";
          this.$toast.open({
            message: err?.response?.data?.message,
            type: "error",
            position: "top",
            duration: 3000,
            pauseOnHover: true,
          });
        });
    },
    Resend() {
      this.resendLoading = true;
      axios
        .post(
          "http://localhost:8000/api/auth/forget-password-resend",

          this.user,
          {
            headers: {
              language: this.$store.state.language,
            },
          }
        )
        .then((res) => {
          this.resendLoading = false;
          message: res?.data?.message;
          this.countDown = 60;
          this.resendActive = false;
          this.countDownTimer();
          console.log(this.countDown);
        })
        .catch((err) => {
          this.resendLoading = false;
          this.countDown = 60;
          this.resendActive = false;
          this.countDownTimer();
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
  
  