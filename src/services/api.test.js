import { describe, it, expect, vi, beforeEach, afterEach } from "vitest";
import {
  apiFetch,
  setToken,
  removeToken,
  flattenValidationErrors,
  parseResourceCollection,
  parseRawPaginator,
} from "./api";

describe("api helpers", () => {
  it("flattens validation errors", () => {
    const errors = flattenValidationErrors({
      phone: ["required"],
      password: "short",
    });
    expect(errors.phone).toEqual(["required"]);
    expect(errors.password).toEqual(["short"]);
  });

  it("parses resource collection payload", () => {
    const list = parseResourceCollection({
      data: [{ id: 1 }, { id: 2 }],
      meta: {},
    });
    expect(list).toHaveLength(2);
  });

  it("parses raw paginator payload", () => {
    const list = parseRawPaginator({ data: [{ id: 11 }] });
    expect(list).toEqual([{ id: 11 }]);
  });
});

describe("apiFetch", () => {
  const originalFetch = global.fetch;

  beforeEach(() => {
    localStorage.clear();
    global.fetch = vi.fn();
  });

  afterEach(() => {
    global.fetch = originalFetch;
    vi.restoreAllMocks();
  });

  it("unwraps success envelope to data payload", async () => {
    global.fetch.mockResolvedValueOnce({
      status: 200,
      ok: true,
      json: async () => ({ success: true, data: { id: 1 }, message: "ok" }),
    });

    const payload = await apiFetch("/any");
    expect(payload).toEqual({ id: 1 });
  });

  it("throws validation error shape for 422", async () => {
    global.fetch.mockResolvedValueOnce({
      status: 422,
      ok: false,
      json: async () => ({
        message: "Validation failed",
        errors: { phone: ["required"] },
      }),
    });

    await expect(
      apiFetch("/auth/login", { method: "POST", body: JSON.stringify({}) }),
    ).rejects.toMatchObject({
      type: "validation",
      status: 422,
      errors: { phone: ["required"] },
    });
  });

  it("clears token and throws auth error on 401", async () => {
    setToken("abc");

    global.fetch.mockResolvedValueOnce({
      status: 401,
      ok: false,
      json: async () => ({ message: "Unauthenticated." }),
    });

    await expect(apiFetch("/user/profile")).rejects.toMatchObject({
      type: "auth",
      status: 401,
    });
    expect(localStorage.getItem("kandura_access_token")).toBeNull();
    removeToken();
  });
});
