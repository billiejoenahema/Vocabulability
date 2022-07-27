<script setup>
import { computed, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const router = useRouter();
const store = useStore();

const user = reactive({
  email: '',
  password: '',
});
const errors = computed(() => store.getters['auth/errors']);
const hasErrors = computed(() => store.getters['auth/hasErrors']);
const invalidFeedback = (message) => {
  return message ? message[0] : '';
};

const login = async () => {
  await store.dispatch('auth/login', user);
  if (!hasErrors.value) {
    router.push('/');
  }
};
</script>

<template>
  <form class="column">
    <p class="column">
      <label for="login-email">Email</label>
      <input
        :class="invalidFeedback(errors.email) && 'invalid'"
        v-model="user.email"
        id="login-email"
        name="email"
        type="email" />
      <div class="invalid-feedback">{{ invalidFeedback(errors.email) }}</div>
    </p>
    <p class="column">
      <label for="login-password">Password</label>
      <input
        :class="invalidFeedback(errors.password) && 'invalid'"
        v-model="user.password"
        id="login-password"
        name="password"
        type="password"
      />
      <div class="invalid-feedback">{{ invalidFeedback(errors.password) }}</div>
      <button class="sign-in" @click.prevent.stop="login()">Sign in</button>
    </p>
  </form>
</template>
