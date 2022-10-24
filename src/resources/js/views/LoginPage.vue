<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
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
const isInvalid = computed(() => store.getters['auth/isInvalid']);
const isForgotPassword = ref(false);
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
const guestLogin = async () => {
  await store.dispatch('auth/guestLogin');
  if (!hasErrors.value) {
    router.push('/');
  }
};
const forgotPassword = async () => {
  await store.dispatch('auth/forgotPassword', { email: user.email });
};
</script>

<template>
  <Toast />
  <div class="login-form">
    <form class="column">
      <p class="column">
        <label for="login-email">Email</label>
        <input
          :class="isInvalid('email')"
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
      <template v-if="!isForgotPassword">
        <p class="column">
          <label for="login-password">Password</label>
          <input
            :class="isInvalid('password')"
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
        <div class="d-flex justify-content-between align-items-center">
          <button @click.prevent.stop="guestLogin()">Guest Login</button>
          <a href="#" @click.prevent.stop="isForgotPassword = true"
            >forgot password</a
          >
        </div>
      </template>
      <template v-else>
        <div class="d-flex justify-content-between align-items-center">
          <button @click.prevent="forgotPassword()">Reset password</button>
          <a href="#" @click.prevent="isForgotPassword = false">back</a>
        </div>
      </template>
    </form>
    <div></div>
  </div>
</template>
