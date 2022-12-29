<script setup>
import { computed, onMounted, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import BaseInput from '../../components/BaseInput.vue';

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

const onChangeBirthDate = (e) => {
  console.log(e.target.value);
};
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
        <input type="text" class="form-control" id="name" v-model="user.name" />
      </div>
      <div class="mb-2">
        <label for="kana_name" class="mb-1">フリガナ</label>
        <BaseInput
          :type="'text'"
          :class-value="'form-control'"
          :id="'kana_name'"
          :invalid-feedback="invalidFeedback('kana_name')"
          :model-value="user.kana_name"
          @update:model-value="user.kana_name"
        />
        <input
          type="text"
          class="form-control"
          id="kana_name"
          v-model="user.kana_name"
        />
      </div>
      <div class="mb-2">
        <label for="birth_date" class="mb-1">生年月日</label>
        <input
          type="text"
          class="form-control"
          id="birth_date"
          placeholder="1990/01/01"
          @change="onChangeBirthDate()"
        />
      </div>
      <div class="mb-2">
        <label for="birth_date" class="mb-1">性別</label>
        <select v-model="user.gender">
          <option></option>
          <option value="01">男性</option>
          <option value="02">女性</option>
          <option value="03">無回答</option>
        </select>
      </div>
      <div class="mb-2">
        <label for="phone" class="mb-1">電話番号</label>
        <input
          type="text"
          class="form-control"
          id="phone"
          v-model="user.phone"
        />
      </div>
      <div class="mb-2">
        <label for="postcode" class="mb-1">郵便番号</label>
        <input
          type="text"
          class="form-control"
          id="postcode"
          v-model="user.postcode"
        />
      </div>
      <div class="mb-2">
        <label for="address" class="mb-1">住所</label>
        <input
          type="text"
          class="form-control"
          id="address"
          v-model="user.address"
        />
      </div>
      <div class="between">
        <button type="button" @click="cancel" class="cancel">キャンセル</button>
        <button type="button" @click="submit">保存</button>
      </div>
    </form>
  </div>
</template>
