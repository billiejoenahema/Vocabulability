// 全角数字を半角数字に変換する
export const toHalfWidth = (number) => {
  return number.replace(/[０-９]/g, (s) => {
    return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
  });
};

// ハイフンを取り除き、全角数字を半角数字に変換する
export const convertPostalCode = (postalCode) => {
  return postalCode
    .replaceAll(/[-－ー−―‐ーｰ]/g, '')
    .replace(/[０-９]/g, (s) => {
      return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
    });
};
