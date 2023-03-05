<script setup>
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../../components/InvalidFeedback';
import Pagination from '../../components/Pagination';
import ResultInfo from '../../components/ResultInfo';
import SortIcon from '../../components/SortIcon';
import { useDebounce } from '../../functions/useDebounce';

const store = useStore();

onMounted(async () => {
  await store.dispatch('question/get');
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
const loading = computed(() => store.getters['loading/loading']);
const fetchData = () => {
  store.dispatch('question/get', params.value);
  editable.value = [];
};
const debounceSearch = useDebounce(() => {
  params.value.filter = '';
  fetchData();
});
const setFilter = (alphabet) => {
  params.value.keyword = '';
  params.value.filter = alphabet;
  fetchData();
};
const resetParams = () => {
  store.commit('question/resetParams');
  fetchData();
};
const onChangeSort = (label) => {
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
const updateQuestion = async (question) => {
  await store.dispatch('question/update', question);
  if (hasErrors.value) {
    fetchData();
  }
};
const deleteQuestion = async (id) => {
  if (confirm('削除しますか？')) {
    await store.dispatch('question/delete', id);
    if (!hasErrors.value) {
      fetchData(currentAlphabet.value);
    }
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
</script>

<template>
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
    <ResultInfo :meta="meta" />
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
    <div v-if="!loading && !questions.length">
      検索に一致する単語はありませんでした。
    </div>
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
          @click="updateQuestion(question, id)"
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
