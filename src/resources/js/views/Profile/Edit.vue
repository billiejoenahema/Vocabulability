<script setup>
import { computed, onMounted, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import YubinBango from 'yubinbango-core2';
import InputDateSplit from '../../components/InputDateSplit.vue';
import InputEmail from '../../components/InputEmail.vue';
import InputRadio from '../../components/InputRadio.vue';
import InputTel from '../../components/InputTel.vue';
import InputText from '../../components/InputText.vue';
import { scrollToInvalidInput } from '../../functions/scrollToInvalidInput.js';

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
  store.dispatch('consts/getIfNeeded');
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
// 住所を自動入力
const setAddress = (input) => {
  // ハイフンを取り除く
  const code = input.replace('-', '');
  // 7桁の数字でなかったら処理を終了する
  if (!code.match(/^\d{7}/)) return false;
  new YubinBango.Core(code, (address) => {
    user.address = `${address.region}${address.locality}${address.street}`;
  });
};
// バリデーションエラーが表示されている項目までスクロールさせる

const submit = async () => {
  await store.dispatch('profile/post', user);
  if (hasErrors.value) {
    scrollToInvalidInput();
    return;
  }
  router.push('/profile');
};
</script>

<template>
  <div class="container">
    <div class="row header">
      <div class="title">プロフィール編集</div>
    </div>
    <form class="mw-400 needs-validation mb-2">
      <div class="mb-2">
        <label for="name" class="mb-1"
          >名前<span class="required-badge">必須</span></label
        >
        <InputText
          :class-value="isInvalid('name')"
          helper-text="50文字以内"
          id="name"
          input-counter="on"
          :invalid-feedback="invalidFeedback('name')"
          maxlength="50"
          placeholder="例）山田 太郎"
          v-model="user.name"
        />
      </div>
      <div class="mb-2">
        <label for="kana_name" class="mb-1"
          >フリガナ<span class="required-badge">必須</span></label
        >
        <InputText
          :class-value="isInvalid('kana_name')"
          helper-text="カタカナ50文字以内"
          id="kana_name"
          input-counter="on"
          :invalid-feedback="invalidFeedback('kana_name')"
          maxlength="50"
          placeholder="例）ヤマダ タロウ"
          v-model="user.kana_name"
        />
      </div>
      <div class="mb-2">
        <label for="birth_date" class="mb-1"
          >生年月日<span class="required-badge">必須</span></label
        >
        <InputDateSplit
          :class-value="isInvalid('birth_date')"
          id="birth_date"
          :invalid-feedback="invalidFeedback('birth_date')"
          :helper-text="'半角数字'"
          v-model="user.birth_date"
        />
      </div>
      <InputRadio
        id="gender"
        :class-value="isInvalid('gender')"
        :invalid-feedback="invalidFeedback('gender')"
        legend="性別"
        :options="genderFormOptions"
        v-model="user.gender"
      />
      <div class="mb-2">
        <label for="phone" class="mb-1"
          >メールアドレス<span class="required-badge">必須</span></label
        >
        <InputEmail
          :class-value="isInvalid('email')"
          id="email"
          :invalid-feedback="invalidFeedback('email')"
          maxlength="255"
          placeholder="例）example@example.com"
          v-model="user.email"
        />
      </div>
      <div class="mb-2">
        <label for="phone" class="mb-1"
          >電話番号<span class="required-badge">必須</span></label
        >
        <InputTel
          :class-value="isInvalid('phone')"
          :helper-text="'半角数字'"
          id="phone"
          :invalid-feedback="invalidFeedback('phone')"
          maxlength="14"
          placeholder="例）09012345678"
          v-model="user.phone"
        />
      </div>
      <div class="mb-2">
        <label for="postcode" class="mb-1"
          >郵便番号<span class="required-badge">必須</span></label
        >
        <InputText
          autocorrect="postal-code"
          :class-value="isInvalid('postcode')"
          :id="'postcode'"
          :invalid-feedback="invalidFeedback('postcode')"
          :helper-text="'半角数字7文字'"
          maxlength="8"
          placeholder="例）1010001"
          v-model="user.postcode"
          @update:model-value="setAddress(user.postcode)"
        />
      </div>
      <div class="mb-2">
        <label for="address" class="mb-1"
          >住所<span class="required-badge">必須</span></label
        >
        <InputText
          :class-value="isInvalid('address')"
          :id="'address'"
          input-counter="on"
          :invalid-feedback="invalidFeedback('address')"
          :helper-text="'50文字以内'"
          maxlength="50"
          placeholder="例）東京都千代田区丸の内１丁目９"
          v-model="user.address"
        />
      </div>
      <div class="between">
        <button type="button" @click="cancel" class="cancel">キャンセル</button>
        <button type="button" class="submit" @click="submit">保存</button>
      </div>
    </form>
  </div>
</template>
