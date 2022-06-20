<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

store.dispatch('question/get');
const questions = computed(() => store.getters['question/data']);
</script>

<template>
  <div>
    <ul>
      <li v-for="question in questions" :key="question.id">
        {{ question.word }}
      </li>
    </ul>
  </div>
</template>
