<script setup>
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../../components/InvalidFeedback';
import LoadingOverlay from '../../components/LoadingOverlay';
import Navigation from '../../components/Navigation';
import Toast from '../../components/Toast';
import {
  DELETE_ITEM_CONFIRM,
  JAPANESE_SYLLABARY,
  NO_MATCH_ITEMS,
} from '../../const/const';
import { useDebounce } from '../../functions/useDebounce';

const store = useStore();

onMounted(async () => {
  setIsLoading(true);
  await store.dispatch('item/get');
  setIsLoading(false);
});

store.dispatch('item/get');
const items = computed(() => store.getters['item/data']);
const invalidFeedback = computed(() => store.getters['item/invalidFeedback']);
const hasErrors = computed(() => store.getters['item/hasErrors']);
const hasErrorsPrecedent = computed(() => store.getters['precedent/hasErrors']);
const isInvalid = computed(() => store.getters['item/isInvalid']);
const editable = ref([]);
const keyword = ref('');
const currentCharacter = ref('');
const isLoading = computed(() => store.getters['loading/isLoading']);
const setIsLoading = (bool) => store.commit('loading/setIsLoading', bool);
const defaultPrecedent = {
  id: null,
  item_id: null,
  name: null,
};

const debounceSearch = useDebounce(() => {
  currentCharacter.value = '';
  store.dispatch('item/get', { keyword: keyword.value });
});
const fetchData = (character = '') => {
  store.dispatch('item/get', { filter: character });
};
const filter = (character) => {
  keyword.value = '';
  editable.value = [];
  currentCharacter.value = character;
  fetchData(character);
};
const onEdit = (index) => {
  editable.value = [];
  editable.value[index] = true;
  store.commit('item/setErrors', {});
};
const removePrecedent = async (index, _index, id = null) => {
  if (!id) {
    items.value[index].precedents.splice(_index, 1);
    return;
  }
  if (!confirm('事例を削除しますか？')) return;
  await store.dispatch('precedent/delete', id);
  if (hasErrorsPrecedent.value) return;
  setTimeout(() => {
    editable.value = [];
    fetchData(currentCharacter.value);
  }, 2000);
};
const addPrecedent = (index) => {
  items.value[index].precedents.push({ ...defaultPrecedent });
};
const updateItem = async (item, index) => {
  setIsLoading(true);
  await store.dispatch('item/update', item);
  setIsLoading(false);
  if (hasErrors.value) return;
  setTimeout(() => {
    editable.value[index] = false;
    fetchData(currentCharacter.value);
  }, 2000);
};
const deleteItem = async (id) => {
  if (confirm(DELETE_ITEM_CONFIRM)) {
    setIsLoading(true);
    await store.dispatch('item/delete', id);
    setIsLoading(false);
    if (hasErrors.value) return;
    setTimeout(() => {
      editable.value = [];
      fetchData(currentCharacter.value);
    }, 2000);
  }
};
const cancel = () => {
  editable.value = [];
  fetchData(currentCharacter.value);
};
</script>

<template>
  <LoadingOverlay :isLoading="isLoading" />
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
    <div class="wrap">
      <div
        class="row"
        v-for="(character, index) in JAPANESE_SYLLABARY"
        :key="index"
      >
        <div
          class="index-item jp-character"
          :class="character === currentCharacter && 'current-character'"
          @click="filter(character)"
        >
          {{ character }}
        </div>
        <span>/</span>
      </div>
      <div class="index-item jp-character" @click="filter('')">すべて</div>
    </div>
    <div class="row list-header">
      <div class="list-column-title">項目</div>
      <div class="list-column-title">事例</div>
    </div>
    <div v-if="!isLoading && !items.length">{{ NO_MATCH_ITEMS }}</div>
    <div v-else class="list-body">
      <div v-for="(item, index) in items" :key="item.id" class="row list-row">
        <div v-if="editable[index]" class="column">
          <input
            v-model="item.name"
            :class="isInvalid('name')"
            maxlength="255"
          />
          <InvalidFeedback :errors="invalidFeedback('name')" />
        </div>
        <div v-else @click="onEdit(index)" class="list-item">
          {{ item.name }}
        </div>
        <div class="precedent row">
          <template v-for="(precedent, _index) in item.precedents">
            <div v-if="editable[index]" class="column">
              <div class="row">
                <input
                  :key="precedent.id"
                  v-model="item.precedents[_index].name"
                  class="precedent-input"
                  :class="isInvalid('precedents' + _index + '.name')"
                  maxlength="255"
                />
                <div
                  class="precedent-input-xmark"
                  @click="removePrecedent(index, _index, precedent.id)"
                >
                  <font-awesome-icon class="xmark-icon" icon="xmark" />
                </div>
              </div>
              <InvalidFeedback
                :errors="invalidFeedback('precedents.' + _index + '.name')"
              />
            </div>
            <div v-else @click="onEdit(index)" class="list-item precedent">
              {{ item.precedents[_index].name }}
            </div>
          </template>
          <div
            v-if="editable[index]"
            class="precedent-input-plus"
            @click="addPrecedent(index)"
          >
            <font-awesome-icon class="plus-icon" icon="plus" />
          </div>
        </div>
        <button
          v-if="editable[index]"
          title="更新"
          @click="updateItem(item, index)"
        >
          <font-awesome-icon class="check-icon" icon="check" />
        </button>
        <div v-else @click="onEdit(index)"></div>
        <button
          v-if="editable[index]"
          title="キャンセル"
          class="cancel"
          @click="cancel()"
        >
          <font-awesome-icon class="xmark-icon" icon="xmark" />
        </button>
        <div v-else @click="onEdit(index)"></div>
        <button
          v-if="editable[index]"
          title="削除"
          class="delete"
          @click="deleteItem(item.id)"
        >
          <font-awesome-icon class="minus-icon" icon="minus" />
        </button>
        <div v-else @click="onEdit(index)"></div>
      </div>
    </div>
  </div>
</template>
