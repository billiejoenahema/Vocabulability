<script setup>
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../../components/InvalidFeedback';
import LoadingOverlay from '../../components/LoadingOverlay';
import Navigation from '../../components/Navigation';
import Toast from '../../components/Toast';
import { ALPHABETS, DELETE_CONFIRM, NO_MATCH } from '../../const/const';
import { useDebounce } from '../../functions/useDebounce';

const store = useStore();

onMounted(async () => {
  setIsLoading(true);
  await store.dispatch('question/get');
  setIsLoading(false);
});

store.dispatch('question/get');
const questions = computed(() => store.getters['question/data']);
const invalidFeedback = computed(
  () => store.getters['question/invalidFeedback']
);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const isInvalid = computed(() => store.getters['question/isInvalid']);
const editable = ref([]);
const keyword = ref('');
const currentAlphabet = ref('');
const isLoading = computed(() => store.getters['loading/isLoading']);
const setIsLoading = (bool) => store.commit('loading/setIsLoading', bool);

const debounceSearch = useDebounce(() => {
  currentAlphabet.value = '';
  store.dispatch('question/get', { keyword: keyword.value });
});
const fetchData = (alphabet) => {
  store.dispatch('question/get', { filter: alphabet });
};
const filter = (alphabet) => {
  keyword.value = '';
  editable.value = [];
  currentAlphabet.value = alphabet;
  fetchData(alphabet);
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
  if (hasErrors.value) {
    return;
  }
  editable.value[index] = false;
  fetchData(currentAlphabet.value);
};
const deleteQuestion = async (id) => {
  if (confirm(DELETE_CONFIRM)) {
    setIsLoading(true);
    await store.dispatch('question/delete', id);
    setIsLoading(false);
    if (hasErrors.value) return;
    fetchData(currentAlphabet.value);
    editable.value = [];
  }
};
const cancel = (index) => {
  editable.value[index] = false;
};
</script>

<template>
  <LoadingOverlay :isLoading="isLoading" />
  <Toast />
  <Navigation />
  <div class="word-list">
    <div class="row header">
      <div class="title">登録済み単語リスト</div>
      <div class="search-input-wrapper">
        <input
          v-model="keyword"
          @input="debounceSearch()"
          placeholder="キーワード検索"
        />
      </div>
    </div>
    <div class="wrap">
      <div class="row" v-for="(alphabet, index) in ALPHABETS" :key="index">
        <span v-if="index">/</span>
        <div
          class="index-item"
          :class="alphabet === currentAlphabet && 'current-alphabet'"
          @click="filter(alphabet)"
        >
          {{ alphabet }}
        </div>
      </div>
    </div>
    <div class="row list-header">
      <div class="list-column-title">単語</div>
      <div class="list-column-title">正解</div>
    </div>
    <div v-if="!isLoading && !questions.length">{{ NO_MATCH }}</div>
    <div v-else class="list-body">
      <div
        v-for="(question, index) in questions"
        :key="question.id"
        class="row list-row"
      >
        <input
          v-if="editable[index]"
          v-model="question.word"
          :class="isInvalid('word')"
          maxlength="255"
        />
        <div v-else @click="onEdit(index)" class="list-item">
          {{ question.word }}
        </div>
        <input
          v-if="editable[index]"
          v-model="question.correct_answer"
          :class="isInvalid('correct_answer')"
          maxlength="255"
        />
        <div v-else @click="onEdit(index)" class="list-item">
          {{ question.correct_answer }}
        </div>
        <button v-if="editable[index]" @click="updateQuestion(question, index)">
          更新
        </button>
        <div v-else @click="onEdit(index)"></div>
        <button v-if="editable[index]" class="cancel" @click="cancel(index)">
          キャンセル
        </button>
        <div v-else @click="onEdit(index)"></div>
        <button
          v-if="editable[index]"
          class="delete"
          @click="deleteQuestion(question.id)"
        >
          削除
        </button>
        <div v-else @click="onEdit(index)"></div>
        <InvalidFeedback
          v-if="editable[index] && invalidFeedback('word')"
          :errors="invalidFeedback('word')"
        />
        <InvalidFeedback
          v-if="editable[index] && invalidFeedback('correct_answer')"
          :errors="invalidFeedback('correct_answer')"
        />
      </div>
    </div>
  </div>
</template>
