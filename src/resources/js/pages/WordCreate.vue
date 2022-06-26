<script setup>
import { computed, reactive, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

store.dispatch('question/get');
const questions = computed(() => store.getters['question/data']);
const newQuestion = reactive({
  word: '',
  correct_answer: '',
  example: '',
});
const editable = ref([false]);
const addWord = () => {
  store.dispatch('question/post', newQuestion);
};
const updateQuestion = (question, index) => {
  store.dispatch('question/update', question);
  editable.value[index] = false;
};
const cancel = (index) => {
  editable.value[index] = false;
};
const deleteQuestion = (id) => {
  store.dispatch('question/delete', id);
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
      <div v-else @click="editable[index] = true" class="list-item">
        {{ question.word }}
      </div>
      <input v-if="editable[index]" v-model="question.correct_answer" />
      <div v-else @click="editable[index] = true" class="list-item">
        {{ question.correct_answer }}
      </div>
      <input v-if="editable[index]" v-model="question.example" />
      <div v-else @click="editable[index] = true" class="list-item">
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
