window.apiRequest = async function apiRequest(
  url,
  method = "GET",
  data = null
) {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");

    const options = {
      method: method.toUpperCase(),
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
    };

    // ✅ Force POST data to be an array
    if (options.method === "POST" && data !== null) {
      options.body = JSON.stringify(Array.isArray(data) ? data : [data]);
    }

    const response = await fetch(url, options);

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const result = await response.json();

    // ✅ Always return array
    return Array.isArray(result) ? result : [result];
  } catch (error) {
    console.error("API Error:", error);
    return [];
  }
};
