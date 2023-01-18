import dayjs from 'dayjs';

// 郵便番号フォーマット
export const formatPostcode = (value) => {
  const code1 = value.slice(0, 3);
  const code2 = value.slice(3);
  return `${code1}-${code2}`;
};

// 日付フォーマット
export const formatDate = (value) => {
  return dayjs(value).format('YYYY年MM月DD日');
};
