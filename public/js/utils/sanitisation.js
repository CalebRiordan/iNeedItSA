export function sanitise(string) {
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#x27;",
    "/": "&#x2F;",
  };
  const reg = /[&<>"'/]/gi;
  return string.replace(reg, (match) => map[match]);
}

export function anySpecialChars(string) {
    const reg = /[^a-zA-Z0-9\s\-_.@]/;
    return reg.test(string);
}
