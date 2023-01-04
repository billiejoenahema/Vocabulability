<script setup>
import { reactive } from 'vue';
import { useStore } from 'vuex';
import BaseInput from '../components/BaseInput.vue';

const store = useStore();

const sendMail = () => {
  store.dispatch('test_mail/send');
};
const state = reactive({
  tel: '',
  email: '',
  month: '',
  date: '',
  time: '',
  datetime: '',
});
</script>

<template>
  <div class="container">
    <div class="row header">
      <div class="title">ページリスト</div>
    </div>
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
    <label>TEL</label>
    <BaseInput v-model="state.tel" autocomplete="on" id="tel" type="tel" />
  </div>
  <div class="input-text">
    <label>Email</label>
    <Input v-model="state.email" autocomplete="on" id="email" type="email" />
  </div>
  <div class="input-text">
    <label>TEL</label>
    <Input v-model="state.text" autocomplete="on" id="text" type="tel" />
  </div>
  <div class="input-text">
    <Input type="month" id="month" v-model="state.month" />
  </div>
  <div class="input-text">
    <label>BirthDay</label>
    <Input v-model="state.date" autocomplete="on" id="date" type="date" />
  </div>
  <div class="input-text">
    <Input type="time" id="time" v-model="state.time" />
  </div>
  <div class="input-text">
    <Input type="datetime-local" id="datetime" v-model="state.datetime" />
  </div>
</template>
