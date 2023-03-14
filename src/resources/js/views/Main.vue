<script setup>
import 'floating-vue/dist/style.css';
import { computed, onUnmounted, reactive, ref } from 'vue';
import 'vue-select/dist/vue-select.css';
import { useStore } from 'vuex';
import YubinBango from 'yubinbango-core2';
import InputPostalCode from '../components/InputPostalCode.vue';
import InputTel from '../components/InputTel.vue';
import InputText from '../components/InputText.vue';
import InputTextarea from '../components/InputTextarea.vue';
import Tooltip from '../components/Tooltip.vue';

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
  address: '',
  postal_code: '',
  remarks: '',
  long_text: '',
});
const invalidFeedback = computed(
  () => store.getters['profile/invalidFeedback']
);
const file = ref(null);
const fileUrl = ref(null);
const changeFile = (e) => {
  file.value = e.target.files[0];
  const objUrl = URL.createObjectURL(file.value);
  fileUrl.value = objUrl;
};
// 入力された郵便番号から住所を自動入力
const setAddress = (code) => {
  // 7文字の半角数字でなければエラーメッセージを表示する
  if (!code.match(/^\d{7}/)) {
    store.commit('profile/setErrors', {
      postal_code: ['半角数字7文字で入力してください。'],
    });
    return false;
  }
  new YubinBango.Core(code, (address) => {
    state.address = `${address.region}${address.locality}${address.street}`;
    if (!address.region) {
      store.commit('profile/setErrors', {
        postal_code: ['該当する住所が見つかりませんでした。'],
      });
    }
  });
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
  <Tooltip content="ツールチップが表示されました。">
    <div>テキストにカーソルを乗せるとツールチップが表示されます。</div>
  </Tooltip>
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
        v-model="state.postal_code"
        id="postalCode"
        placeholder="1010001"
        helper-text="半角数字7文字"
        :invalid-feedback="invalidFeedback('postal_code')"
        @search="setAddress"
      />
    </div>
    <div>住所: {{ state.address }}</div>
    <div class="input-text">
      <label>TEL</label>
      <InputTel
        v-model="state.tel"
        autocomplete="on"
        id="tel"
        type="tel"
        placeholder="例）09012345678"
        inputting-placeholder="on"
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
      <label>LongText</label>
      <InputTextarea
        v-model="state.long_text"
        autocomplete="on"
        :class-value="'class-value'"
        id="longText"
        helper-text="1000文字以内"
        :maxlength="1000"
        input-counter="on"
        :rows="6"
      />
    </div>
    <div class="input-text">
      <label>Remarks</label>
      <InputText
        v-model="state.remarks"
        autocomplete="on"
        id="remarks"
        type="text"
        helper-text="200文字以内"
        :maxlength="200"
        input-counter="on"
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
.input-postal-code {
  margin-right: 2rem;
}
</style>
