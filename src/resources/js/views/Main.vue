<script setup>
import { reactive } from 'vue';
import { useStore } from 'vuex';
import InputText from '../components/InputText.vue';

const store = useStore();

const sendMail = () => {
  store.dispatch('test_mail/send');
};
const state = reactive({
  text: '',
  month: '',
  date: '',
  time: '',
  datetime: '',
});
</script>

<template>
  <div class="main-page">
    <ul>
      <li><router-link to="/word_check">単語チェック</router-link></li>
      <li><router-link to="/word_list">単語リスト</router-link></li>
      <li><router-link to="/word_create">単語登録</router-link></li>
      <li><router-link to="/item_list">項目リスト</router-link></li>
      <li><router-link to="/item_create">項目登録</router-link></li>
    </ul>
  </div>
  <div>
    <button @click="sendMail()">メール送信</button>
  </div>
  <div class="input-text">
    <InputText :type="'text'" :for-id-value="'text'" v-model="state.text" />
  </div>
  <div class="input-text">
    <InputText :type="'month'" :for-id-value="'month'" v-model="state.month" />
  </div>
  <div class="input-text">
    <InputText :type="'date'" :for-id-value="'date'" v-model="state.date" />
  </div>
  <div class="input-text">
    <InputText :type="'time'" :for-id-value="'time'" v-model="state.time" />
  </div>
  <div class="input-text">
    <InputText
      :type="'datetime-local'"
      :for-id-value="'datetime'"
      v-model="state.datetime"
    />
  </div>
</template>
