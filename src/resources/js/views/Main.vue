<script setup>
import 'floating-vue/dist/style.css';
import { onUnmounted, reactive, ref } from 'vue';
import 'vue-select/dist/vue-select.css';
import { useStore } from 'vuex';
import InputPostalCode from '../components/InputPostalCode.vue';
import InputTel from '../components/InputTel.vue';
import InputText from '../components/InputText.vue';
import InputTextarea from '../components/InputTextarea.vue';

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
const remarks = ref('');
const longText = ref('');
const postalCode = ref('');
const file = ref(null);
const fileUrl = ref(null);
const changeFile = (e) => {
  store.commit('loading/setIsLoading', true);
  file.value = e.target.files[0];
  const objUrl = URL.createObjectURL(file.value);
  fileUrl.value = objUrl;
  setTimeout(() => {
    store.commit('loading/setIsLoading', false);
  }, 2000);
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
  <hr />
  <VTooltip :triggers="['click']" auto-hide>
    <p>テキストテキストテキスト...</p>
    <template #popper>
      テキスト全文テキスト全文テキスト全文テキスト
      全文テキスト全文テキスト全文テキスト全文
    </template>
  </VTooltip>
  <div class="input-text">
    <input type="file" @change="(e) => changeFile(e)" />
  </div>
  <div>
    <button @click="sendMail()">メール送信</button>
  </div>
  <div class="mw-400">
    <div class="input-text">
      <label>No.</label>
      <VueSelect :options="options"></VueSelect>
    </div>
    <div class="input-text">
      <label>PostalCode</label>
      <InputPostalCode
        v-model="postalCode"
        id="postalCode"
        placeholder="1001000"
        helper-text="半角数字7文字"
      />
    </div>
    <div class="input-text">
      <label>LongText</label>
      <InputTextarea
        v-model="longText"
        autocomplete="on"
        id="longText"
        type="text"
        help-text="1000文字以内"
        :maxlength="1000"
        :character-count="true"
        :rows="6"
      />
    </div>
    <div class="input-text">
      <label>Remarks</label>
      <InputText
        v-model="remarks"
        autocomplete="on"
        id="remarks"
        type="text"
        help-text="200文字以内"
        :maxlength="200"
        :character-count="true"
      />
    </div>
    <div class="input-text">
      <label>TEL</label>
      <InputTel
        v-model="state.tel"
        autocomplete="on"
        id="tel"
        type="tel"
        placeholder="例）09012345678"
        :inputting-placeholder="true"
      />
    </div>
    <div class="input-text">
      <label>Email</label>
      <InputText
        v-model="state.email"
        autocomplete="on"
        id="email"
        type="email"
        placeholder="例）example@example.com"
      />
    </div>
    <div class="input-text">
      <InputText type="month" id="month" v-model="state.month" />
    </div>
    <div class="input-text">
      <label>BirthDay</label>
      <InputText v-model="state.date" autocomplete="on" id="date" type="date" />
    </div>
    <div class="input-text">
      <InputText type="time" id="time" v-model="state.time" />
    </div>
    <div class="input-text">
      <InputText
        type="datetime-local"
        id="datetime"
        v-model="state.datetime"
        placeholder="例）2023年01月23日"
      />
    </div>
  </div>
</template>
<style>
pre {
  opacity: 1;
  margin: 0;
  padding: 0;
}
</style>
