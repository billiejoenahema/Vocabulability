<script setup>
import { useStore } from 'vuex';

const store = useStore();
</script>

<template>
  <nav>
    <router-link to="/">Home</router-link>
    <router-link to="/create">Create</router-link>
  </nav>
  <h3>英単語チェック</h3>
  <div>
    <label>単語</label>
    <p>issue</p>
    <label>正解</label>
    <p>answer</p>
  </div>
</template>
