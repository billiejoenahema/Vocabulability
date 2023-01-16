<script setup>
import dayjs from 'dayjs';
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const router = useRouter();
const store = useStore();

store.dispatch('profile/get');
const user = computed(() => store.getters['profile/data']);
const genderTextValue = computed(() => store.getters['consts/genderTextValue']);
// 日付フォーマット
const formatDate = (value) => {
  return dayjs(value).format('YYYY年MM月DD日');
};
// 郵便番号フォーマット
const formatPostcode = (value) => {
  const code1 = value.slice(0, 3);
  const code2 = value.slice(3);
  return `${code1}-${code2}`;
};
const edit = () => {
  router.push('/profile/edit');
};
</script>

<template>
  <div class="container">
    <div class="row header">
      <div class="title">プロフィール</div>
    </div>
    <table class="table">
      <tbody>
        <tr>
          <td>名前</td>
          <td>{{ user.name }}</td>
        </tr>
        <tr>
          <td>フリガナ</td>
          <td>{{ user.kana_name }}</td>
        </tr>
        <tr>
          <td>生年月日</td>
          <td>{{ formatDate(user.birth_date) }}</td>
        </tr>
        <tr>
          <td>性別</td>
          <td>{{ genderTextValue(user.gender) }}</td>
        </tr>
        <tr>
          <td>メールアドレス</td>
          <td>{{ user.email }}</td>
        </tr>
        <tr>
          <td>電話番号</td>
          <td>{{ user.phone }}</td>
        </tr>
        <tr>
          <td>住所</td>
          <td>{{ formatPostcode(user.postcode) }}&nbsp;{{ user.address }}</td>
        </tr>
        <tr>
          <td>権限</td>
          <td>{{ user.authority }}</td>
        </tr>
        <tr>
          <td>更新日</td>
          <td>{{ user.updated_at }}</td>
        </tr>
        <tr>
          <td>登録日</td>
          <td>{{ user.created_at }}</td>
        </tr>
      </tbody>
    </table>
    <div>
      <button type="button" @click="edit">プロフィールを編集する</button>
    </div>
  </div>
</template>
