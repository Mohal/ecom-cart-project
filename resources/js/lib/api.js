import Cookies from 'js-cookie';

export async function api(path, options = {}) {
  // Ensure CSRF cookie is initialized before protected requests
  const token = Cookies.get('XSRF-TOKEN');

  const res = await fetch(`/api${path}`, {
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      ...(token ? { 'X-XSRF-TOKEN': token } : {}),
      ...(options.headers || {}),
    },
    credentials: 'include',
    ...options,
  });

  if (! res.ok) {
    if (res.status === 401) {
      window.location.href = "/login";
      // return;
      throw new Error("Unauthorized");
    }

    const text = await res.text();
    throw new Error(text || `Request failed: ${res.status}`);
  }

  // Only parse JSON if response is JSON
  const contentType = res.headers.get('content-type');
  if (contentType && contentType.includes('application/json')) {
    return res.json();
  }
  return null;
}
