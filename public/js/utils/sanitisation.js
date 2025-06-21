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

// Allows passing extra allowed characters as a string
export function anySpecialChars(string, extraAllowed = "") {
  // Escape regex special characters in extraAllowed
  const escaped = extraAllowed.replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&');
  // Build regex pattern
  const pattern = `[^a-zA-Z0-9\\s\\-_.@${escaped}]`;
  const reg = new RegExp(pattern);
  return reg.test(string);
}
