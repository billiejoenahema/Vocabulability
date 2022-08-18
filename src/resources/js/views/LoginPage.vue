<script setup>
import { computed, onMounted, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import InvalidFeedback from '../components/InvalidFeedback.vue';
import Toast from '../components/Toast.vue';

const router = useRouter();
const store = useStore();

const user = reactive({
  email: '',
  password: '',
});
const isLogin = computed(() => store.getters['profile/isLogin']);
const invalidFeedback = computed(() => store.getters['auth/invalidFeedback']);
const hasErrors = computed(() => store.getters['auth/hasErrors']);
onMounted(async () => {
  await store.dispatch('profile/getIfNeeded');
  if (isLogin.value) {
    router.push('/');
  }
});
const login = async () => {
  await store.dispatch('auth/login', user);
  if (!hasErrors.value) {
    router.push('/');
  }
};
</script>

<template>
  <Toast />
  <form class="column">
    <p class="column">
      <label for="login-email">Email</label>
      <input
        :class="invalidFeedback('email') && 'invalid'"
        v-model="user.email"
        id="login-email"
        name="email"
        type="email"
        maxlength="255"
      />
      <InvalidFeedback
        v-if="invalidFeedback('email')"
        :errors="invalidFeedback('email')"
      />
    </p>
    <p class="column">
      <label for="login-password">Password</label>
      <input
        :class="invalidFeedback('password') && 'invalid'"
        v-model="user.password"
        id="login-password"
        name="password"
        type="password"
        maxlength="128"
      />
      <InvalidFeedback
        v-if="invalidFeedback('password')"
        :errors="invalidFeedback('password')"
      />
      <button class="sign-in" @click.prevent.stop="login()">Sign in</button>
    </p>
  </form>
</template>
