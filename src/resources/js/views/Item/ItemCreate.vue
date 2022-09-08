<script setup>
import { computed, onUnmounted, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../../components/InvalidFeedback';
import Navigation from '../../components/Navigation';
import Toast from '../../components/Toast';

const store = useStore();

const newItem = reactive({
  category: null,
  name: null,
  name_kana: null,
  precedents: [
    {
      name: null,
    },
  ],
});
const invalidFeedback = computed(() => store.getters['item/invalidFeedback']);
const hasErrors = computed(() => store.getters['item/hasErrors']);
const isInvalid = computed(() => store.getters['item/isInvalid']);
const csv = ref(null);

const add = () => {
  newItem.precedents.push({ name: '' });
};
const remove = (index) => {
  newItem.precedents.splice(index, 1);
};
const create = async () => {
  await store.dispatch('item/post', newItem);
  if (hasErrors.value) return;
  newItem.category = null;
  newItem.name = null;
  newItem.name_kana = null;
  newItem.precedents = [{ name: null }];
  store.commit('item/setErrors', {});
};
const importCSV = async () => {
  const formData = new FormData();

  formData.append('file', csv.value.files[0]);
  await store.dispatch('item/importCSV', formData);
};
onUnmounted(() => {
  store.commit('item/setErrors', {});
});
</script>

<template>
  <Toast />
  <Navigation />
  <div class="word-create">
    <div>
      <div class="row header">
        <div class="title">新規登録</div>
      </div>
      <div class="column">
        <label>カテゴリ</label>
        <select
          v-model="newItem.category"
          :class="isInvalid('category')"
          maxlength="255"
        >
          <option value="01">01</option>
        </select>
        <invalid-feedback
          v-if="invalidFeedback('category')"
          :errors="invalidFeedback('category')"
        />
      </div>
      <div class="column">
        <label>項目名</label>
        <input
          type="text"
          v-model="newItem.name"
          :class="isInvalid('name')"
          maxlength="255"
        />
        <invalid-feedback
          v-if="invalidFeedback('name')"
          :errors="invalidFeedback('name')"
        />
      </div>
      <div class="column">
        <label>項目名ふりがな</label>
        <input
          type="text"
          v-model="newItem.name_kana"
          :class="isInvalid('name_kana')"
          maxlength="255"
        />
        <invalid-feedback
          v-if="invalidFeedback('name_kana')"
          :errors="invalidFeedback('name_kana')"
        />
      </div>
      <div v-for="(precedent, index) in newItem.precedents" class="column">
        <label>名前{{ index + 1 }}</label>
        <div class="row">
          <input
            type="text"
            v-model="precedent.name"
            :class="isInvalid('precedents.precedents[' + index + '].name')"
            maxlength="255"
          />
          <button
            v-if="index > 0"
            class="item-remove-button"
            @click="remove(index)"
          >
            削除
          </button>
        </div>
        <invalid-feedback
          v-if="invalidFeedback('precedents.precedents[' + index + '].name')"
          :errors="invalidFeedback('precedents.precedents[' + index + '].name')"
        />
      </div>
      <button class="item-add-button" @click="add()">入力欄を追加</button>
      <div class="button-area">
        <button @click.prevent="create()">登録</button>
      </div>
      <hr />
      <div class="csv-import">
        <div class="csv-import-input-area">
          <label>CSVインポート</label>
          <input
            type="file"
            accept=".csv"
            ref="csv"
            :class="isInvalid('file')"
          />
          <invalid-feedback
            v-if="invalidFeedback('file')"
            :errors="invalidFeedback('file')"
          />
        </div>
        <button @click="importCSV()">CSVファイルをインポート</button>
      </div>
    </div>
  </div>
</template>
