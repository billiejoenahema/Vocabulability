<script setup>
import { computed, onMounted, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import InputCheckbox from '../../components/InputCheckbox.vue';
import InputDateSplit from '../../components/InputDateSplit.vue';
import BaseInput from '../../components/InputText.vue';

const router = useRouter();
const store = useStore();

const user = reactive({
  name: null,
  kana_name: null,
  birth_date: null,
  gender: null,
  postcode: null,
  address: null,
});

onMounted(() => {
  Object.assign(user, store.getters['profile/data']);
});

const hasErrors = computed(() => store.getters['profile/hasErrors']);
const invalidFeedback = computed(
  () => store.getters['profile/invalidFeedback']
);
const isInvalid = computed(() => store.getters['profile/isInvalid']);
const genderFormOptions = computed(
  () => store.getters['consts/genderFormOptions']
);
const cancel = () => {
  router.push('/profile');
};
const submit = async () => {
  await store.dispatch('profile/post', user);
  if (hasErrors.value) return;
  setTimeout(() => {
    router.push('/profile');
  }, 2000);
};
</script>

<template>
  <div class="container">
    <div class="row header">
      <div class="title">プロフィール編集</div>
    </div>
    <form class="mw-400">
      <div class="mb-2">
        <label for="name" class="mb-1">名前</label>
        <BaseInput
          type="text"
          :class-value="'form-control' + isInvalid('name')"
          id="name"
          :invalid-feedback="invalidFeedback('name')"
          maxlength="50"
          v-model="user.name"
        />
      </div>
      <div class="mb-2">
        <label for="kana_name" class="mb-1">フリガナ</label>
        <BaseInput
          type="text"
          :class-value="'form-control' + isInvalid('kana_name')"
          id="kana_name"
          :invalid-feedback="invalidFeedback('kana_name')"
          maxlength="50"
          v-model="user.kana_name"
        />
      </div>
      <div class="mb-2">
        <label for="birth_date" class="mb-1">生年月日</label>
        <InputDateSplit
          id="birth_date"
          :class-value="isInvalid('birth_date')"
          :invalid-feedback="invalidFeedback('birth_date')"
          :help-text="'半角数字で入力してください。'"
          v-model="user.birth_date"
        />
      </div>
      <InputCheckbox
        id="gender"
        :class-value="'form-control' + isInvalid('gender')"
        :invalid-feedback="invalidFeedback('gender')"
        :options="genderFormOptions"
        legend="性別"
        v-model="user.gender"
      />
      <div class="mb-2">
        <label for="phone" class="mb-1">電話番号</label>
        <BaseInput
          type="text"
          :class-value="'form-control' + isInvalid('phone')"
          id="phone"
          :invalid-feedback="invalidFeedback('phone')"
          maxlength="14"
          v-model="user.phone"
        />
      </div>
      <div class="mb-2">
        <label for="postcode" class="mb-1">郵便番号</label>
        <BaseInput
          :type="'text'"
          :class-value="'form-control' + isInvalid('postcode')"
          :id="'postcode'"
          :invalid-feedback="invalidFeedback('postcode')"
          autocorrect="postal-code"
          maxlength="8"
          v-model="user.postcode"
        />
      </div>
      <div class="mb-2">
        <label for="address" class="mb-1">住所</label>
        <BaseInput
          :type="'text'"
          :class-value="'form-control' + isInvalid('address')"
          :id="'address'"
          :invalid-feedback="invalidFeedback('address')"
          v-model="user.address"
        />
      </div>
      <div class="between">
        <button type="button" @click="cancel" class="cancel">キャンセル</button>
        <button type="button" @click="submit" class="submit">保存</button>
      </div>
    </form>
  </div>
</template>
