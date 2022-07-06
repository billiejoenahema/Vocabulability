<script setup>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import Navigation from '../components/Navigation.vue';
import Toast from '../components/Toast.vue';
import { useDebounce } from '../functions/useDebounce';

const store = useStore();

store.dispatch('question/get');
const questions = computed(() => store.getters['question/data']);
const errors = computed(() => store.getters['question/errors']);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const editable = ref([]);
const keyword = ref('');

const debounceSearch = useDebounce(() => {
  store.dispatch('question/get', { keyword: keyword.value });
});
const invalidFeedback = (message) => {
  return message ? message[0] : '';
};
const toEditable = (index) => {
  editable.value = [];
  editable.value[index] = true;
};
const updateQuestion = async (question, index) => {
  await store.dispatch('question/update', question);
  if (hasErrors.value) {
    return;
  }
  editable.value[index] = false;
  store.dispatch('question/get');
};
const deleteQuestion = async (id) => {
  if (confirm('この問題を削除しますか？')) {
    await store.dispatch('question/delete', id);
    if (hasErrors.value) {
      return;
    }
    store.dispatch('question/get');
  }
};
const cancel = (index) => {
  store.dispatch('question/get');
  editable.value[index] = false;
};
</script>

<template>
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
    <div class="row list-header">
      <div class="list-column-title">単語</div>
      <div class="list-column-title">正解</div>
      <div class="list-column-title">例文</div>
      <div class="list-column-title"></div>
    </div>
    <div class="list-body">
      <div
        v-for="(question, index) in questions"
        :key="question.id"
        class="row list-row"
      >
        <input v-if="editable[index]" v-model="question.word" />
        <div v-else @click="toEditable(index)" class="list-item">
          {{ question.word }}
        </div>
        <input v-if="editable[index]" v-model="question.correct_answer" />
        <div v-else @click="toEditable(index)" class="list-item">
          {{ question.correct_answer }}
        </div>
        <input v-if="editable[index]" v-model="question.example" />
        <div v-else @click="toEditable(index)" class="list-item">
          {{ question.example }}
        </div>
        <button v-if="editable[index]" @click="updateQuestion(question, index)">
          更新
        </button>
        <div v-else></div>
        <button v-if="editable[index]" class="cancel" @click="cancel(index)">
          キャンセル
        </button>
        <div v-else></div>
        <button
          v-if="editable[index]"
          class="delete"
          @click="deleteQuestion(question.id)"
        >
          削除
        </button>
        <div v-else></div>
        <div v-show="editable[index]" class="invalid-feedback">
          {{ invalidFeedback(errors.word) }}
        </div>
        <div v-show="editable[index]" class="invalid-feedback">
          {{ invalidFeedback(errors.correct_answer) }}
        </div>
      </div>
      <div v-if="questions.length === 0">
        検索に一致する単語はありませんでした。
      </div>
    </div>
  </div>
</template>
