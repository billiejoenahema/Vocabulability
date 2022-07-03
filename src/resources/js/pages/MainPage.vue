<script setup>
import { useStore } from 'vuex';
import Navigation from '../components/Navigation.vue';

const store = useStore();
</script>

<template>
  <Navigation />
  <div class="main-page">
    <div class="row header">
      <div class="title">英単語チェッカー</div>
    </div>
    <div>
      <label>単語</label>
      <p>issue</p>
      <label>正解</label>
      <p>answer</p>
    </div>
  </div>
</template>
