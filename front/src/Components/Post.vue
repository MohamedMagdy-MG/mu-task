<template>
  <Image
    v-if="isShowImage === true"
    :image="this.post.Content?.image"
    :showImage="this.showImage"
  />
  <Delete
    v-if="isShowDelete === true"
    :id="this.post.id"
    :ShowDelete="this.ShowDelete"
    :getPosts="this.getPosts"
    :posts="this.posts"
    :ShowComment="this.ShowComment"
  />
  <Edit
    v-if="isShowEdit === true"
    :id="this.post.id"
    :getPosts="this.getPosts"
    :posts="this.posts"
    :ShowEdit="this.ShowEdit"
    :post="this.post"
  />
  <Comment
    v-if="isShowComment === true"
    :post="this.post"
    :ShowComment="this.ShowComment"
    :getPosts="this.getPosts"
    :Reaction="this.Reaction"
  />
  <div
    class="flex flex-col w-full px-5 py-3 bg-[#fff] shadow-sm rounded-md gap-4"
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
            <i
              class="pi pi-lock-open text-[12px] rounded-full"
              :class="this.$store.state.language === 'ar' ? '-scale-x-100' : ''"
            ></i>
          </div>
        </div>
      </RouterLink>
      <div
        class="flex items-center gap-2"
        v-show="this.post.User?.mine === true"
      >
        <button
          class="rounded-full"
          :class="this.isHoverEdit === true ? 'bg-[#F3F4F6] ' : ''"
          @mouseover="this.isHoverEdit = true"
          @mouseleave="this.isHoverEdit = false"
          @click="ShowEdit"
        >
          <i class="pi pi-pencil text-[14px] px-2 py-2"></i>
        </button>

        <button
          class="rounded-full"
          :class="this.isHoverDelete === true ? 'bg-[#F3F4F6] ' : ''"
          @mouseover="this.isHoverDelete = true"
          @mouseleave="this.isHoverDelete = false"
          @click="ShowDelete"
        >
          <i class="pi pi-trash text-[14px] px-2 py-2"></i>
        </button>
      </div>
    </div>
    <div
      :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
      class="flex flex-col items-flex-start justify-center"
    >
      <div
        class="flex text-wrap text-[14px] text-[#000] leading-relaxed font-bold"
        :dir="this.isRTL(this.post.Content?.title) == true ? 'rtl' : 'ltr'"
      >
        {{ this.post.Content?.title }}
      </div>
      <div
        class="flex text-wrap text-[14px] text-[#000] leading-relaxed"
        :dir="this.isRTL(this.post.Content?.body) == true ? 'rtl' : 'ltr'"
      >
        {{ this.post.Content?.body }}
      </div>
      <div
        v-show="this.post.Content?.image !== null"
        class="flex text-wrap text-[14px] text-[#000] leading-relaxed mt-2 relative"
        @mouseover="this.isViewImage = true"
        @mouseleave="this.isViewImage = false"
      >
        <img
          :src="this.post.Content?.image"
          class="rounded max-h-[400px] w-full object-cover"
        />
        <div
          class="flex justify-center items-center w-full h-full bg-[#0000007C] absolute top-0 right-0 left-0 bottom-0 rounded"
          style="cursor: pointer"
          v-show="isViewImage === true"
        >
          <button
            class="w-full h-full flex justify-center items-center"
            style="cursor: pointer"
            @click="showImage"
          >
            <i class="pi pi-eye text-[18px] text-white"></i>
          </button>
        </div>
      </div>
    </div>

    <div
      :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
      class="flex flex-col gap-1"
    >
      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="flex items-center justify-between"
      >
        <div>
          <div
            class="w-full flex items-center justify-center gap-3 py-2 px-3 rounded-full b text-[12px] font-bold"
          >
            <div
              v-show="this.post.LikeCount !== 0 && this.post.LoveCount !== 0"
              class="relative w-full flex items-center justify-center text-[10px]"
            >
              <i
                class="pi pi-heart-fill px-2 py-2 bg-[#9ED5CB] rounded-full text-[#fff]"
              ></i>
              <i
                class="pi pi-thumbs-up-fill absolute left-5 px-2 py-2 bg-[#9ED5CB] rounded-full text-[#fff] z-10"
                style="border: 3px solid #fff"
              ></i>
            </div>
            <div
              v-show="this.post.LikeCount !== 0 && this.post.LoveCount === 0"
              class="relative w-full flex items-center justify-center text-[10px]"
            >
              <i
                class="pi pi-thumbs-up-fill px-2 py-2 bg-[#9ED5CB] rounded-full text-[#fff]"
              ></i>
            </div>
            <div
              v-show="this.post.LikeCount === 0 && this.post.LoveCount !== 0"
              class="relative w-full flex items-center justify-center text-[10px]"
            >
              <i
                class="pi pi-heart-fill px-2 py-2 bg-[#9ED5CB] rounded-full text-[#fff]"
              ></i>
            </div>
            <p
              v-show="this.post.LikeCount !== 0 && this.post.LoveCount !== 0"
              class="font-bold text-[#000] mx-5 text-[15px]"
            >
              {{ this.post.LikeCount + this.post.LoveCount }}
            </p>
            <p
              v-show="this.post.LikeCount !== 0 && this.post.LoveCount === 0"
              class="font-bold text-[#000] mx-0 text-[15px]"
            >
              {{ this.post.LikeCount }}
            </p>
            <p
              v-show="this.post.LikeCount === 0 && this.post.LoveCount !== 0"
              class="font-bold text-[#000] mx-0 text-[15px]"
            >
              {{ this.post.LoveCount }}
            </p>
          </div>
        </div>
        <div
          class="flex justify-center items-center"
          v-show="this.post.CommentsCount !== 0"
        >
          <p>{{ this.post.CommentsCount }} {{ this.$t("comments") }}</p>
        </div>
      </div>
      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="h-[1px] bg-[#F3F4F6]"
      ></div>
      <div
        :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
        class="flex items-center"
      >
        <button
          class="w-full flex items-center justify-center gap-3 py-1 b text-[14px] font-bold relative"
          @mouseleave="this.isAction = false"
          @mouseover="this.isAction = true"
          :class="this.isAction == true ? 'bg-[#F3F4F6]' : ''"
        >
          <div
            class="flex justify-center items-center gap-3"
            v-show="this.post.isAuthReact === false"
          >
            <i class="pi pi-align-justify"></i>
            <p>{{ $t("Actions") }}</p>
          </div>
          <button
            @click="Reaction()"
            class="flex justify-center items-center gap-3 text-[#9ED5CB]"
            v-show="
              this.post.isAuthReact === true && this.post.AuthReact == 'like'
            "
          >
            <i class="pi pi-thumbs-up-fill"></i>
            <p>{{ $t("Like") }}</p>
          </button>
          <button
            @click="Reaction()"
            class="flex justify-center items-center gap-3 text-[#9ED5CB]"
            v-show="
              this.post.isAuthReact === true && this.post.AuthReact == 'love'
            "
          >
            <i class="pi pi-heart-fill"></i>
            <p>{{ $t("Love") }}</p>
          </button>
          <div
            v-show="this.isAction == true"
            style="box-shadow: 4px 2px 2px 2px #e2e2e2"
            class="gap-3 absolute flex bg-[#fff] px-4 py-2 rounded-full top-[-50px] justify-center items-center z-20"
            :class="
              this.$store.state.language === 'ar'
                ? 'right-[-10px]'
                : 'left-[-15px]'
            "
          >
            <button
              class="w-full flex items-center justify-center gap-3 py-2 px-3 rounded-full b text-[14px] font-bold"
              @mouseleave="this.isLike = false"
              @mouseover="this.isLike = true"
              :class="this.isLike == true ? 'bg-[#F3F4F6]' : ''"
              @click="this.Reaction('like')"
            >
              <i class="pi pi-thumbs-up-fill"></i>
              <p>{{ $t("Like") }}</p>
            </button>
            <div
              :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
              class="h-full py-2 border-l border-[#000]"
            ></div>

            <button
              class="w-full flex items-center justify-center gap-3 py-2 px-3 rounded-full b text-[14px] font-bold"
              @mouseleave="this.isLove = false"
              @mouseover="this.isLove = true"
              :class="this.isLove == true ? 'bg-[#F3F4F6]' : ''"
              @click="this.Reaction('love')"
            >
              <i class="pi pi-heart-fill"></i>
              <p>{{ $t("Love") }}</p>
            </button>
          </div>
        </button>

        <button
          class="w-full flex items-center justify-center gap-3 py-1 text-[14px] font-bold"
          @mouseleave="this.isComment = false"
          @mouseover="this.isComment = true"
          :class="this.isComment == true ? 'bg-[#F3F4F6]' : ''"
          @click="ShowComment"
        >
          <i class="pi pi-comment"></i>
          <p>{{ $t("Comment") }}</p>
        </button>
        <button
          class="w-full flex items-center justify-center gap-3 py-1 text-[14px] font-bold relative"
          @mouseleave="this.isShare = false"
          @mouseover="this.isShare = true"
          :class="this.isShare == true ? 'bg-[#F3F4F6]' : ''"
          @click="ShowShare"
        >
          <i class="pi pi-share-alt"></i>
          <p>{{ $t("Share") }}</p>
          <div
            class="absolute flex flex-col items-flex-start justify-center mx-3 my-3 top-8 bg-[#fff] w-[200px] rounded"
            style="box-shadow: 2px 1px 1px 1px #e2e2e2"
            v-if="this.isShowShare == true"
          >
            <button
              class="flex items-center justify-flex-start px-4 py-3 gap-3 rounded"
              @mouseleave="this.isFeedback = false"
              @mouseover="this.isFeedback = true"
              :class="this.isFeedback == true ? 'bg-[#F3F4F6]' : ''"
            >
              <i class="pi pi-pencil"></i>
              <p>{{ $t("Share To Feedback") }}</p>
            </button>
            <button
              class="flex items-center justify-flex-start px-4 py-3 gap-3 rounded"
              @mouseleave="this.isWhatsapp = false"
              @mouseover="this.isWhatsapp = true"
              :class="this.isWhatsapp == true ? 'bg-[#F3F4F6]' : ''"
            >
              <i class="pi pi-whatsapp"></i>
              <p>{{ $t("Share To Whatsapp") }}</p>
            </button>
          </div>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Image from "./Image.vue";
import Delete from "./Delete.vue";
import Edit from "./Edit.vue";
import Comment from "./Comments.vue";
import axios from "axios";
export default {
  components: {
    Image,
    Delete,
    Comment,
    Edit,
  },
  data() {
    return {
      isAction: false,
      isLike: false,
      isLove: false,
      isComment: false,
      isShare: false,
      isViewImage: false,
      isShowImage: false,
      isHoverDelete: false,
      isShowDelete: false,
      isHoverEdit: false,
      isShowEdit: false,
      isShowShare: false,
      isFeedback: false,
      isWhatsapp: false,
      isShowComment: false,
    };
  },
  props: ["post", "getPosts", "posts"],

  methods: {
    isRTL(s) {
      var ltrChars =
          "A-Za-z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02B8\u0300-\u0590\u0800-\u1FFF" +
          "\u2C00-\uFB1C\uFDFE-\uFE6F\uFEFD-\uFFFF",
        rtlChars = "\u0591-\u07FF\uFB1D-\uFDFD\uFE70-\uFEFC",
        rtlDirCheck = new RegExp("^[^" + ltrChars + "]*[" + rtlChars + "]");

      return rtlDirCheck.test(s);
    },
    showImage() {
      this.isShowImage = !this.isShowImage;
    },
    ShowDelete() {
      this.isShowDelete = !this.isShowDelete;
    },
    ShowEdit() {
      this.isShowEdit = !this.isShowEdit;
    },
    ShowShare() {
      this.isShowShare = !this.isShowShare;
    },
    ShowComment() {
      this.isShowComment = !this.isShowComment;
    },
    Reaction(data = null) {
      if (data === "love") {
        if (this.post.isAuthReact === false && data === "love") {
          this.post.LoveCount = this.post.LoveCount + 1;
        }
        if (
          this.post.isAuthReact === true &&
          this.post.AuthReact === "like" &&
          data === "love"
        ) {
          this.post.LikeCount = this.post.LikeCount - 1;
          this.post.LoveCount = this.post.LoveCount + 1;
        }

        this.post.isAuthReact = true;
        this.post.AuthReact = data;
      } else if (data === "like") {
        if (this.post.isAuthReact === false && data === "like") {
          this.post.LikeCount = this.post.LikeCount + 1;
        }
        if (
          this.post.isAuthReact === true &&
          this.post.AuthReact === "love" &&
          data === "like"
        ) {
          this.post.LoveCount = this.post.LoveCount - 1;
          this.post.LikeCount = this.post.LikeCount + 1;
        }
        this.post.isAuthReact = true;
        this.post.AuthReact = data;
      } else {
        if (this.post.AuthReact === "love") {
          this.post.LoveCount !== 0
            ? (this.post.LoveCount = this.post.LoveCount - 1)
            : (this.post.LoveCount = this.post.LoveCount);
        } else if (this.post.AuthReact === "like") {
          this.post.LikeCount = this.post.LikeCount - 1;
        }
        this.post.isAuthReact = false;
      }

      axios
        .post(
          "http://localhost:8000/api/site/post/react",
          {
            id: this.post.id,
            reaction: data,
          },
          {
            headers: {
              language: this.$store.state.language,
              authorization: `bearer ${this.$store.state.user.token}`,
            },
          }
        )
        .then((res) => {
          this.loading = false;
          this.ShowComment = this.ShowComment;
          this.getPosts();
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
