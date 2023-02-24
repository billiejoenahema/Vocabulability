<script setup>
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import DataCount from '../../components/DataCount';
import InvalidFeedback from '../../components/InvalidFeedback';
import Pagination from '../../components/Pagination';
import SortIcon from '../../components/SortIcon';
import { useDebounce } from '../../functions/useDebounce';

const store = useStore();

onMounted(async () => {
  setLoading(true);
  await store.dispatch('item/get', params.value);
  setLoading(false);
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
const meta = computed(() => store.getters['item/meta']);
const editable = ref([]);
const inputDateRef = ref(null);
const loading = computed(() => store.getters['loading/loading']);
const setLoading = (bool) => store.commit('loading/setLoading', bool);
const defaultPrecedent = {
  id: null,
  item_id: null,
  name: null,
};

const setFilter = (character) => {
  params.value.keyword = '';
  params.value.filter = character;
  fetchData();
};
const resetParams = () => {
  store.commit('item/resetParams');
  fetchData();
};
const fetchData = () => {
  store.dispatch('item/get', params.value);
  editable.value = [];
};
const sort = (label) => {
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
  // <input type="date" />に無効な日付が入力されていたら検索を実行しない
  if (inputDateRef.value.validity.badInput) {
    store.commit('item/setErrors', {
      datetime: ['有効な日付を指定してください。'],
    });
    return;
  }
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
    fetchData();
  }, 2000);
};
const addPrecedent = (index) => {
  items.value[index].precedents.push({ ...defaultPrecedent });
};
const updateItem = async (item, index) => {
  item.precedents.forEach((v) => {
    v.item_id = item.id;
  });
  setLoading(true);
  await store.dispatch('item/update', item);
  setLoading(false);
  if (hasErrors.value) return;
  setTimeout(() => {
    editable.value[index] = false;
    fetchData();
  }, 2000);
};
const deleteItem = async (id) => {
  if (confirm('削除しますか？')) {
    setLoading(true);
    await store.dispatch('item/delete', id);
    setLoading(false);
    if (hasErrors.value) return;
    setTimeout(() => {
      fetchData();
    }, 2000);
  }
};
const cancel = () => {
  fetchData();
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
      <div class="title">登録済みカラム名リスト</div>
      <div class="search-input-wrapper">
        <input
          v-model="params.created_at_from"
          type="datetime-local"
          ref="inputDateRef"
          @input="debounceSearch()"
        />
        <InvalidFeedback :invalid-feedback="invalidFeedback('datetime')" />
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
      <div class="row" v-for="character in japaneseSyllabary" :key="character">
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
    <DataCount v-if="meta" :meta="meta" />
    <div class="row list-header">
      <div class="row" @click="sort('name_kana')">
        <div class="list-column-title">項目</div>
        <SortIcon
          :is-asc="params?.is_asc"
          :active="params?.column === 'name_kana'"
        />
      </div>
      <div class="row" @click="sort('precedent')">
        <div class="list-column-title">カラム名</div>
        <SortIcon
          :is-asc="params?.is_asc"
          :active="params?.column === 'precedent'"
        />
      </div>
      <div class="row" @click="sort('description')">
        <div class="list-column-title">説明</div>
        <SortIcon
          :is-asc="params?.is_asc"
          :active="params?.column === 'description'"
        />
      </div>
    </div>
    <div v-if="!loading && !items.length">
      検索に一致する項目はありませんでした。
    </div>
    <div v-else class="list-body">
      <div v-for="(item, index) in items" :key="item.id" class="row list-row">
        <div v-if="editable[index]" class="column">
          <input
            v-model="item.name"
            :class="isInvalid('name')"
            maxlength="50"
          />
          <InvalidFeedback :invalid-feedback="invalidFeedback('name')" />
        </div>
        <div v-else @click="onEdit(index)" class="list-item">
          {{ item.name }}
        </div>
        <div class="precedent row">
          <template
            v-for="(precedent, _index) in item.precedents"
            :key="precedent.id ?? 'index_' + _index"
          >
            <div v-if="editable[index]" class="column">
              <div class="row">
                <input
                  :key="precedent.id"
                  v-model="item.precedents[_index].name"
                  class="precedent-input"
                  :class="isInvalid('precedents.' + _index + '.name')"
                  maxlength="50"
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
        <div v-if="editable[index]" class="column">
          <input
            v-model="item.description"
            :class="isInvalid('description')"
            maxlength="50"
          />
          <InvalidFeedback :invalid-feedback="invalidFeedback('description')" />
        </div>
        <div v-else @click="onEdit(index)" class="list-item">
          {{ item.description }}
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
  <Pagination :links="meta.links" @change="changePage" />
</template>
