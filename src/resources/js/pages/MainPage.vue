<script setup>
import { reactive } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const question = reactive({
  word: '',
  correct_answer: '',
  example: '',
});
const addWord = () => {
  console.log('clicked!');
  store.dispatch('question/post', question);
};
</script>

<template>
  <h3>メインページ</h3>
  <div>
    <label>単語</label>
    <p>issue</p>
    <label>正解</label>
    <p>answer</p>
  </div>
  <form @submit.prevent>
    <label>単語</label>
    <input type="text" v-model="question.word" />
    <label>正解</label>
    <input type="text" v-model="question.correct_answer" />
    <label>例文</label>
    <input type="text" v-model="question.example" />
    <button @click.prevent="addWord()">追加</button>
  </form>
</template>
