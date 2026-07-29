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
//
// Only genuine network failures (fetch() itself throwing) and 5xx server
// errors are retried - those are the only cases a second attempt could
// plausibly fix. A 4xx (validation, auth, permission, not-found) means the
// request itself was rejected; retrying just re-sends the same bad request
// 3 times before giving up. Every branch below returns the parsed JSON body
// directly (never wrapped in an extra {response: ...} envelope) so a caller
// can read response.message / response.errors / response.data the same way
// on success or failure.
window.fetchWithRetry = async function fetchWithRetry(
  url,
  options = {},
  signal = null,
  retries = 3,
  delay = 500,
) {
  for (let i = 0; i < retries; i++) {
    let res;

    try {
      const fetchOptions = { ...options };
      if (signal) fetchOptions.signal = signal;
      res = await fetch(url, fetchOptions);
    } catch (err) {
      if (err.name === "AbortError") {
        console.warn(`Fetch aborted for ${url}`);
        return { success: false, aborted: true };
      }

      console.warn(`Fetch attempt ${i + 1} failed for ${url}:`, err);

      if (i < retries - 1) {
        await new Promise((r) => setTimeout(r, delay));
        continue;
      }

      console.error(`All fetch attempts failed for ${url}`);
      return {
        success: false,
        message: "Unable to reach the server. Please check your connection.",
      };
    }

    if (res.status === 401) {
      window.location.reload();
      return;
    }

    const contentType = res.headers.get("Content-Type") || "";
    const text = await res.text();
    const body = contentType.includes("application/json") && text
      ? JSON.parse(text)
      : null;

    if (res.ok) {
      return body; // includes "no content" (e.g. DELETE / 204) as null
    }

    // 4xx - not retryable, surface the actual error body immediately.
    if (res.status < 500) {
      return body ?? { success: false, message: `Request failed (${res.status}).` };
    }

    // 5xx - may be transient, worth another attempt.
    if (i < retries - 1) {
      await new Promise((r) => setTimeout(r, delay));
      continue;
    }

    console.error(`All fetch attempts failed for ${url}`);
    return body ?? { success: false, message: `Server error (${res.status}).` };
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
// -----------------------------------------------------------------
// Field-level validation error highlighting
// -----------------------------------------------------------------
// Laravel's own $request->validate() failures come back as
// {message, errors: {field: [msgs]}} with no "success" key; a few
// controllers manually validate and return {success:false, invalid_fields:
// {...}} instead. This normalizes both into one errors object.
function extractFieldErrors(response) {
  if (!response || typeof response !== "object") return null;
  if (response.errors && typeof response.errors === "object") {
    return response.errors;
  }
  if (response.invalid_fields && typeof response.invalid_fields === "object") {
    return response.invalid_fields;
  }
  return null;
}

// Resolves which element(s) an error key refers to. Handles the naming
// conventions actually used across this app's forms:
//   - "client_type"                 -> [name="client_type"] or #client_type
//   - "finance.credit_terms"        -> nested field also tried as the
//                                      bracket-style name="finance[credit_terms]"
//   - "containers.2.container_type" -> the 3rd [data-field="container_type"]
//                                      inside a repeated row (crm lead
//                                      containers/addresses, booking cargo
//                                      lines, contract rate rows, ...)
//   - "source"                      -> falls back to a name-prefix match
//                                      (e.g. source_select/source_other)
//                                      when nothing is named exactly "source"
function findFieldElements(container, key) {
  const arrayMatch = key.match(/^[a-zA-Z0-9_]+\.(\d+)\.(.+)$/);
  if (arrayMatch) {
    const index = Number(arrayMatch[1]);
    const leaf = arrayMatch[2];
    const candidates = container.querySelectorAll(
      `[data-field="${leaf}"], [name="${leaf}"]`,
    );
    return candidates[index] ? [candidates[index]] : [];
  }

  const byName = container.querySelector(`[name="${key}"]`);
  if (byName) return [byName];

  const byDataField = container.querySelector(`[data-field="${key}"]`);
  if (byDataField) return [byDataField];

  const byId = document.getElementById(key);
  if (byId && container.contains(byId)) return [byId];

  if (key.includes(".")) {
    const parts = key.split(".");
    const bracketName = parts[0] + parts.slice(1).map((p) => `[${p}]`).join("");
    const byBracket = container.querySelector(`[name="${bracketName}"]`);
    if (byBracket) return [byBracket];
  }

  const byPrefix = container.querySelectorAll(`[name^="${key}_"], [id^="${key}_"]`);
  return byPrefix.length ? Array.from(byPrefix) : [];
}

const FIELD_ERROR_CLASSES = ["border-red-500", "ring-1", "ring-red-500"];

window.clearFieldErrors = function clearFieldErrors(container) {
  if (!container) return;
  container.querySelectorAll(".field-error-flagged").forEach((el) => {
    el.classList.remove("field-error-flagged", ...FIELD_ERROR_CLASSES);
  });
  container.querySelectorAll(".field-error-message").forEach((el) => el.remove());
};

// Paints a red border + inline message on every field named in `errors`
// (a {field: message|message[]} map) found inside `container`, and clears
// each one automatically the moment the user edits it.
window.applyFieldErrors = function applyFieldErrors(container, errors) {
  if (!container || !errors) return;
  clearFieldErrors(container);

  let firstEl = null;

  Object.entries(errors).forEach(([key, messages]) => {
    const message = Array.isArray(messages) ? messages[0] : messages;
    const elements = findFieldElements(container, key);

    elements.forEach((el) => {
      el.classList.add("field-error-flagged", ...FIELD_ERROR_CLASSES);

      const hint = document.createElement("p");
      hint.className = "field-error-message text-xs text-red-500 mt-1";
      hint.textContent = message;
      el.insertAdjacentElement("afterend", hint);

      const clearOnEdit = () => {
        el.classList.remove("field-error-flagged", ...FIELD_ERROR_CLASSES);
        hint.remove();
        el.removeEventListener("input", clearOnEdit);
        el.removeEventListener("change", clearOnEdit);
      };
      el.addEventListener("input", clearOnEdit);
      el.addEventListener("change", clearOnEdit);

      if (!firstEl) firstEl = el;
    });
  });

  if (firstEl) {
    firstEl.scrollIntoView({ behavior: "smooth", block: "center" });
    firstEl.focus?.();
  }
};

// Best-effort container to scope field lookups to: the enclosing <form> if
// there is one (not every page wraps its fields in <form>, e.g. the booking
// form is plain divs), else the nearest modal, else the whole SPA content
// area - always narrower than `document` so unrelated fields elsewhere on
// the page never get flagged by a same-named field.
function resolveFieldErrorContainer(button) {
  if (!button) return null;
  return (
    button.closest("form") ||
    button.closest('[id$="Modal"], .modal, .side-modal-panel') ||
    document.getElementById("content") ||
    document.body
  );
}

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

    // Paint/clear field-level red borders wherever this call was tied to a
    // submit button - covers every form using apiCall({..., button}) with
    // no per-page changes needed.
    const errorContainer = resolveFieldErrorContainer(button);
    if (errorContainer) {
      const fieldErrors = extractFieldErrors(response);
      if (fieldErrors) {
        applyFieldErrors(errorContainer, fieldErrors);
      } else {
        clearFieldErrors(errorContainer);
      }
    }

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
