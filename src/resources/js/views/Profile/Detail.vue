<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const user = computed(() => store.getters['profile/data']);
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
          <td>{{ user.birth_date }}</td>
        </tr>
        <tr>
          <td>性別</td>
          <td>{{ user.gender }}</td>
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
          <td>{{ user.postcode + ' ' + user.address }}</td>
        </tr>
        <tr>
          <td>権限</td>
          <td>{{ user.autdority }}</td>
        </tr>
        <tr>
          <td>更新日時</td>
          <td>{{ user.updated_at }}</td>
        </tr>
        <tr>
          <td>登録日時</td>
          <td>{{ user.created_at }}</td>
        </tr>
      </tbody>
    </table>
    <ul></ul>
  </div>
</template>
