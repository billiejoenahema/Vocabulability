<script setup>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
store.dispatch('question/get');
const questions = computed(() => store.getters['question/randomData']);
const index = ref(0);
const isShowAnswer = ref(false);
const isLastQuestion = ref(false);
const toNextQuestion = async () => {
  store.commit('loading/setLoading', true);
  if (isLastQuestion.value) {
    await store.dispatch('question/get');
    index.value = 0;
    isLastQuestion.value = false;
    return;
  }
  index.value++;
  isShowAnswer.value = false;
  if (index.value + 1 >= questions.value.length) {
    isLastQuestion.value = true;
  }
  store.commit('loading/setLoading', false);
};
</script>

<template>
  <div class="container">
    <div class="row header">
      <div class="title">エンジニア英単語チェック</div>
    </div>
    <div class="column">
      <div class="column word-wrapper">
        <label class="title">単語</label>
        <div class="word">{{ questions[index]?.word }}</div>
      </div>
      <div class="column answer-wrapper">
        <label class="title">正答</label>
        <div v-if="isShowAnswer" class="answer" @click="toNextQuestion()">
          {{ questions[index]?.correct_answer }}
        </div>
        <div v-else @click="isShowAnswer = true" class="answer show-answer">
          正答を見る
        </div>
      </div>
      <button @click="toNextQuestion()">次の問題</button>
    </div>
  </div>
</template>
