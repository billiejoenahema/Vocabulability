// エラーメッセージが表示されている項目までスクロールさせる
export const scrollToInvalidInput = () => {
  // エラーが表示されている要素をすべて取得
  const invalidElements = document.querySelectorAll('.is-invalid');
  if (!invalidElements) return;
  // ナビゲーションバーの高さ
  const headerHeight = document.getElementById('navigation').offsetHeight;
  let positions = [];
  invalidElements.forEach((v) => {
    positions.push(
      v.getBoundingClientRect().top + window.pageYOffset - headerHeight
    );
  });
  // 最も高い要素の位置を取得
  const highestPosition = positions?.reduce((acc, cur) =>
    acc < cur ? acc : cur
  );
  // 最も高い要素までスクロール
  window.scrollTo({
    top: highestPosition,
    behavior: 'smooth',
  });
};
