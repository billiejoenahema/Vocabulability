<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const toast = computed(() => store.getters['toast/data']);
const className = () => {
  if (toast.value.status < 300) {
    return 'success';
  } else if (toast.value.status >= 400) {
    return 'error';
  } else {
    return '';
  }
};
</script>

<template>
  <div class="toast" :class="className()">{{ toast.content }}</div>
</template>

<style scoped>
main > .toast {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 10000;
  font-size: 1.2rem;
  width: 100vw;
  max-width: 2000px;
  border-radius: 0;
  text-align: center;
}
.success {
  background: #80cbc4;
  color: #00695c;
  opacity: 1;
  transform: translateY(-40px);
  animation: anime 3s ease 1 normal;
}
.error {
  background: #f8bbd0;
  color: #ad1457;
  opacity: 1;
  transform: translateY(-40px);
  animation: anime 3s ease 1 normal;
}
@keyframes anime {
  16.666% {
    transform: translateY(0);
  }
  83.333% {
    transform: translateY(0);
  }
}
</style>
