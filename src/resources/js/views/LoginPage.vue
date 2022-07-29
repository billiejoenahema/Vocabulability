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
const invalidFeedback = computed(() => store.getters['auth/invalidFeedback']);
const hasErrors = computed(() => store.getters['auth/hasErrors']);

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
        :class="invalidFeedback('email') && 'invalid'"
        v-model="user.email"
        id="login-email"
        name="email"
        type="email" />
      <div class="invalid-feedback" v-if="invalidFeedback('email')">
            <div v-for="(error, index) in invalidFeedback('email')" :key="index">
              {{ error }}
            </div>
          </div>
    </p>
    <p class="column">
      <label for="login-password">Password</label>
      <input
        :class="invalidFeedback('password') && 'invalid'"
        v-model="user.password"
        id="login-password"
        name="password"
        type="password"
      />
      <div class="invalid-feedback" v-if="invalidFeedback('password')">
            <div v-for="(error, index) in invalidFeedback('password')" :key="index">
              {{ error }}
            </div>
          </div>
      <button class="sign-in" @click.prevent.stop="login()">Sign in</button>
    </p>
  </form>
</template>
