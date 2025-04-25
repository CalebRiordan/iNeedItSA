export function buildQueryString(params = {}) {
  const query = Object.entries(params)
    .filter(
      ([_, value]) =>
        value !== undefined &&
        value !== null &&
        value !== 0 &&
        value !== "" &&
        !Number.isNaN(value)
    )
    .map(
      ([key, value]) =>
        `${encodeURIComponent(key)}=${encodeURIComponent(value)}`
    )
    .join("&");

  return query.length > 0 ? `?${query}` : "";
}
