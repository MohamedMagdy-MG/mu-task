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
    :ShowComment="this.ShowComment"
    :getPosts="this.getPosts"
  />
  <Edit
    v-if="isShowEdit === true"
    :id="this.post.id"
    :getPosts="this.getPosts"
    :posts="this.posts"
    :ShowEdit="this.ShowEdit"
    :post="this.post"
  />
  <div
    class="fixed z-40 w-full h-full top-0 right-0 left-0 bottom-0 flex justify-center items-center bg-[#000000de]"
  >
    <div class="w-[60%] flex justify-center items-center flex-col rounded-lg">
      <div
        class="flex flex-col w-full px-5 py-3 bg-[#fff] shadow-sm gap-4 overflow-y-auto max-h-[500px] relative scroll-m-0"
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
                <p>{{ this.post.Content.time }}</p>
                <i
                  class="pi pi-lock-open text-[12px] rounded-full"
                  :class="
                    this.$store.state.language === 'ar' ? '-scale-x-100' : ''
                  "
                ></i>
              </div>
            </div>
          </RouterLink>
          <div class="flex items-center gap-2">
            <button
              class="rounded-full"
              :class="this.isHoverEdit === true ? 'bg-[#F3F4F6] ' : ''"
              @mouseover="this.isHoverEdit = true"
              @mouseleave="this.isHoverEdit = false"
              @click="ShowEdit"
              v-show="this.post.User.mine === true"
            >
              <i class="pi pi-pencil text-[14px] px-2 py-2"></i>
            </button>
            <button
              class="rounded-full"
              :class="this.isHoverDelete === true ? 'bg-[#F3F4F6] ' : ''"
              @mouseover="this.isHoverDelete = true"
              @mouseleave="this.isHoverDelete = false"
              @click="ShowDelete"
              v-show="this.post.User.mine === true"
            >
              <i class="pi pi-trash text-[14px] px-2 py-2"></i>
            </button>

            <button
              class="rounded-full"
              :class="this.isHoverClose === true ? 'bg-[#F3F4F6] ' : ''"
              @mouseover="this.isHoverClose = true"
              @mouseleave="this.isHoverClose = false"
              @click="this.ShowComment"
            >
              <i class="pi pi-times text-[14px] px-2 py-2"></i>
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
          class="rounded max-h-[200px] w-full object-cover"
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
                  v-show="
                    this.post.LikeCount !== 0 && this.post.LoveCount !== 0
                  "
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
                  v-show="
                    this.post.LikeCount !== 0 && this.post.LoveCount === 0
                  "
                  class="relative w-full flex items-center justify-center text-[10px]"
                >
                  <i
                    class="pi pi-thumbs-up-fill px-2 py-2 bg-[#9ED5CB] rounded-full text-[#fff]"
                  ></i>
                </div>
                <div
                  v-show="
                    this.post.LikeCount === 0 && this.post.LoveCount !== 0
                  "
                  class="relative w-full flex items-center justify-center text-[10px]"
                >
                  <i
                    class="pi pi-heart-fill px-2 py-2 bg-[#9ED5CB] rounded-full text-[#fff]"
                  ></i>
                </div>
                <p
                  v-show="
                    this.post.LikeCount !== 0 && this.post.LoveCount !== 0
                  "
                  class="font-bold text-[#000] mx-5 text-[15px]"
                >
                  {{ this.post.LikeCount + this.post.LoveCount }}
                </p>
                <p
                  v-show="
                    this.post.LikeCount !== 0 && this.post.LoveCount === 0
                  "
                  class="font-bold text-[#000] mx-0 text-[15px]"
                >
                  {{ this.post.LikeCount }}
                </p>
                <p
                  v-show="
                    this.post.LikeCount === 0 && this.post.LoveCount !== 0
                  "
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
              <button
                class="flex justify-center items-center gap-3"
                v-show="this.post.isAuthReact === false"
                @click="Reaction()"
              >
                <i class="pi pi-align-justify"></i>
                <p>{{ $t("Actions") }}</p>
              </button>
              <button
                @click="Reaction()"
                class="flex justify-center items-center gap-3 text-[#9ED5CB]"
                v-show="
                  this.post.isAuthReact === true &&
                  this.post.AuthReact == 'like'
                "
              >
                <i class="pi pi-thumbs-up-fill"></i>
                <p>{{ $t("Like") }}</p>
              </button>
              <button
                @click="Reaction()"
                class="flex justify-center items-center gap-3 text-[#9ED5CB]"
                v-show="
                  this.post.isAuthReact === true &&
                  this.post.AuthReact == 'love'
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
                    ? 'right-[25px]'
                    : 'left-[30px]'
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
            <div
              v-if="this.selectedComments.length > 0"
              class="w-full flex items-center justify-center gap-3 py-1 b text-[14px] font-bold"
            >
              <div
                v-if="this.DeleteCommentsLoading === true"
                class="w-full flex items-center justify-center gap-3 py-1 b text-[14px] font-bold"
                @mouseleave="this.isDeleteComments = false"
                @mouseover="this.isDeleteComments = true"
                :class="this.isDeleteComments == true ? 'bg-[#F3F4F6]' : ''"
              >
                <i class="pi pi-spin pi-spinner text-[16px] text-[#000]"></i>
              </div>
              <button
                v-else
                @click="this.DeleteComments()"
                class="w-full flex items-center justify-center gap-3 py-1 b text-[14px] font-bold"
                @mouseleave="this.isDeleteComments = false"
                @mouseover="this.isDeleteComments = true"
                :class="this.isDeleteComments == true ? 'bg-[#F3F4F6]' : ''"
              >
                {{ $t("Delete") }} ({{ this.selectedComments.length }})
              </button>
            </div>
          </div>
        </div>
        <div
          :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
          class="h-[1px] bg-[#F3F4F6]"
        ></div>
        <div
          :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
          v-for="comment in comments"
          :key="comment.id"
        >
          <Comment
            :comment="comment"
            :selectedComments="this.selectedComments"
            :post="this.post"
          />
        </div>
        <button
          v-show="this.totalPages > this.page && this.count == 10"
          class="text-[12px]"
          @click="this.loadMore()"
        >
          <i
            class="pi pi-spin pi-spinner text-[20px]"
            v-if="this.loading == true"
          ></i>
          <p v-else>{{ $t("Load More") }}</p>
        </button>
      </div>
      <div
        class="flex items-center gap-2 bottom-0 top-0 left-0 w-full bg-[#fff] px-3 py-3"
      >
        <div
          class="w-[44px] h-[44px] rounded-full text-[#000] relative flex justify-center items-center bg-[#fff] border border-[#9ED5CB] px-1 py-1"
        >
          <img
            :src="
              this.$store?.state?.user?.image
                ? this.$store?.state?.user?.image
                : require('@/assets/logo.png')
            "
            class="w-[30px] rounded-full object-cover"
          />
        </div>

        <div
          :dir="this.$store.state.language === 'ar' ? 'rtl' : 'ltr'"
          class="relative flex items-center justify-start w-full"
        >
          <input
            type="text"
            :placeholder="$t('Write a comment...')"
            class="bg-[#F3F4F6] rounded-full py-2 focus:border-teal-500 focus:outline-none pl-4 pr-[85px] w-full text-[#445045] text-wrap text-[12px]"
            contenteditable="true"
            v-model="this.comment"
          />
          <button
            @click="this.addComment()"
            class="absolute inset-y-0 right-0 flex items-center justify-center bg-[#9ED5CB] w-[80px] rounded-full px-4 font-bold text-[14px]"
          >
            {{ $t("Send") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Comment from "./Comment.vue";
import Delete from "./DeleteFromComment.vue";
import Edit from "./EditFromComment.vue";
import Image from "./Image.vue";


export default {
  components: {
    Comment,
    Delete,
    Edit,
    Image,
  },
  data() {
    return {
      isAction: false,
      isLike: false,
      isLove: false,
      isComment: false,
      isHoverDelete: false,
      isShare: false,
      isShowShare: false,
      isHoverEdit: false,
      isShowEdit: false,
      isFeedback: false,
      isShowDelete: false,
      isWhatsapp: false,
      isHoverClose: false,
      comments: [],
      comment: "",
      page: 1,
      totalPages: 1,
      count: 0,
      loading: false,
      selectedComments: [],
      isDeleteComments: false,
      DeleteCommentsLoading: false,
      isViewImage: false,
      isShowImage: false,
    };
  },
  props: ["post", "ShowComment", "getPosts", "Reaction"],
  created() {
    this.getComments();
  },
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
    ShowShare() {
      this.isShowShare = !this.isShowShare;
    },
    ShowDelete() {
      this.isShowDelete = !this.isShowDelete;
    },
    ShowEdit() {
      this.isShowEdit = !this.isShowEdit;
    },
    getComments() {
      axios
        .get(
          `http://localhost:8000/api/site/comment?id=${this.post?.id}&page=${this.page}`,
          {
            headers: {
              language: this.$store.state.language,
              authorization: `bearer ${this.$store.state.user.token}`,
            },
          }
        )
        .then((res) => {
          this.comments = res?.data?.data?.Comments;
          this.totalPages = res.data.data?.Pagination?.totalPages;
          this.count = res.data.data?.Pagination?.count;
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
    addComment() {
      axios
        .post(
          `http://localhost:8000/api/site/comment`,
          {
            post_id: this.post.id,
            comment: this.comment,
          },
          {
            headers: {
              language: this.$store.state.language,
              authorization: `bearer ${this.$store.state.user.token}`,
            },
          }
        )
        .then((res) => {
          this.comment = "";
          this.post.CommentsCount += 1;
          this.getComments();
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
    loadMore() {
      this.page = this.page + 1;
      this.loading = true;
      axios
        .get(
          `http://localhost:8000/api/site/comment?id=${this.post?.id}&page=${this.page}`,
          {
            headers: {
              language: this.$store.state.language,
              authorization: `bearer ${this.$store.state.user.token}`,
            },
          }
        )
        .then((res) => {
          this.loading = false;
          this.comments.push(...res?.data?.data?.Comments);
          this.totalPages = res.data.data?.Pagination?.totalPages;
          this.count = res.data.data?.Pagination?.count;
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

    DeleteComments() {
      this.DeleteCommentsLoading = true;
      axios
        .delete(
          `http://localhost:8000/api/site/comment/selected`,

          {
            data: {
              post_id: this.post.id,
              ids: this.selectedComments,
            },
            headers: {
              language: this.$store.state.language,
              authorization: `bearer ${this.$store.state.user.token}`,
            },
          }
        )
        .then((res) => {
          this.getComments();
          this.selectedComments = [];
          this.DeleteCommentsLoading = false;
          this.post.CommentsCount -= this.selectedComments.length;
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
