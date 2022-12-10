<script setup>
import { computed, onMounted, onUnmounted, ref, watchEffect } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../../components/InvalidFeedback';
import LoadingOverlay from '../../components/LoadingOverlay';
import Pagination from '../../components/Pagination';
import SortIcon from '../../components/SortIcon';
import { useDebounce } from '../../functions/useDebounce';

const store = useStore();

onMounted(async () => {
  setIsLoading(true);
  await store.dispatch('item/get', params.value);
  setIsLoading(false);
});

const items = computed(() => store.getters['item/data']);
const params = computed(() => store.getters['item/params']);
const japaneseSyllabary = computed(
  () => store.getters['consts/japaneseSyllabary']
);
const invalidFeedback = computed(() => store.getters['item/invalidFeedback']);
const hasErrors = computed(() => store.getters['item/hasErrors']);
const hasErrorsPrecedent = computed(() => store.getters['precedent/hasErrors']);
const isInvalid = computed(() => store.getters['item/isInvalid']);
const links = computed(() => store.getters['item/links']);
const editable = ref([]);
const isLoading = computed(() => store.getters['loading/isLoading']);
const setIsLoading = (bool) => store.commit('loading/setIsLoading', bool);
const defaultPrecedent = {
  id: null,
  item_id: null,
  name: null,
};

const setFilter = (character) => {
  params.value.keyword = '';
  editable.value = [];
  params.value.filter = character;

  fetchData();
};
const resetParams = () => {
  store.commit('item/resetParams');
  fetchData();
};
const fetchData = () => {
  store.dispatch('item/get', params.value);
};
const onChangeSort = (label) => {
  editable.value = false;
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
  store.commit('item/setErrors', {});
};
const debounceSearch = useDebounce(() => {
  if (params.value.created_at_from === '') return;
  params.value.filter = '';
  fetchData();
});
const removePrecedent = async (index, _index, id = null) => {
  if (!id) {
    items.value[index].precedents.splice(_index, 1);
    return;
  }
  if (!confirm('削除しますか？')) return;
  await store.dispatch('precedent/delete', id);
  if (hasErrorsPrecedent.value) return;
  setTimeout(() => {
    editable.value = [];
    fetchData();
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
    fetchData();
  }, 2000);
};
const deleteItem = async (id) => {
  if (confirm('削除しますか？')) {
    setIsLoading(true);
    await store.dispatch('item/delete', id);
    setIsLoading(false);
    if (hasErrors.value) return;
    setTimeout(() => {
      editable.value = [];
      fetchData();
    }, 2000);
  }
};
const cancel = () => {
  editable.value = [];
  fetchData();
};
const changePage = (page = null) => {
  if (page) {
    params.value.page = page;
    fetchData();
  }
};
// TODO watchEffectをやめて検索リクエスト送信時にvalidityをチェックするようにする
watchEffect(() => {
  // <input type="date" />に無効な日付を入力させないようにする
  if (params.value.created_at_from === '') {
    store.commit('item/setErrors', {
      datetime: ['有効な日付を指定してください。'],
    });
    // 更新ボタンを不活性化する
    // :disabled="params.created_at_from === ''"
  }
});
onUnmounted(() => {
  store.commit('item/setErrors', {});
});
</script>

<template>
  <LoadingOverlay :isLoading="isLoading" />
  <div class="item-list">
    <div class="row header">
      <div class="title">登録済みカラム名リスト</div>
      <div class="search-input-wrapper">
        <input
          v-model="params.created_at_from"
          @input="debounceSearch()"
          type="datetime-local"
        />
        <InvalidFeedback :errors="invalidFeedback('datetime')" />
      </div>
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
      <div
        class="row"
        v-for="(character, index) in japaneseSyllabary"
        :key="index"
      >
        <div
          class="index-item jp-character"
          :class="character === params.filter && 'current-character'"
          @click="setFilter(character)"
        >
          {{ character }}
        </div>
        <span>/</span>
      </div>
      <div class="index-item jp-character" @click="setFilter('')">すべて</div>
    </div>
    <div class="row list-header">
      <div class="row" @click="onChangeSort('name')">
        <div class="list-column-title">項目</div>
        <SortIcon
          :is-asc="params?.is_asc"
          :active="params?.column === 'name'"
        />
      </div>
      <div class="row" @click="onChangeSort('precedent')">
        <div class="list-column-title">カラム名</div>
        <SortIcon
          :is-asc="params?.is_asc"
          :active="params?.column === 'precedent'"
        />
      </div>
    </div>
    <div v-if="!isLoading && !items.length">
      検索に一致する項目はありませんでした。
    </div>
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
                  :class="isInvalid('precedents.' + _index + '.name')"
                  maxlength="255"
                />
                <div
                  class="precedent-input-xmark"
                  @click="removePrecedent(index, _index, precedent.id)"
                  title="カラム名を削除"
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
            title="カラム名を追加"
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
  <Pagination :links="links" @change="changePage" />
</template>
