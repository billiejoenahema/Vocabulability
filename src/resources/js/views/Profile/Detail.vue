<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { formatDate, formatPostcode } from '../../functions/format.js';

const router = useRouter();
const store = useStore();

store.dispatch('profile/get');
const user = computed(() => store.getters['profile/data']);
const genderTextValue = computed(() => store.getters['consts/genderTextValue']);

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
          <td>{{ formatDate(user.birth_date, 'YYYY年MM月DD日') }}</td>
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
        <tr>
          <td>最終ログイン</td>
          <td>{{ user.last_login_at }}</td>
        </tr>
      </tbody>
    </table>
    <div>
      <button type="button" @click="edit">プロフィールを編集する</button>
    </div>
  </div>
</template>
