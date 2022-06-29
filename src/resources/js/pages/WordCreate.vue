<script setup>
import { computed, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import Toast from '../components/Toast.vue';

const store = useStore();

store.dispatch('question/get');
const questions = computed(() => store.getters['question/data']);
const newQuestion = reactive({
  word: '',
  correct_answer: '',
  example: '',
});
const errors = computed(() => store.getters['question/errors']);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const editable = ref([]);

const invalidFeedback = (message) => {
  return message ? message[0] : '';
};
const toEditable = (index) => {
  editable.value = [];
  editable.value[index] = true;
};
const addWord = async () => {
  await store.dispatch('question/post', newQuestion);
  if (hasErrors.value) {
    return;
  }
  store.dispatch('question/get');
  newQuestion.word = '';
  newQuestion.correct_answer = '';
  newQuestion.example = '';
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
  <form @submit.prevent>
    <h4>新規登録</h4>
    <div class="column">
      <label>単語</label>
      <input type="text" v-model="newQuestion.word" />
      <div class="invalid-feedback">{{ invalidFeedback(errors.word) }}</div>
    </div>
    <div class="column">
      <label>正解</label>
      <input type="text" v-model="newQuestion.correct_answer" />
      <div class="invalid-feedback">
        {{ invalidFeedback(errors.correct_answer) }}
      </div>
    </div>
    <div class="column">
      <label>例文</label>
      <input type="text" v-model="newQuestion.example" />
      <div class="invalid-feedback">{{ invalidFeedback(errors.example) }}</div>
    </div>
    <div class="button-area">
      <button @click.prevent="addWord()">追加</button>
    </div>
  </form>
  <div class="word-list">
    <h4>登録済み単語リスト</h4>
    <div class="row list-header">
      <div class="list-column-title">単語</div>
      <div class="list-column-title">正解</div>
      <div class="list-column-title">例文</div>
      <div class="list-column-title"></div>
    </div>
    <div
      v-for="(question, index) in questions"
      :key="question.id"
      class="row list-body"
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
    </div>
  </div>
</template>
