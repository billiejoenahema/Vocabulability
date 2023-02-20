<script setup>
import { computed, nextTick, onUnmounted, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../../components/InvalidFeedback';

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
  description: null,
});
const invalidFeedback = computed(() => store.getters['item/invalidFeedback']);
const hasErrors = computed(() => store.getters['item/hasErrors']);
const isInvalid = computed(() => store.getters['item/isInvalid']);
const setLoading = (bool) => store.commit('loading/setLoading', bool);
const csvRef = ref(null);

const add = () => {
  newItem.precedents.push({ name: '' });
};
const remove = (index = null) => {
  newItem.precedents.splice(index, 1);
};
// newItemの初期化
const initNewItem = () => {
  Object.keys(newItem).forEach((key) => {
    if (key === 'precedents') {
      newItem[key] = [{ name: null }];
      return;
    }
    newItem[key] = null;
  });
};
const create = async () => {
  setLoading(true);
  await store.dispatch('item/post', newItem);
  if (!hasErrors.value) {
    // newItemを初期化する
    initNewItem();
    await nextTick();
    store.commit('item/setErrors', {});
  }
  setLoading(false);
};
const importCSV = async () => {
  setLoading(true);
  const formData = new FormData();
  formData.append('file', csvRef.value.files[0]);
  await store.dispatch('item/importCSV', formData);
  if (!hasErrors.value) {
    csvRef.value.files[0] = null;
  }
  setLoading(false);
};
onUnmounted(() => {
  store.commit('item/setErrors', {});
});
</script>

<template>
  <div class="container">
    <div class="row header">
      <div class="title">新規登録</div>
    </div>
    <div class="column">
      <label>カテゴリ</label>
      <select
        v-model="newItem.category"
        :class="isInvalid('category')"
        maxlength="50"
      >
        <option value="01">01</option>
      </select>
      <InvalidFeedback :invalid-feedback="invalidFeedback('category')" />
    </div>
    <div class="column">
      <label>項目名</label>
      <input
        type="text"
        v-model="newItem.name"
        :class="isInvalid('name')"
        maxlength="50"
      />
      <InvalidFeedback :invalid-feedback="invalidFeedback('name')" />
    </div>
    <div class="column">
      <label>項目名ふりがな</label>
      <input
        type="text"
        v-model="newItem.name_kana"
        :class="isInvalid('name_kana')"
        maxlength="50"
      />
      <InvalidFeedback :invalid-feedback="invalidFeedback('name_kana')" />
    </div>
    <div
      v-for="(precedent, index) in newItem.precedents"
      :key="precedent.id"
      class="column"
    >
      <label>カラム名{{ index + 1 }}</label>
      <div class="row">
        <input
          type="text"
          v-model="precedent.name"
          :class="isInvalid('precedents.' + index + '.name')"
          maxlength="50"
        />
        <button v-if="index === 0" class="item-add-button" @click="add()">
          <font-awesome-icon class="icon" icon="plus" />入力欄を追加
        </button>
        <button v-else class="item-remove-button" @click="remove(index)">
          削除
        </button>
      </div>
      <InvalidFeedback
        :errors="invalidFeedback('precedents.' + index + '.name')"
      />
    </div>
    <div class="column">
      <label>説明</label>
      <input
        id="description"
        type="text"
        v-model="newItem.description"
        :class="isInvalid('description')"
        maxlength="200"
      />
      <InvalidFeedback :invalid-feedback="invalidFeedback('description')" />
    </div>
    <div class="button-area">
      <button @click.prevent="create()" class="register">登録</button>
    </div>
    <hr />
    <div class="csv-import">
      <div class="csv-import-input-area">
        <label>CSVインポート</label>
        <input
          type="file"
          accept=".csv"
          ref="csvRef"
          :class="isInvalid('file')"
        />
        <invalid-feedback :errors="invalidFeedback('file')" />
      </div>
      <button @click="importCSV()">CSVファイルをインポート</button>
    </div>
  </div>
</template>
