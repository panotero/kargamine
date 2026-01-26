window.apiRequest = async function apiRequest(url, method = null, data = null) {
  try {
    if (method === null) {
      throw new Error("API Request Error: 'method' cannot be null.");
    }
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");

    const options = {
      method: method.toUpperCase(),
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
    };

    //  Force POST data to be an array
    if (options.method === "POST" && data !== null) {
      options.body = JSON.stringify(Array.isArray(data) ? data : [data]);
    }

    const response = await fetch(url, options);

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const result = await response.json();

    //  Always return array
    return Array.isArray(result) ? result : [result];
  } catch (error) {
    console.error("API Error:", error);
    return [];
  }
};

// fetch function with max retries implemented
window.fetchWithRetry = async function fetchWithRetry(
  url,
  options = {},
  signal = null,
  retries = 3,
  delay = 500,
) {
  let response = null;

  for (let i = 0; i < retries; i++) {
    try {
      if (signal !== null) {
        console.log("signal available");
      } else {
        console.log("signal not available");
      }
      // Clone options and attach signal if provided
      const fetchOptions = { ...options };
      if (signal) fetchOptions.signal = signal;

      const res = await fetch(url, fetchOptions);
      const text = await res.text();
      response = text ? JSON.parse(text) : null;

      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }

      // Handle responses with no JSON (ex: DELETE / 204)
      const contentType = res.headers.get("Content-Type") || "";
      if (!contentType.includes("application/json")) {
        return null; // or true if DELETE success
      }

      return text ? JSON.parse(text) : null;
    } catch (err) {
      // Handle abort specifically
      if (err.name === "AbortError") {
        console.warn(`Fetch aborted for ${url}`);
        return { response: response, success: false, aborted: true };
      }

      console.warn(`Fetch attempt ${i + 1} failed for ${url}:`, err);

      if (i < retries - 1) {
        await new Promise((r) => setTimeout(r, delay));
      } else {
        console.error(`All fetch attempts failed for ${url}`);
        return { response: response, success: false };
      }
    }
  }
};
