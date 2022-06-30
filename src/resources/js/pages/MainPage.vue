<script setup>
import { useStore } from 'vuex';
import Navigation from '../components/Navigation.vue';

const store = useStore();
</script>

<template>
  <Navigation />
  <h3>英単語チェッカー</h3>
  <div>
    <label>単語</label>
    <p>issue</p>
    <label>正解</label>
    <p>answer</p>
  </div>
</template>
