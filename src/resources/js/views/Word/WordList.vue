<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useStore } from 'vuex';
import DataCount from '../../components/DataCount';
import InvalidFeedback from '../../components/InvalidFeedback';
import LoadingOverlay from '../../components/LoadingOverlay';
import Pagination from '../../components/Pagination';
import SortIcon from '../../components/SortIcon';
import { useDebounce } from '../../functions/useDebounce';

const store = useStore();

onMounted(async () => {
  setIsLoading(true);
  await store.dispatch('question/get');
  setIsLoading(false);
});

const questions = computed(() => store.getters['question/data']);
const params = computed(() => store.getters['question/params']);
const alphabets = computed(() => store.getters['consts/alphabets']);
const invalidFeedback = computed(
  () => store.getters['question/invalidFeedback']
);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const isInvalid = computed(() => store.getters['question/isInvalid']);
const meta = computed(() => store.getters['question/meta']);
const editable = ref([]);
const isLoading = computed(() => store.getters['loading/isLoading']);
const setIsLoading = (bool) => store.commit('loading/setIsLoading', bool);
const fetchData = () => {
  store.dispatch('question/get', params.value);
};
const debounceSearch = useDebounce(() => {
  params.value.filter = '';
  fetchData();
});
const setFilter = (alphabet) => {
  params.value.keyword = '';
  editable.value = [];
  params.value.filter = alphabet;
  fetchData();
};
const resetParams = () => {
  store.commit('question/resetParams');
  fetchData();
};
const onChangeSort = (label) => {
  editable.value = false;
  if (params.value.column === label) {
    params.value.is_asc = !params.value.is_asc;
  } else {
    params.value.column = label;
    params.value.is_asc = true;
  }
  // ソートするときにページを1に戻す
  params.value.page = 1;
  fetchData();
};
const onEdit = (index) => {
  editable.value = [];
  editable.value[index] = true;
  store.commit('question/setErrors', {});
};
const updateQuestion = async (question, index) => {
  setIsLoading(true);
  await store.dispatch('question/update', question);
  setIsLoading(false);
  if (hasErrors.value) return;
  editable.value[index] = false;
  fetchData();
};
const deleteQuestion = async (id) => {
  if (confirm('削除しますか？')) {
    setIsLoading(true);
    await store.dispatch('question/delete', id);
    setIsLoading(false);
    if (hasErrors.value) return;
    fetchData(currentAlphabet.value);
    editable.value = [];
  }
};
const cancel = () => {
  editable.value = [];
};
const changePage = (page = null) => {
  if (page) {
    params.value.page = page;
    fetchData();
  }
};
onUnmounted(() => {
  store.commit('question/setErrors', {});
});
</script>

<template>
  <LoadingOverlay :isLoading="isLoading" />
  <div class="container">
    <div class="row header">
      <div class="title">登録済み単語リスト</div>
      <div class="search-input-wrapper">
        <input
          v-model="params.keyword"
          @input="debounceSearch()"
          placeholder="キーワード検索"
          maxlength="50"
          type="search"
        />
      </div>
      <button @click="resetParams()">リセット</button>
    </div>
    <div class="wrap">
      <div class="row" v-for="alphabet in alphabets" :key="alphabet">
        <span v-if="index">/</span>
        <div
          class="index-item"
          :class="alphabet === params.filter && 'current-alphabet'"
          @click="setFilter(alphabet)"
        >
          {{ alphabet }}
        </div>
      </div>
    </div>
    <DataCount v-if="meta" :meta="meta" />
    <div class="row list-header">
      <div class="row" @click="onChangeSort('word')">
        <div class="list-column-title">単語</div>
        <SortIcon
          :is-asc="params?.is_asc"
          :active="params?.column === 'word'"
        />
      </div>
      <div class="row" @click="onChangeSort('correct_answer')">
        <div class="list-column-title">正解</div>
        <SortIcon
          :is-asc="params?.is_asc"
          :active="params?.column === 'correct_answer'"
        />
      </div>
    </div>
    <div v-if="!isLoading && !questions.length">
      検索に一致する単語はありませんでした。
    </div>
    <div v-else class="list-body">
      <div
        v-for="question in questions"
        :key="question.id"
        class="row list-row"
      >
        <input
          v-if="editable[index]"
          v-model="question.word"
          :class="isInvalid('word')"
          maxlength="255"
          type="text"
        />
        <div v-else @click="onEdit(index)" class="list-item">
          {{ question.word }}
        </div>
        <input
          v-if="editable[index]"
          v-model="question.correct_answer"
          :class="isInvalid('correct_answer')"
          maxlength="255"
          type="text"
        />
        <div v-else @click="onEdit(index)" class="list-item">
          {{ question.correct_answer }}
        </div>
        <button
          v-if="editable[index]"
          @click="updateQuestion(question, index)"
          title="更新"
        >
          <font-awesome-icon class="check-icon" icon="check" />
        </button>
        <div v-else @click="onEdit(index)"></div>
        <button
          v-if="editable[index]"
          class="cancel"
          @click="cancel()"
          title="キャンセル"
        >
          <font-awesome-icon class="xmark-icon" icon="xmark" />
        </button>
        <div v-else @click="onEdit(index)"></div>
        <button
          v-if="editable[index]"
          class="delete"
          @click="deleteQuestion(question.id)"
          title="削除"
        >
          <font-awesome-icon class="minus-icon" icon="minus" />
        </button>
        <div v-else @click="onEdit(index)"></div>
        <InvalidFeedback
          v-if="editable[index]"
          :errors="invalidFeedback('word')"
        />
        <InvalidFeedback
          v-if="editable[index]"
          :errors="invalidFeedback('correct_answer')"
        />
      </div>
    </div>
  </div>
  <Pagination :links="meta.links" @change="changePage" />
</template>
