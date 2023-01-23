// 全角数字を半角数字に変換する
export const toHalfWidth = (number) => {
  return number.replace(/[０-９]/g, (s) => {
    return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
  });
};
