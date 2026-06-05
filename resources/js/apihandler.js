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
      //   if (signal !== null) {
      //     console.log("signal available");
      //   } else {
      //     console.log("signal not available");
      //   }
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

window.apiJSONPOST = async function apiJSONPOST(data, url, button) {
  console.log("Post Request:", data);
  const buttonText = button.textContent;
  try {
    button.disabled = true;
    button.innerHTML = `<div class="flex items-center gap-2">
    <div class="w-4 h-4 border-2 border-gray-800 border-t-white rounded-full animate-spin"></div>
  </div>`;
    const response = await fetchWithRetry(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(data),
      credentials: "include",
    });

    button.disabled = false;
    button.innerHTML = buttonText;
    return response;
  } catch (err) {
    console.error(err);

    showMessage({
      status: "error",
      title: "Error Occured",
      message:
        "There is some error saving your information. Please contact system administrator",
    });

    button.disabled = false;
    button.innerHTML = buttonText;
  } finally {
    button.disabled = false;
    button.innerHTML = buttonText;
  }
};

window.apiJSONGET = async function apiJSONGET(url, data = null) {
  console.log("Get Request");
  try {
    const response = await fetchWithRetry(url, {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    });

    return response;
  } catch (err) {
    console.error(err);

    showMessage({
      status: "error",
      title: "Error Occured",
      message:
        "There is some error fetching your information. Please contact system administrator",
    });
  }
};

window.apiPOST = async function apiPOST(data, url, button) {
  console.log("Post Request:", data);
  const buttonText = button.textContent;
  try {
    button.disabled = true;
    button.innerHTML = `<div class="flex items-center gap-2">
    <div class="w-4 h-4 border-2 border-gray-800 border-t-white rounded-full animate-spin"></div>
  </div>`;
    const response = await fetchWithRetry(url, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: data,
      credentials: "include",
    });

    button.disabled = false;
    button.innerHTML = buttonText;
    return response;
  } catch (err) {
    console.error(err);

    showMessage({
      status: "error",
      title: "Error Occured",
      message:
        "There is some error saving your information. Please contact system administrator",
    });

    button.disabled = false;
    button.innerHTML = buttonText;
  } finally {
    button.disabled = false;
    button.innerHTML = buttonText;
  }
};

/*
sample usage


            FOR GET METHOD
            const companies = await apiCall({
                mode: "GET",
                url: "/api/companies"
            });


            FOR POST METHOD
            const response = await apiCall({
                    mode: "POST",
                    isJson: true,
                    payload: payload,
                    url: "/api/companies",
                    button: nextBtn
                });

            FOR DELETE METHOD
            const response = await apiCall({
                mode: "DELETE",
                isJson: true,
                payload: payload,
                url: "/api/lov",
                button: deleteButton    t
            });
*/
window.apiCall = async function apiCall({
  mode = "GET",
  isJson = true,
  payload = null,
  url,
  button = null,
}) {
  let originalText = "";

  try {
    // Button loading state
    if (button) {
      originalText = button.innerHTML;
      button.disabled = true;
      button.innerHTML = `
                <div class="flex items-center justify-center">
                    <div class="w-4 h-4 border-2 border-gray-800 border-t-white rounded-full animate-spin"></div>
                </div>`;
    }

    // Normalize method
    let method = mode.toUpperCase();
    if (method === "UPDATE") method = "PUT";

    // Get CSRF token safely
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");

    // Base headers
    let headers = {
      Accept: "application/json",
    };

    // Attach CSRF only for mutating requests
    if (["POST", "PUT", "PATCH", "DELETE"].includes(method) && csrfToken) {
      headers["X-CSRF-TOKEN"] = csrfToken;
    }

    let options = {
      method,
      headers,
      credentials: "include",
    };

    // Attach body
    if (method !== "GET" && payload) {
      if (isJson) {
        headers["Content-Type"] = "application/json";
        options.body = JSON.stringify(payload);
      } else {
        // FormData (browser sets headers automatically)
        options.body = payload;
      }
    }

    const response = await fetchWithRetry(url, options);
    return response;
  } catch (err) {
    console.error(err);

    showMessage({
      status: "error",
      title: "Error Occured",
      message:
        "There is some error saving your information. Please contact system administrator",
    });

    throw err;
  } finally {
    if (button) {
      button.disabled = false;
      button.innerHTML = originalText;
    }
  }
};
