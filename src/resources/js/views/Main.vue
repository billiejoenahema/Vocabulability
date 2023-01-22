<script setup>
import { onUnmounted, reactive, ref } from 'vue';
import 'vue-select/dist/vue-select.css';
import { useStore } from 'vuex';
import BaseInput from '../components/BaseInput.vue';

const store = useStore();

const sendMail = () => {
  store.dispatch('test_mail/send');
};
const state = reactive({
  tel: '',
  email: '',
  month: '',
  date: '',
  time: '',
  datetime: '',
});
const file = ref(null);
const fileUrl = ref(null);
const changeFile = (e) => {
  file.value = e.target.files[0];
  const objUrl = URL.createObjectURL(file.value);
  fileUrl.value = objUrl;
};
const uploadFile = () => {
  const formData = new FormData();
  formData.append('file', file.value);
  store.dispatch('upload_file/post', formData);
};
const options = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
onUnmounted(() => URL.revokeObjectURL(fileUrl));
</script>

<template>
  <div class="container">
    <div class="row header">
      <div class="title">ページリスト</div>
    </div>
    <ul>
      <li><router-link to="/word_check">単語チェック</router-link></li>
      <li><router-link to="/word_list">単語リスト</router-link></li>
      <li><router-link to="/word_create">単語登録</router-link></li>
      <li><router-link to="/item_list">項目リスト</router-link></li>
      <li><router-link to="/item_create">項目登録</router-link></li>
    </ul>
  </div>
  <div class="input-text">
    <input type="file" @change="(e) => changeFile(e)" />
    <iframe :src="fileUrl" height="160" width="120"></iframe>
    <button type="button" @click="uploadFile">アップロード</button>
  </div>
  <div>
    <button @click="sendMail()">メール送信</button>
  </div>
  <div class="input-text">
    <label>No.</label>
    <vSelect :options="options"></vSelect>
  </div>
  <div class="input-text">
    <label>TEL</label>
    <BaseInput v-model="state.tel" autocomplete="on" id="tel" type="tel" />
  </div>
  <div class="input-text">
    <label>Email</label>
    <BaseInput
      v-model="state.email"
      autocomplete="on"
      id="email"
      type="email"
    />
  </div>
  <div class="input-text">
    <label>TEL</label>
    <BaseInput v-model="state.text" autocomplete="on" id="text" type="tel" />
  </div>
  <div class="input-text">
    <BaseInput type="month" id="month" v-model="state.month" />
  </div>
  <div class="input-text">
    <label>BirthDay</label>
    <BaseInput v-model="state.date" autocomplete="on" id="date" type="date" />
  </div>
  <div class="input-text">
    <BaseInput type="time" id="time" v-model="state.time" />
  </div>
  <div class="input-text">
    <BaseInput type="datetime-local" id="datetime" v-model="state.datetime" />
  </div>
</template>
