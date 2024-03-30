<template  >
  <Header_AR
    v-if="this.$store.state.language === 'ar'"
    :getPosts="getPosts"
    :setSearch="this.setSearch"
    :setPage="this.setPage"
    :setPosts="this.setPosts"
  />
  <Header_EN
    v-else
    :getPosts="getPosts"
    :setSearch="this.setSearch"
    :setPage="setPage"
    :setPosts="this.setPosts"
  />
  <div class="flex w-full pt-4 mt-[70px]">
    <div class="flex-1"></div>
    <div class="flex-1">
      <div class="flex flex-col w-full">
        <Create :getPosts="this.getPosts" />
      </div>
    </div>
    <div class="flex-1"></div>
  </div>
  <div class="flex w-full px-2 py-2">
    <div class="flex-1"></div>
    <div class="flex-1">
      <div
        class="flex flex-col w-full mb-2"
        v-for="post in this.posts"
        :key="post.id"
      >
        <Post
          :post="post"
          :loading="loading"
          :getPosts="this.getPosts"
          :setPosts="this.setPosts"
        />
      </div>
    </div>
    <div class="flex-1"></div>
  </div>
  <div class="flex w-full" v-if="this.loading === true">
    <div class="flex-1"></div>
    <div class="flex-1">
      <div class="flex justify-center items-center my-2">
        <i class="pi pi-spin pi-spinner text-[#000] text-[20px]"></i>
      </div>
    </div>
    <div class="flex-1"></div>
  </div>
</template>


<script>
import Header_EN from "../Layouts/en/Header";
import Header_AR from "../Layouts/ar/Header";
import Post from "../Components/Post.vue";
import Create from "../Components/Create.vue";
import axios from "axios";
export default {
  name: "home-view",
  components: {
    Header_EN,
    Header_AR,
    Post,
    Create,
  },
  data() {
    return {
      posts: [],
      loading: true,
      search: null,
      url: null,
      page: 1,
      totalPages: 1,
    };
  },
  mounted() {
    document.title =
      localStorage.getItem("language") == "ar"
        ? "الصفحة الرئيسية | نظام المدونة"
        : "Home Page | Blog System";
    this.getPosts();
    window.addEventListener("scroll", this.handleScroll);
  },
  beforeDestroy() {
    this.$refs.scrollContainer.removeEventListener("scroll", this.handleScroll);
  },

  updated() {
    document.title =
      localStorage.getItem("language") == "ar"
        ? "الصفحة الرئيسية | نظام المدونة"
        : "Home Page | Blog System";
  },

  methods: {
    getPosts() {
      this.page = 1;
      if (this.search === null) {
        this.url = `http://localhost:8000/api/site/post?page=${this.page}`;
      } else {
        this.url = `http://localhost:8000/api/site/post?page=${this.page}&search=${this.search}`;
      }
      this.loading = true;
      axios
        .get(this.url, {
          headers: {
            language: this.$store.state.language,
            authorization: `bearer ${this.$store.state.user.token}`,
          },
        })
        .then((res) => {
          this.loading = false;
          this.totalPages = res.data.data?.Pagination?.totalPages;
          this.posts = res.data.data?.Posts;
        })
        .catch((err) => {
          this.$toast.open({
            message: "Failed To Load Posts Data",
            type: "error",
            position: "top",
            duration: 3000,
            pauseOnHover: true,
          });
        });
    },
    setSearch(s) {
      this.search = s;
    },
    setPage(p) {
      this.page = p;
    },
    setPosts(p) {
      this.posts = p;
    },
    handleScroll() {
      if (this.page <= this.totalPages) {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
          this.page = this.page + 1;
          if (this.page <= this.totalPages) {
            if (this.search === null) {
              this.url = `http://localhost:8000/api/site/post?page=${this.page}`;
            } else {
              this.url = `http://localhost:8000/api/site/post?page=${this.page}&search=${this.search}`;
            }
            this.loading = true;
            axios
              .get(this.url, {
                headers: {
                  language: this.$store.state.language,
                  authorization: `bearer ${this.$store.state.user.token}`,
                },
              })
              .then((res) => {
                this.loading = false;
                this.totalPages = res.data.data?.Pagination?.totalPages;
                this.posts = this.posts.concat(res?.data?.data?.Posts);
              })
              .catch((err) => {
                this.$toast.open({
                  message: "Failed To Load Posts Data",
                  type: "error",
                  position: "top",
                  duration: 3000,
                  pauseOnHover: true,
                });
              });
          }
        }
      }
    },
  },
};
</script>
