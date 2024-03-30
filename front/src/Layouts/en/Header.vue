<template>
  <div
    class="w-full bg-[#fff] h-[70px] flex items-center justify-between fixed top-0 z-30 shadow-sm"
  >
    <div class="flex items-center justify-start">
      <RouterLink to="/" class="flex justify-center">
        <img width="90" src="@/assets/logo.png" class="rounded-full" />
      </RouterLink>
      <div class="relative flex items-center justify-start full">
        <input
          type="text"
          placeholder="Search Blog"
          v-model="this.searchText"
          class="bg-[#F3F4F6] rounded-full py-2 focus:border-teal-500 focus:outline-none pl-12 w-[300px] text-[#445045]"
        />
        <button
          @click="this.getSearchData()"
          class="absolute inset-y-0 left-0 flex items-center justify-center bg-[#9ED5CB] w-[40px] rounded-full"
        >
          <svg
            class="w-5 h-full text-white"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m4-5a8 8 0 11-16 0 8 8 0 0116 0z"
            ></path>
          </svg>
        </button>
      </div>
    </div>
    <div class="flex items-center justify-end px-3 gap-2">
      <button
        class="rounded-full text-[#fff] relative flex justify-center items-center bg-[#fff]"
        @click="changeLanguage()"
      >
        <div class="object-cover">
          <img
            src="@/assets/flags/ar.png"
            alt="Flag"
            class="rounded-full object-cover h-11 w-11"
          />
        </div>
      </button>
      <div class="bg-[#9ED5CB] px-3 py-3 rounded-full text-[#fff]">
        <svg
          viewBox="0 0 24 24"
          width="20"
          height="20"
          fill="currentColor"
          class="x19dipnz x1lliihq x1k90msu x2h7rmj x1qfuztq"
          style="--color: var(--primary-icon)"
        >
          <path
            d="M12 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm8 0a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm8 0a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm8 16a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm8 0a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 17a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"
          ></path>
        </svg>
      </div>
      <div class="bg-[#9ED5CB] px-3 py-3 rounded-full text-[#fff]">
        <svg
          viewBox="0 0 12 13"
          width="20"
          height="20"
          fill="currentColor"
          class="x19dipnz x1lliihq x1k90msu x2h7rmj x1qfuztq"
          style="--color: var(--primary-icon)"
        >
          <g fill-rule="evenodd" transform="translate(-450 -1073)">
            <path
              d="m459.603 1077.948-1.762 2.851a.89.89 0 0 1-1.302.245l-1.402-1.072a.354.354 0 0 0-.433.001l-1.893 1.465c-.253.196-.583-.112-.414-.386l1.763-2.851a.89.89 0 0 1 1.301-.245l1.402 1.072a.354.354 0 0 0 .434-.001l1.893-1.465c.253-.196.582.112.413.386M456 1073.5c-3.38 0-6 2.476-6 5.82 0 1.75.717 3.26 1.884 4.305.099.087.158.21.162.342l.032 1.067a.48.48 0 0 0 .674.425l1.191-.526a.473.473 0 0 1 .32-.024c.548.151 1.13.231 1.737.231 3.38 0 6-2.476 6-5.82 0-3.344-2.62-5.82-6-5.82"
            ></path>
          </g>
        </svg>
      </div>
      <div class="bg-[#9ED5CB] px-3 py-3 rounded-full text-[#fff] relative">
        <svg
          viewBox="0 0 24 24"
          width="20"
          height="20"
          fill="currentColor"
          class="x19dipnz x1lliihq x1k90msu x2h7rmj x1qfuztq"
          style="--color: var(--primary-icon)"
        >
          <path
            d="M3 9.5a9 9 0 1 1 18 0v2.927c0 1.69.475 3.345 1.37 4.778a1.5 1.5 0 0 1-1.272 2.295h-4.625a4.5 4.5 0 0 1-8.946 0H2.902a1.5 1.5 0 0 1-1.272-2.295A9.01 9.01 0 0 0 3 12.43V9.5zm6.55 10a2.5 2.5 0 0 0 4.9 0h-4.9z"
          ></path>
        </svg>
        <div
          class="absolute inset-y-0 right-0 top-[-5px] flex items-center justify-center bg-[#545F55] h-[14px] w-[14px] rounded-full px-2 py-2"
        >
          <span class="text-[12px]">3</span>
        </div>
      </div>
      <button
        @click="this.setting"
        class="w-[44px] h-[44px] rounded-full text-[#fff] relative flex justify-center items-center bg-[#fff] border border-[#9ED5CB] px-1 py-1"
      >
        <img src="@/assets/logo.png" />
        <div
          class="absolute right-0 bottom-0 flex items-center justify-center bg-[#545F55] h-[14px] w-[14px] rounded-full px-1 py-1 text-[#fff]"
        >
          <svg
            viewBox="0 0 16 16"
            width="12"
            height="12"
            fill="currentColor"
            class="x19dipnz x1lliihq x1k90msu x2h7rmj x1qfuztq"
            style="--color: var(--primary-icon)"
          >
            <g fill-rule="evenodd" transform="translate(-448 -544)">
              <path
                fill-rule="nonzero"
                d="M452.707 549.293a1 1 0 0 0-1.414 1.414l4 4a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L456 552.586l-3.293-3.293z"
              ></path>
            </g>
          </svg>
        </div>
      </button>
    </div>
  </div>
  <div
    class="w-[200px] flex flex-col items-center justify-center absolute right-[10px] top-[60px] rounded bg-[#fff] shadow-2xl z-99999"
    v-show="this.showSetting == true"
    ref="profileMenu"
  >
    <div class="flex flex-col items-center justify-flex-start py-4 w-full">
      <RouterLink
        to="/"
        class="flex items-center justify-between py-4 w-full hover:bg-[#F3F4F6]"
      >
        <div class="flex items-center gap-2 px-2">
          <i
            class="pi pi-user text-[14px] px-2 py-2 text-[#fff] bg-[#445045] rounded-full"
          ></i>
          <p class="font-bold text-[16px]">Profile</p>
        </div>
        <div class="flex items-center px-2">
          <i
            class="pi pi-arrow-right text-[16px] text-[#445045] rounded-full"
          ></i>
        </div>
      </RouterLink>
      <hr class="w-full bg-[#445045]" />
      <button
        @click="logoutUser"
        class="flex items-center justify-between py-4 w-full hover:bg-[#F3F4F6]"
      >
        <div class="flex items-center gap-2 px-2">
          <i
            class="pi pi-power-off text-[14px] px-2 py-2 text-[#fff] bg-[#445045] rounded-full"
          ></i>
          <p class="font-bold text-[16px]">Logout</p>
        </div>
        <div class="flex items-center px-2">
          <i
            class="pi pi-arrow-right text-[16px] text-[#445045] rounded-full"
          ></i>
        </div>
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      showSetting: false,
      searchText: null,
    };
  },
  props: ["getPosts", "setSearch", "setPage"],
  methods: {
    setting() {
      this.showSetting = !this.showSetting;
    },
    logoutUser() {
      axios
        .post(
          "http://localhost:8000/api/site/logout",

          this.user,
          {
            headers: {
              language: this.$store.state.language,
              authorization: `bearer ${this.$store.state.user.token}`,
            },
          }
        )
        .then((res) => {
          this.$router.push("/login");
          this.$store.dispatch("resetUser");
        })
        .catch((err) => {
          this.$toast.open({
            message: err?.response?.data?.message,
            type: "error",
            position: "top",
            duration: 3000,
            pauseOnHover: true,
          });
        });
    },

    getLanguageText() {
      return this.$store.state.language === "en" ? "ar" : "en";
    },
    changeLanguage() {
      const newLanguage = this.$store.state.language === "en" ? "ar" : "en";
      this.$store.commit("SET_LANGUAGE", newLanguage);
      this.$i18n.locale = newLanguage;
      this.getPosts();
    },
    getSearchData() {
      this.setPage(1);
      this.setSearch(this.searchText);
      this.getPosts();
    },
  },
};
</script>

