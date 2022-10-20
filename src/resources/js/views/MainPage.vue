<script setup>
import { useStore } from 'vuex';
import Navigation from '../components/Navigation.vue';

const store = useStore();

const sendMail = () => {
  store.dispatch('test_mail/send');
};
</script>

<template>
  <Navigation />
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
</template>
