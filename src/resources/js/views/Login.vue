<script setup>
import { computed, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import BaseInput from '../components/InputText.vue';

const router = useRouter();
const store = useStore();

const user = reactive({
  email: '',
  password: '',
});
const hasErrors = computed(() => store.getters['auth/hasErrors']);
const isForgotPassword = ref(false);

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
  <div class="login-form">
    <form class="column">
      <div class="invalid-feedback" v-if="hasErrors">
        メールアドレスまたはパスワードに誤りがあります。
      </div>
      <p class="column">
        <label for="login-email">Email</label>
        <BaseInput
          v-model="user.email"
          id="login-email"
          name="email"
          type="email"
          maxlength="255"
          autocomplete="on"
          inputmode="email"
        />
      </p>
      <template v-if="!isForgotPassword">
        <p class="column">
          <label for="login-password">Password</label>
          <BaseInput
            v-model="user.password"
            id="login-password"
            name="password"
            type="password"
            maxlength="128"
            autocomplete="on"
            inputmode="text"
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
  </div>
</template>
