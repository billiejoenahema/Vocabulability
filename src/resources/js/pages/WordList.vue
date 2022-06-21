<script setup>
import { computed, reactive } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

store.dispatch('question/get');
const questions = computed(() => store.getters['question/data']);
const newQuestion = reactive({
  word: '',
  correct_answer: '',
  example: '',
});
const addWord = () => {
  console.log('clicked!');
  store.dispatch('question/post', newQuestion);
};
</script>

<template>
  <form @submit.prevent>
    <h4>新規登録</h4>
    <div class="column">
      <label>単語</label>
      <input type="text" v-model="newQuestion.word" />
    </div>
    <div class="column">
      <label>正解</label>
      <input type="text" v-model="newQuestion.correct_answer" />
    </div>
    <div class="column">
      <label>例文</label>
      <input type="text" v-model="newQuestion.example" />
    </div>
    <button @click.prevent="addWord()">追加</button>
  </form>
  <div>
    <h4>登録済み単語リスト</h4>
    <ul>
      <li v-for="question in questions" :key="question.id">
        {{ question.word }}
      </li>
    </ul>
  </div>
</template>
